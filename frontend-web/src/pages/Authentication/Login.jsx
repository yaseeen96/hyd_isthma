import { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { setPageTitle } from '../../store/themeConfigSlice';
import { useNavigate } from 'react-router-dom';
import IconPhone from '../../components/Icon/IconPhone';
import AuthLayout from './Layout/AuthLayout';

const Login = () => {
    const dispatch = useDispatch();
    useEffect(() => {
        dispatch(setPageTitle('Login'));
    });
    const navigate = useNavigate();
    function submitForm() {
        navigate('/auth/verifyOtp');
    }
    return (
        <AuthLayout title="Enter your phone number to login">
            <form className="space-y-5 dark:text-white" onSubmit={submitForm}>
                <div>
                    <label htmlFor="Phone">Phone</label>
                    <div className="relative text-white-dark">
                        <input id="Phone" type="email" placeholder="Enter Phone Number" className="form-input ps-10 placeholder:text-white-dark" />
                        <span className="absolute start-4 top-1/2 -translate-y-1/2">
                            <IconPhone fill={true} />
                        </span>
                    </div>
                </div>
                <button type="submit" className="btn btn-gradient !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                    Sign in
                </button>
            </form>
        </AuthLayout>
    );
};

export default Login;
