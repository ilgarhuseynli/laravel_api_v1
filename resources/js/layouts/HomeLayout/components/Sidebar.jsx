import SimpleBar from 'simplebar-react';
import 'simplebar-react/dist/simplebar.min.css';
import {Link, useNavigate} from "react-router-dom";
import {url} from "../../../library/utils";
import {useDispatch} from "react-redux";
import { logout} from "../../../stores/authSlice";
import {authLogout} from "../../../actions";

const Sidebar = () => {

    const dispatch = useDispatch();
    const navigate = useNavigate();

    const logoutHandler = async () => {
        let response = await authLogout()

        if (response.data.status === 'success'){
            dispatch(logout());
            navigate(url('auth.login'))
        }
    }

    return (
        <div className="leftside-menu">
            <Link to={url('home')} className="logo text-center logo-light">
                    <span className="logo-lg">
                        <img src="assets/images/logo.png" alt="" height="16"/>
                    </span>
                <span className="logo-sm">
                        <img src="assets/images/logo_sm.png" alt="" height="16"/>
                    </span>
            </Link>

            <Link to={url('home')} className="logo text-center logo-dark">
                    <span className="logo-lg">
                        <img src="assets/images/logo-dark.png" alt="" height="16"/>
                    </span>
                <span className="logo-sm">
                        <img src="assets/images/logo_sm_dark.png" alt="" height="16"/>
                    </span>
            </Link>

            <div className="h-100" id="leftside-menu-container" >

                <SimpleBar  style={{ maxHeight: '100%' }} >

                    <ul className="side-nav">

                        <li className="side-nav-title side-nav-item">Navigation</li>

                        <li className="side-nav-item">
                            <Link to={url('home')} className="side-nav-link">
                                <i className="uil-home-alt" />
                                <span className="badge bg-success float-end">4</span>
                                <span> Dashboards </span>
                            </Link>
                        </li>

                        <li className="side-nav-title side-nav-item">Apps</li>

                        <li className="side-nav-item">
                            <a href="apps-calendar.html" className="side-nav-link">
                                <i className="uil-calender" />
                                <span> Calendar </span>
                            </a>
                        </li>

                        <li className="side-nav-item">
                            <a href="apps-chat.html" className="side-nav-link">
                                <i className="uil-comments-alt" />
                                <span> Chat </span>
                            </a>
                        </li>

                        <li className="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEcommerce" aria-expanded="false"
                               aria-controls="sidebarEcommerce" className="side-nav-link">
                                <i className="uil-store" />
                                <span> Ecommerce </span>
                                <span className="menu-arrow"></span>
                            </a>
                            <div className="collapse" id="sidebarEcommerce">
                                <ul className="side-nav-second-level">
                                    <li>
                                        <a href="apps-ecommerce-products.html">Products</a>
                                    </li>
                                    <li>
                                        <a href="apps-ecommerce-products-details.html">Products Details</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li className="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarEmail" aria-expanded="false"
                               aria-controls="sidebarEmail" className="side-nav-link">
                                <i className="uil-envelope" />
                                <span> Email </span>
                                <span className="menu-arrow"></span>
                            </a>
                            <div className="collapse" id="sidebarEmail">
                                <ul className="side-nav-second-level">
                                    <li>
                                        <a href="apps-email-inbox.html">Inbox</a>
                                    </li>
                                    <li>
                                        <a href="apps-email-read.html">Read Email</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li className="side-nav-title side-nav-item">Custom</li>

                        <li className="side-nav-item">
                            <a data-bs-toggle="collapse" href="#sidebarPages" aria-expanded="false"
                               aria-controls="sidebarPages" className="side-nav-link">
                                <i className="uil-copy-alt" />
                                <span> Pages </span>
                                <span className="menu-arrow"></span>
                            </a>
                            <div className="collapse" id="sidebarPages">
                                <ul className="side-nav-second-level">
                                    <li>
                                        <a href="pages-profile.html">Profile</a>
                                    </li>
                                    <li>
                                        <a href="pages-profile-2.html">Profile 2</a>
                                    </li>
                                    <li>
                                        <a href="pages-invoice.html">Invoice</a>
                                    </li>
                                    <li>
                                        <a href="pages-faq.html">FAQ</a>
                                    </li>
                                    <li>
                                        <a href="pages-pricing.html">Pricing</a>
                                    </li>
                                    <li>
                                        <a href="pages-maintenance.html">Maintenance</a>
                                    </li>
                                    <li className="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarPagesAuth" aria-expanded="false"
                                           aria-controls="sidebarPagesAuth">
                                            <span> Authentication </span>
                                            <span className="menu-arrow"></span>
                                        </a>
                                        <div className="collapse" id="sidebarPagesAuth">
                                            <ul className="side-nav-third-level">
                                                <li>
                                                    <a href="pages-login.html">Login</a>
                                                </li>
                                                <li>
                                                    <a href="pages-login-2.html">Login 2</a>
                                                </li>
                                                <li>
                                                    <a href="pages-register.html">Register</a>
                                                </li>
                                                <li>
                                                    <a href="pages-register-2.html">Register 2</a>
                                                </li>
                                                <li>
                                                    <a href="pages-logout.html">Logout</a>
                                                </li>
                                                <li>
                                                    <a href="pages-logout-2.html">Logout 2</a>
                                                </li>
                                                <li>
                                                    <a href="pages-recoverpw.html">Recover Password</a>
                                                </li>
                                                <li>
                                                    <a href="pages-recoverpw-2.html">Recover Password 2</a>
                                                </li>
                                                <li>
                                                    <a href="pages-lock-screen.html">Lock Screen</a>
                                                </li>
                                                <li>
                                                    <a href="pages-lock-screen-2.html">Lock Screen 2</a>
                                                </li>
                                                <li>
                                                    <a href="pages-confirm-mail.html">Confirm Mail</a>
                                                </li>
                                                <li>
                                                    <a href="pages-confirm-mail-2.html">Confirm Mail 2</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li className="side-nav-item">
                                        <a data-bs-toggle="collapse" href="#sidebarPagesError" aria-expanded="false"
                                           aria-controls="sidebarPagesError">
                                            <span> Error </span>
                                            <span className="menu-arrow"></span>
                                        </a>
                                        <div className="collapse" id="sidebarPagesError">
                                            <ul className="side-nav-third-level">
                                                <li>
                                                    <a href="pages-404.html">Error 404</a>
                                                </li>
                                                <li>
                                                    <a href="pages-404-alt.html">Error 404-alt</a>
                                                </li>
                                                <li>
                                                    <a href="pages-500.html">Error 500</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li>
                                        <a href="pages-starter.html">Starter Page</a>
                                    </li>
                                    <li>
                                        <a href="pages-preloader.html">With Preloader</a>
                                    </li>
                                    <li>
                                        <a href="pages-timeline.html">Timeline</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li className="side-nav-item">
                            <a href="landing.html" target="_blank" className="side-nav-link">
                                <i className="uil-globe" />
                                <span className="badge bg-secondary text-light float-end">New</span>
                                <span> Landing </span>
                            </a>
                        </li>

                        <li className="side-nav-item" onClick={logoutHandler}>
                            <a href="#" className='side-nav-link'>
                                <i className="uil-exit" />
                                <span> Logout </span>
                            </a>
                        </li>


                    </ul>
                    <div className="clearfix"></div>

                </SimpleBar>
            </div>
        </div>
    );
};

export default Sidebar;
