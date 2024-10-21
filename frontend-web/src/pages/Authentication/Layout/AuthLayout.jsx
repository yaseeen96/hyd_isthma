import { Link, useNavigate } from 'react-router-dom';
import { useDarkMode } from '../../../utils/hooks/useDarkMode';

const AuthLayout = ({ children, title, noBanner }) => {
    const isDarkMode = useDarkMode();
    return (
        <div>
            <div className="relative flex h-screen flex-col items-center justify-center bg-[url(/assets/images/auth/map.png)] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
                {/* <img src="/assets/images/auth/polygon-object.svg" alt="image" className="absolute bottom-0 end-[28%]" /> */}
                {noBanner ? null : <img src="assets/images/auth/cover.png" className="w-56 mx-auto mb-2" alt="" />}
                <div className="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#f2e6ff_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#f2e6ff_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
                    <div className="relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[500px] py-10">
                        <div className="flex justify-center mb-8">
                            <img src={isDarkMode ? 'assets/images/auth/logo_web_white.png' : 'assets/images/auth/logo_web_black.png'} alt="" />
                        </div>
                        <div className="mx-auto w-full max-w-[440px]">{children}</div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AuthLayout;
