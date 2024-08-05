import { MdHomeFilled } from 'react-icons/md';
import { BiSupport } from 'react-icons/bi';
import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useRecoilState } from 'recoil';
import { bottomBarIndex } from '../../../store/atoms/activeBottomNavBarAtom';

const BottomBar = () => {
    const [activeIndex, setActiveIndex] = useRecoilState(bottomBarIndex);

    const onSupportPress = () => {
        setActiveIndex(1);
    };

    const onHomePress = () => {
        setActiveIndex(0);
    };
    return (
        <div className="fixed bottom-6 left-0 z-50 w-full h-16 border-gray-200  bg-gray-50 dark:bg-black border-t-[1px] dark:border-gray-600  ">
            <div className="grid h-full max-w-lg grid-cols-2 mx-auto font-medium">
                <button type="button" className="inline-flex flex-col items-center justify-center px-5  " onClick={onHomePress}>
                    <MdHomeFilled size={28} className="text-primary" style={activeIndex === 0 ? { color: '#8635BD' } : { color: 'gray' }} />
                    <span
                        className={`text-[0.7rem] text-gray-500 dark:text-gray-400  group-hover:text-primary-600 dark:group-hover:text-primary-500 ${
                            activeIndex === 0 ? 'text-primary dark:text-primary-500' : 'text-gray-300'
                        } `}
                    >
                        Home
                    </span>
                </button>
                <button type="button" className="inline-flex flex-col items-center justify-center px-5  " onClick={onSupportPress}>
                    <BiSupport size={28} style={activeIndex === 1 ? { color: '#8635BD' } : { color: 'gray' }} />
                    <span
                        className={`text-[0.7rem] text-gray-500 dark:text-gray-400 group-hover:text-primary-600 dark:group-hover:text-primary-500 ${
                            activeIndex === 1 ? 'text-primary dark:text-primary-500' : 'text-gray-300'
                        } `}
                    >
                        Support
                    </span>
                </button>
            </div>
        </div>
    );
};

export default BottomBar;
