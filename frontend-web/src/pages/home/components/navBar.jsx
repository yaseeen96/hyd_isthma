import { useRef, useState, useEffect } from 'react';
import { CgProfile } from 'react-icons/cg';
import { useDarkMode } from '../../../utils/hooks/useDarkMode';

const StickyNavBar = () => {
    const isDarkMode = useDarkMode();
    const navbarRef = useRef(null);
    const [isSticky, setIsSticky] = useState(false);

    useEffect(() => {
        const observer = new IntersectionObserver(
            ([entry]) => {
                setIsSticky(entry.intersectionRatio < 1);
            },
            { threshold: [1] }
        );

        if (navbarRef.current) {
            observer.observe(navbarRef.current);
        } else {
        }

        return () => {
            if (navbarRef.current) {
                observer.unobserve(navbarRef.current);
            }
        };
    }, []);

    useEffect(() => {}, [isSticky]);

    return (
        <div ref={navbarRef} className={`sticky-navbar bg-gray-50 dark:bg-black  w-screen min-h-16 max-h-32 pt-8 sticky top-[-1px] ${isSticky ? 'backdrop-blur-md' : ''}`}>
            <div className={`mt-4 flex flex-row items-center ${isSticky ? 'justify-center' : 'justify-between'} h-full px-7 py-2`}>
                {isSticky ? (
                    <div className="flex justify-center items-center mt-4">
                        <img src="assets/images/auth/logo_web_black.png" alt="" />
                    </div>
                ) : (
                    <>
                        <img src={isDarkMode ? 'assets/images/auth/logo_app_white.png' : 'assets/images/auth/logo_app_black.png'} alt="Logo" />
                        <button>
                            <CgProfile
                                size={35}
                                style={{
                                    color: isSticky ? '#FF0000' : '#8635BD', // Change color when sticky
                                }}
                            />
                        </button>
                    </>
                )}
            </div>
        </div>
    );
};

export default StickyNavBar;
