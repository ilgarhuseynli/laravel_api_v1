import {Navigate, useLocation} from "react-router-dom";
import {useSelector} from "react-redux";

export const PrivateRoute = ({children}) => {

    const {user} = useSelector(state => state.auth);
    const location = useLocation();

    if (!user){
        return  <Navigate to={'/auth/login'} replace={true} state={{
            return_url:location.pathname + location.search
        }} />
    }

    return children

}
