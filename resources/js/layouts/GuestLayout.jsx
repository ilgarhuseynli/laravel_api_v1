import {Outlet} from 'react-router-dom'

const GuestLayout = () => {
    return (
        <>
            <div className="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
                <div className="container">
                    <div className="row justify-content-center">
                        <div className="col-xxl-4 col-lg-5">
                            <div className="card">

                                <div className="card-header pt-4 pb-4 text-center bg-primary">
                                    <a href="#">
                                        <span><img src="/assets/images/logo.png" alt="" height="18"/></span>
                                    </a>
                                </div>

                                <Outlet/>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <footer className="footer footer-alt">
                2022 © React-Dashboard - <a target='_blank' className='text-muted'
                                            href="https://ilqarhuseynli.com">ilqarhuseynli.com</a>
            </footer>

        </>
    );
};

export default GuestLayout;
