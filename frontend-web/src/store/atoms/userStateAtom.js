import { atom } from 'recoil';
import { atomConstants } from './atomConstants';

export const userStateAtom = atom({
    key: atomConstants.userStateAtom,
    default: {
        isLoggedIn: false,
        token: null,
        name: null,
        phone: null,
        rukn_id: null,
        unit_name: null,
        district: null,
        halqa: null,
        gender: null,
    },
});
