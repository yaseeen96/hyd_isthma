import BottomBar from '../components/bottomBar';
import StickyNavBar from '../components/navBar';

const HomeLayout = ({ children }) => {
    return (
        <div className="relative min-h-screen flex flex-col">
            <StickyNavBar />
            <div className="flex-grow flex flex-col items-start py-10 px-7 dark:bg-black bg-gray-50 pb-16">{children}</div>
            <BottomBar />
        </div>
    );
};

export default HomeLayout;
