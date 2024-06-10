import AuthLayout from './Layout/AuthLayout';
import IconPhone from '../../components/Icon/IconPhone';

import { useNavigate } from 'react-router-dom';
import { useDarkMode } from '../../utils/hooks/useDarkMode';
import { verifyOtpService } from '../../services/login_service';
import { useRecoilState, useSetRecoilState } from 'recoil';
import { userStateAtom } from '../../store/atoms/userStateAtom';
import { useState } from 'react';
import { toast } from 'react-toastify';

const Otp = () => {
    const isDarkMode = useDarkMode();
    console.log('isDarkMode in Otp:', isDarkMode);
    const navigate = useNavigate();
    const [otp, setOtp] = useState('');
    const [loading, setLoading] = useState(false);
    const [userState, setUserState] = useRecoilState(userStateAtom);

    async function submitForm(e) {
        e.preventDefault();
        try {
            setLoading(true);
            const response = await verifyOtpService(userState.phone, otp);
            setUserState((prev) => ({ ...prev, token: response.data.token, name: response.data.name, halqa: response.data.halqa }));
            toast.success("Yayyyy! You're logged in");
            localStorage.setItem('token', response.data.token);
            localStorage.setItem('name', response.data.user.name);
            navigate('/home');
        } catch (error) {
            toast.error(`${error}`);
        } finally {
            setLoading(false);
        }
    }
    return (
        <AuthLayout title="Otp has been sent to your registered phone number">
            <form className="space-y-5 dark:text-white" onSubmit={submitForm}>
                <div>
                    <div className="relative text-white-dark">
                        <input
                            id="OTP"
                            type="number"
                            placeholder="Enter your OTP"
                            className="form-input ps-10 placeholder:text-white-dark"
                            onChange={(e) => setOtp(e.target.value)}
                            disabled={loading}
                        />
                        <span className="absolute start-4 top-1/2 -translate-y-1/2">
                            <IconPhone fill={true} />
                        </span>
                    </div>
                </div>
                <button type="submit" className="btn btn-primary !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                    {loading ? 'Signing In' : 'Verify'}
                </button>
            </form>
        </AuthLayout>
    );
};

export default Otp;
