import BottomBar from '../components/bottomBar';
import StickyNavBar from '../components/navBar';

const HomeLayout = ({ children }) => {
    return (
        <div className="relative min-h-screen flex flex-col w-screen">
            {/* Sticky Navigation Bar (Fixed at the top) */}
            <StickyNavBar />
            {/* Content Area with bottom padding to avoid overlap with BottomBar */}
            {/* add pb-52 when children is filled */}
            <div className="flex-grow overflow-y-auto pt-5 px-7 mb-16  dark:bg-black bg-gray-50">{children}</div>
            {/* Bottom Bar (Fixed at the bottom) */}
            {/* <div className="fixed bottom-0 left-0 z-50 w-full h-16 bg-white dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600"> */}
            <BottomBar />
            {/* </div> */}
        </div>
    );
};

export default HomeLayout;
