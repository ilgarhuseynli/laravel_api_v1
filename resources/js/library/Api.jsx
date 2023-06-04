import moment from "moment-timezone";
import {API_ROUTES} from "../config/routes";
import {config} from "../config/config";
import {serializeQuery} from "./utils";

export class Api {

  static async request(url, method = "GET", params) {
    let options = {
      method,
    };
    if(method !== "GET"){
      options.body = serializeQuery(this.filterParams(params));
      options.body = Object.assign(params, options.body);
    }
    return await fetch(
      method === "GET"
        ? `${API_ROUTES[url]}?${serializeQuery(this.filterParams(params))}`
        : API_ROUTES[url],
      {
        method,
        mode: "cors",
        cache: "no-cache",
        credentials: "same-origin",
        headers: {
          Accept: "application/json",
        },
        redirect: "follow",
        referrerPolicy: "no-referrer",
        body:
          method !== "GET" ? options.body : null,
      }
    )
      .then(async (res) => {
        const response = await res.json();
        if (response.error_code === 1001) {
          //   Auth.logOut();
          return;
        }
        return response;
      })
      .catch((err) => {
        // console.error(err)
        throw new Error(err);
      });
  }
  static async get(url, params) {
    return await this.request(url, "GET", params);
  }
  static async post(url, params) {
    return await this.request(url, "POST", params);
  }
  static async put(url, params) {
    return await this.request(url, "PUT", params);
  }
  static async del(url, params) {
    return await this.request(url, "DELETE", params);
  }
  static filterParams(params) {
    // let token = Auth.get("token");
    // let token_user = Auth.get("_id");
    let obj = {
      app_id: config.appID,
      // env: process.env.NODE_ENV,
      tz: moment.tz.guess(),
      ts: moment(new Date()).format("YYYY-MM-DD HH:mm:ss"),
    };
    // if (token) {
    //   obj.token = token;
    // }
    // if (token_user) {
    //   obj.token_user = token_user;
    // }
    if (params) {
      Object.keys(params).map((key) => {
        let value = params[key];
        if (!value) {
          value = "";
        }
        obj[key] = value;

        return obj;
      });
    }
    return obj;
  }
}
