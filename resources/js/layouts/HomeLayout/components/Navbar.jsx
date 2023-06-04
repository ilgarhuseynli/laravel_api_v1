import {useDispatch, useSelector} from "react-redux";
import {toggle} from "../../../stores/appSlice";

const Navbar = () => {

    const {darkMode} = useSelector(state => state.app)
    const dispatch = useDispatch()


    const onThemeChange = () =>{

        dispatch(toggle())

        if (!darkMode){
            document.body.setAttribute('data-leftbar-theme','dark');
            document.getElementById('dark-style').removeAttribute('disabled');
            document.getElementById('light-style').setAttribute('disabled','disabled');
        }else{
            document.body.setAttribute('data-leftbar-theme','light');
            document.getElementById('light-style').removeAttribute('disabled');
            document.getElementById('dark-style').setAttribute('disabled','disabled');
        }
    }

    return (
        <div className="navbar-custom">
            <ul className="list-unstyled topbar-menu float-end mb-0">
                <li className="dropdown notification-list d-lg-none">
                    <a className="nav-link dropdown-toggle arrow-none" data-bs-toggle="dropdown" href="#"
                       role="button" aria-haspopup="false" aria-expanded="false">
                        <i className="dripicons-search noti-icon"/>
                    </a>
                    <div className="dropdown-menu dropdown-menu-animated dropdown-lg p-0">
                        <form className="p-3">
                            <input type="text" className="form-control" placeholder="Search ..."
                                   aria-label="Recipient's username"/>
                        </form>
                    </div>
                </li>

                <li className="notification-list">
                    <span
                        style={{cursor:'pointer'}}
                        className="nav-link"
                        onClick={onThemeChange}
                    >
                        <i className={` ${darkMode ? 'uil-sun':'uil-moon'} noti-icon`}/>
                    </span>
                </li>

                <li className="dropdown notification-list">
                    <a className="nav-link dropdown-toggle nav-user arrow-none me-0"
                       data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                       aria-expanded="false">
                        <span className="account-user-avatar">
                            <img src="assets/images/users/avatar-6.jpg" alt="user-image" className="rounded-circle"/>
                        </span>
                        <span>
                                <span className="account-user-name">Soeng Souy</span>
                                <span className="account-position">Founder</span>
                        </span>
                    </a>
                </li>

            </ul>
            <button className="button-menu-mobile open-left">
                <i className="mdi mdi-menu"></i>
            </button>
            <div className="app-search dropdown d-none d-lg-block">
                <form>
                    <div className="input-group">
                        <input type="text" className="form-control dropdown-toggle" placeholder="Search..."
                               id="top-search"/>
                        <span className="mdi mdi-magnify search-icon"></span>
                        <button className="input-group-text btn-primary" type="submit">Search</button>
                    </div>
                </form>

                <div className="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
                    <div className="dropdown-header noti-title">
                        <h5 className="text-overflow mb-2">Found <span
                            className="text-danger">17</span> results</h5>
                    </div>

                    <a href="#" className="dropdown-item notify-item">
                        <i className="uil-notes font-16 me-1"></i>
                        <span>Analytics Report</span>
                    </a>

                    <a href="#" className="dropdown-item notify-item">
                        <i className="uil-life-ring font-16 me-1"></i>
                        <span>How can I help you?</span>
                    </a>

                    <a href="#" className="dropdown-item notify-item">
                        <i className="uil-cog font-16 me-1"></i>
                        <span>User profile settings</span>
                    </a>

                    <div className="dropdown-header noti-title">
                        <h6 className="text-overflow mb-2 text-uppercase">Users</h6>
                    </div>

                    <div className="notification-list">
                        <a href="#" className="dropdown-item notify-item">
                            <div className="d-flex">
                                <img className="d-flex me-2 rounded-circle"
                                     src="assets/images/users/avatar-2.jpg" alt="Generic placeholder image"
                                     height="32"/>
                                <div className="w-100">
                                    <h5 className="m-0 font-14">Erwin Brown</h5>
                                    <span className="font-12 mb-0">UI Designer</span>
                                </div>
                            </div>
                        </a>

                        <a href="#" className="dropdown-item notify-item">
                            <div className="d-flex">
                                <img className="d-flex me-2 rounded-circle"
                                     src="assets/images/users/avatar-5.jpg" alt="Generic placeholder image"
                                     height="32"/>
                                <div className="w-100">
                                    <h5 className="m-0 font-14">Jacob Deo</h5>
                                    <span className="font-12 mb-0">Developer</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Navbar;
