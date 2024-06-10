import React from 'react';
import HomePage from './subpages/Homepage';
import SupportPage from './subpages/Supportpage';
import { useRecoilValue } from 'recoil';
import { bottomBarIndex } from '../../store/atoms/activeBottomNavBarAtom';
const HomeWrapper = () => {
    const activeIndex = useRecoilValue(bottomBarIndex);
    return <div>{activeIndex == 0 ? <HomePage /> : <SupportPage />}</div>;
};

export default HomeWrapper;
