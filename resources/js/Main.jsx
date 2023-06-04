import {RouterProvider, createBrowserRouter} from "react-router-dom";
import routes from "./config/routes";
import {useSelector} from "react-redux";

function Main() {
    const {darkMode} = useSelector(state=>state.app);

    let router = createBrowserRouter(routes)

    return (
        <div className={`app ${darkMode && 'dark'}`}>
            <RouterProvider router={router}/>
        </div>
    );
}

export default Main;
