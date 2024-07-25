import { atom } from 'recoil';
import { atomConstants } from './atomConstants';

export const analyticsState = atom({
    key: atomConstants.analyticsState,
    default: null,
});
