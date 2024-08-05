import { atom } from 'recoil';
import { atomConstants } from './atomConstants';

export const registrationDetailsAtom = atom({
    key: atomConstants.registrationDetails,
    default: {
        member_data: [
            {
                id: null,
                name: null,
                email: null,
                phone: null,
                user_number: null,
                unit_name: null,
                zone_name: null,
                division_name: null,
                dob: null,
                gender: null,
                status: null,
                push_token: null,
                created_at: null,
                updated_at: null,
            },
        ],
        member_reg_data: [
            {
                id: null,
                member_id: null,
                confirm_arrival: null,
                reason_for_not_coming: null,
                ameer_permission_taken: null,
                emergency_contact: null,
                created_at: null,
                updated_at: null,
                member_fees: null,
                arrival_details: null,
                departure_details: null,
                hotel_required: null,
                special_considerations: null,
                sight_seeing: null,
                health_concern: null,
                management_experience: null,
                comments: null,
                family_details: [],
                purchase_details: [],
                year_of_rukniyat: null,
            },
        ],
    },
});
