import {API_ROUTES} from "../config/routes";
import AxiosClient from "../library/AxiosClient";


export async function authLogin(data) {
    return await AxiosClient.post(API_ROUTES.authLogin, data).catch(err => err.response)
}

export async function authLogout() {
    return await AxiosClient.delete(API_ROUTES.authLogout).catch(err => err.response)
}
