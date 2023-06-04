import "./home.scss";
import Widget from "../../components/widget/Widget";
import Featured from "../../components/featured/Featured";
import Chart from "../../components/chart/Chart";
import Table from "../../components/table/Table";

const Home = () => {


    return (
        <div className="container-fluid">
            <div className="row">
                <div className="col-12">
                    <div className="page-title-box">
                        <div className="page-title-right">
                            {/*<ol className="breadcrumb m-0">*/}
                            {/*    <li className="breadcrumb-item"><a href="#">Dashboard</a>*/}
                            {/*    </li>*/}
                            {/*    <li className="breadcrumb-item"><a href="#">Pages</a>*/}
                            {/*    </li>*/}
                            {/*    <li className="breadcrumb-item active">Dashboard</li>*/}
                            {/*</ol>*/}
                        </div>
                        <h4 className="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>
            <div className={'home'}>
                <div className="widgets">
                    <Widget type="user" />
                    <Widget type="order" />
                    <Widget type="earning" />
                    <Widget type="balance" />
                </div>
                <div className="charts">
                    <Featured />
                    <Chart title="Last 6 Months (Revenue)" aspect={2 / 1} />
                </div>
                <div className="listContainer">
                    <div className="listTitle">Latest Transactions</div>
                    <Table />
                </div>
            </div>
        </div>
    )
};

export default Home;
