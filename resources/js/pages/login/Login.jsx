import "./login.scss"
import {useState} from "react";
import {useDispatch, useSelector} from "react-redux";
import {useNavigate, Navigate, useLocation, Link} from "react-router-dom";
import {login} from "../../stores/authSlice";
import {url} from "../../library/utils";
import {authLogin} from "../../actions";

const Login = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const dispatch = useDispatch();
    const {user} = useSelector(state => state.auth);


    const [email, setEmail] = useState('');
    const [loading, setLoading] = useState(false);
    const [password, setPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);

    const loginHandler = async (e) => {
        e.preventDefault();

        setLoading(true)

        let response = await authLogin({email, password})

        if (response.data.status === 'success'){
            let resData = response.data;

            dispatch(login(resData.data))

            let returnUrl = location.state?.return_url || '/'

            navigate(returnUrl)
        }
    }

    if (user) {
        return <Navigate to={'/'}/>
    }

    return (
        <div className="card-body p-4">

            <div className="text-center w-75 m-auto">
                <h4 className="text-dark-50 text-center pb-0 fw-bold">Sign In</h4>
                <p className="text-muted mb-4">Enter your email address and password to access admin panel.</p>
            </div>

            <form className='login-form' onSubmit={loginHandler}>

                <div className="mb-3">
                    <label htmlFor="emailaddress" className="form-label">Email address</label>
                    <input
                        type="email"
                        required
                        id="emailaddress"
                        className="form-control"
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        placeholder='Enter your email'
                    />
                </div>

                <div className="mb-3">
                    {/*<a href="pages-recoverpw.html" className="text-muted float-end">*/}
                    {/*    <small>Forgot your password?</small>*/}
                    {/*</a>*/}
                    <label htmlFor="password" className="form-label">Password</label>
                    <div className="input-group input-group-merge">
                        <input
                            className="form-control"
                            type={showPassword ? 'text' : 'password'}
                            id="password"
                            required
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder='Enter your password'
                        />
                        <div className={`input-group-text  ${showPassword ? 'show-password' : ''}`}
                             onClick={() => setShowPassword(!showPassword)} data-password="false">
                            <span className="password-eye"/>
                        </div>
                    </div>
                </div>

                <div className="mb-3 mb-3">
                    <div className="form-check">
                        <input type="checkbox" className="form-check-input" id="checkbox-signin"/>
                        <label className="form-check-label" htmlFor="checkbox-signin">Remember me</label>
                    </div>
                </div>

                <div className="mb-3 mb-0 text-center">
                    <button className="btn btn-primary fs-4" disabled={loading} type="submit">
                        {loading && <i className='mdi mdi-refresh mdi-spin me-1' />}
                        <span>Log In</span>
                    </button>
                </div>

            </form>


            <div className="row mt-3">
                <div className="col-12 text-center">
                    <p className="text-muted">Don't have an account?
                        <Link to={url('auth.register')} className="text-muted ms-1"><b>Sign Up</b></Link>
                    </p>
                </div>
            </div>

        </div>
    )
}

export default Login
