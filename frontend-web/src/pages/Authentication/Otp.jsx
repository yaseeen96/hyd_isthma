import AuthLayout from './Layout/AuthLayout';
import IconPhone from '../../components/Icon/IconPhone';
import { useNavigate } from 'react-router-dom';
import { verifyOtpService } from '../../services/login_service';
import { useRecoilState } from 'recoil';
import { userStateAtom } from '../../store/atoms/userStateAtom';
import { useState } from 'react';
import { toast } from 'react-toastify';
import { localStorageConstant } from '../../utils/constants/localStorageConstants';
import { ROUTES } from '../../router/routes';

import { isUserLoggedIn } from '../../services/check_token_validity_service';

const Otp = () => {
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
            toast.success('Your login is successful');
            localStorage.setItem(localStorageConstant.token, response.data.token);
            localStorage.setItem(localStorageConstant.name, response.data.user.name);
            const { user, isLoggedIn } = await isUserLoggedIn();
            if (isLoggedIn) {
                localStorage.setItem(localStorageConstant.name, user.name);
                localStorage.setItem(localStorageConstant.arrivalConfirmed, user.registration.confirm_arrival);
                localStorage.setItem(localStorageConstant.arrivalDetails, user.registration.arrival_dtls);
                localStorage.setItem(localStorageConstant.familyDetails, user.registration.family_dtls);
                localStorage.setItem(localStorageConstant.financialDetails, user.registration.financial_dtls);
            }
            navigate(ROUTES.home, { replace: true });
        } catch (error) {
            toast.error(`${error}`);
        } finally {
            setLoading(false);
        }
    }
    return (
        <AuthLayout title="Otp has been sent to your registered phone number" noBanner={true}>
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
