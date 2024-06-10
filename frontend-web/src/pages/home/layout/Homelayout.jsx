import BottomBar from '../components/bottomBar';
import StickyNavBar from '../components/navBar';

const HomeLayout = ({ children }) => {
    return (
        <div>
            <StickyNavBar />
            <div className="flex flex-col min-h-[80vh] items-start py-10 px-7 dark:bg-black bg-gray-50">{children}</div>
            <BottomBar />
        </div>
    );
};

export default HomeLayout;
