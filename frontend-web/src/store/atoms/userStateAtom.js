import { atom } from 'recoil';

export const userStateAtom = atom({
    key: 'userStateAtom',
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
