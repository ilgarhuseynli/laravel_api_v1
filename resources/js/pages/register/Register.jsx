import "./register.scss"
import {useReducer} from "react";
import {useNavigate, Navigate, useLocation, Link} from "react-router-dom";
import {useDispatch, useSelector} from "react-redux";
import {login} from "../../stores/authSlice";
import {url} from "../../library/utils";

const Register = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const dispatch = useDispatch();
    const {user} = useSelector(state => state.auth);

    const [state, setState] = useReducer((prevState, newState) => {
        return {...prevState, ...newState}
    }, {
        email: '',
        name: '',
        password: '',
    })

    const loginHandler = (e) => {
        e.preventDefault();
        let userObj = {
            email: 'Huseyn@gmail.com',
            name: 'Huseyn',
            surname: 'Aliyev',
            age: 30,
            password: '1234',
        }

        if (state.password === userObj.password) {
            dispatch(login(userObj));

            let returnUrl = location.state?.return_url || '/'

            navigate(returnUrl)
        } else {
            alert('User not found');
        }
    }

    if (user) {
        return <Navigate to={'/'}/>
    }

    return (
        <div className="card-body p-4">

            <div className="text-center w-75 m-auto">
                <h4 className="text-dark-50 text-center mt-0 fw-bold">Free Sign Up</h4>
                <p className="text-muted mb-4">Don't have an account? Create your account, it takes less than a
                    minute </p>
            </div>

            <form action="#">

                <div className="mb-3">
                    <label htmlFor="fullname" className="form-label">Full Name</label>
                    <input className="form-control" type="text" id="fullname" placeholder="Enter your name" required/>
                </div>

                <div className="mb-3">
                    <label htmlFor="emailaddress" className="form-label">Email address</label>
                    <input className="form-control" type="email" id="emailaddress" required
                           placeholder="Enter your email"/>
                </div>

                <div className="mb-3">
                    <label htmlFor="password" className="form-label">Password</label>
                    <div className="input-group input-group-merge">
                        <input type="password" id="password" className="form-control"
                               placeholder="Enter your password"/>
                        <div className="input-group-text" data-password="false">
                            <span className="password-eye" />
                        </div>
                    </div>
                </div>

                <div className="mb-3">
                    <div className="form-check">
                        <input type="checkbox" className="form-check-input" id="checkbox-signup"/>
                        <label className="form-check-label" htmlFor="checkbox-signup">
                            I accept <a href="#" className="text-muted">Terms and Conditions</a>
                        </label>
                    </div>
                </div>

                <div className="mb-3 text-center">
                    <button className="btn btn-primary" type="submit"> Sign Up</button>
                </div>

            </form>


            <div className="row mt-3">
                <div className="col-12 text-center">
                    <p className="text-muted">Already have account?
                        <Link to={url('auth.login')} className="text-muted ms-1"><b>Log In</b></Link>
                    </p>
                </div>
            </div>

        </div>

    )
}

export default Register
