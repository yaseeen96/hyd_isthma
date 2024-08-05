import { useNavigate } from 'react-router-dom';
import IconPhone from '../../components/Icon/IconPhone';
import AuthLayout from './Layout/AuthLayout';
import { toast } from 'react-toastify';
import { sendOtpService } from '../../services/login_service';
import { useState } from 'react';
import { useSetRecoilState } from 'recoil';
import { userStateAtom } from '../../store/atoms/userStateAtom';
import { ROUTES } from '../../router/routes';

const Login = () => {
    const [loading, setLoading] = useState(false);
    const [phone, setPhone] = useState('');
    const navigate = useNavigate();
    const setUserState = useSetRecoilState(userStateAtom);

    const submitForm = async (e) => {
        e.preventDefault();
        try {
            setLoading(true);
            const response = await sendOtpService(phone);

            setUserState((prev) => ({ ...prev, phone: phone }));
            toast.success(response.message);
            navigate(ROUTES.verifyOtp);
        } catch (error) {
            toast.error(`${error}`);
        } finally {
            setLoading(false);
        }
    };

    return (
        <AuthLayout title="Enter your email or phone number to login">
            <form className="space-y-5 dark:text-white" onSubmit={submitForm}>
                <div className="mt-5">
                    <div className="relative text-white-dark">
                        <input
                            id="Phone"
                            type="text"
                            placeholder="Enter your email / phone number"
                            className="form-input ps-10 placeholder:text-white-dark"
                            onChange={(e) => {
                                setPhone(e.target.value);
                            }}
                        />
                        <span className="absolute start-4 top-1/2 -translate-y-1/2">
                            <IconPhone fill={true} />
                        </span>
                    </div>
                </div>
                <button type="submit" className="btn btn-primary !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]" onClick={submitForm} disabled={loading}>
                    {loading ? 'Sending OTP' : 'Sign in'}
                </button>
            </form>
        </AuthLayout>
    );
};

export default Login;
