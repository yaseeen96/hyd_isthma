import { atom } from 'recoil';
import { atomConstants } from './atomConstants';

export const bottomBarIndex = atom({
    key: atomConstants.bottomBarIndex,
    default: 0,
});
