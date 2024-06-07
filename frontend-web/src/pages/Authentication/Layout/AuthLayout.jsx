import { Link, useNavigate } from 'react-router-dom';
import ThemeToggle from '../../../components/common/ThemeToggle';
import { useDarkMode } from '../../../utils/hooks/use_dark_mode';

const AuthLayout = ({ children, title }) => {
    const isDark = useDarkMode();
    const navigate = useNavigate();
    return (
        <div>
            <div className="absolute inset-0">
                <img src="/assets/images/auth/bg-gradient.png" alt="image" className="h-full w-full object-cover" />
            </div>
            <div className="relative flex min-h-screen items-center justify-center bg-[url(/assets/images/auth/map.png)] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
                <img src="/assets/images/auth/coming-soon-object1.png" alt="image" className="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2" />
                <img src="/assets/images/auth/coming-soon-object2.png" alt="image" className="absolute left-24 top-0 h-40 md:left-[30%]" />
                <img src="/assets/images/auth/coming-soon-object3.png" alt="image" className="absolute right-0 top-0 h-[300px]" />
                <img src="/assets/images/auth/polygon-object.svg" alt="image" className="absolute bottom-0 end-[28%]" />
                <div className="relative w-full max-w-[870px] rounded-md bg-[linear-gradient(45deg,#f2e6ff_0%,rgba(255,255,255,0)_25%,rgba(255,255,255,0)_75%,_#f2e6ff_100%)] p-2 dark:bg-[linear-gradient(52.22deg,#0E1726_0%,rgba(14,23,38,0)_18.66%,rgba(14,23,38,0)_51.04%,rgba(14,23,38,0)_80.07%,#0E1726_100%)]">
                    <div className="relative flex flex-col justify-center rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 px-6 lg:min-h-[500px] py-20">
                        <div className='flex justify-center'>
                            <img src=  { isDark ?  "assets/images/auth/logo_web_black.png" : "assets/images/auth/logo_web_white.png"} alt="" />
                        </div>
                        <div className="mx-auto w-full max-w-[440px]">
                            <div className="float-right">
                                <ThemeToggle />
                            </div>

                            <div className="mb-10">
                                <h1 className="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">Sign in</h1>
                                <p className="text-base font-bold leading-normal text-white-dark">{title}</p>
                            </div>
                            {children}
                            <div className="relative my-7 text-center md:mb-9">
                                <span className="absolute inset-x-0 top-1/2 h-px w-full -translate-y-1/2 bg-white-light dark:bg-white-dark"></span>
                                <span className="relative bg-white px-2 font-bold uppercase text-white-dark dark:bg-dark dark:text-white-light">or</span>
                            </div>

                            <div className="text-center dark:text-white">
                                Not able to login ? &nbsp;
                                <Link to="tel:+917349629157" className="text-primary underline transition hover:text-black dark:hover:text-white">
                                    Call 7349629157
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AuthLayout;
