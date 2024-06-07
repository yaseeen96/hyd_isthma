import { useDispatch } from 'react-redux';
import { setPageTitle } from '../../store/themeConfigSlice';
import { useEffect } from 'react';
import AuthLayout from './Layout/AuthLayout';
import IconPhone from '../../components/Icon/IconPhone';

const Otp = () => {
    const dispatch = useDispatch();

    useEffect(() => {
        dispatch(setPageTitle('Login'));
    });
    function submitForm() {}
    return (
        <AuthLayout title="Otp has been sent to your registered phone number">
            <form className="space-y-5 dark:text-white" onSubmit={submitForm}>
                <div>
                    <label htmlFor="OTP">OTP</label>
                    <div className="relative text-white-dark">
                        <input id="OTP" type="number" placeholder="Enter your otp" className="form-input ps-10 placeholder:text-white-dark" />
                        <span className="absolute start-4 top-1/2 -translate-y-1/2">
                            <IconPhone fill={true} />
                        </span>
                    </div>
                </div>
                <button type="submit" className="btn btn-primary !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                    Verify
                </button>
            </form>
        </AuthLayout>
    );
};

export default Otp;
