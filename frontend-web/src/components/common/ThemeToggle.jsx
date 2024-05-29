import { useDispatch, useSelector } from 'react-redux';
import { toggleTheme } from '../../store/themeConfigSlice';
import IconSun from '../Icon/IconSun';
import IconMoon from '../Icon/IconMoon';
import IconLaptop from '../Icon/IconLaptop';

const ThemeToggle = () => {
    const dispatch = useDispatch();
    const themeConfig = useSelector((state) => state.themeConfig);
    return (
        <div>
            {themeConfig.theme === 'light' ? (
                <button
                    className={`${
                        themeConfig.theme === 'light' && 'flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60'
                    }`}
                    onClick={() => {
                        dispatch(toggleTheme('dark'));
                    }}
                >
                    <IconSun />
                </button>
            ) : (
                ''
            )}
            {themeConfig.theme === 'dark' && (
                <button
                    className={`${
                        themeConfig.theme === 'dark' && 'flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60'
                    }`}
                    onClick={() => {
                        dispatch(toggleTheme('system'));
                    }}
                >
                    <IconMoon />
                </button>
            )}
            {themeConfig.theme === 'system' && (
                <button
                    className={`${
                        themeConfig.theme === 'system' && 'flex items-center p-2 rounded-full bg-white-light/40 dark:bg-dark/40 hover:text-primary hover:bg-white-light/90 dark:hover:bg-dark/60'
                    }`}
                    onClick={() => {
                        dispatch(toggleTheme('light'));
                    }}
                >
                    <IconLaptop />
                </button>
            )}
        </div>
    );
};

export default ThemeToggle;
