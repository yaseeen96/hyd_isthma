import { useState } from 'react';
import RegistrationLayout from './layout/registrationLayout';
import useAsyncEffect from 'use-async-effect';
import { confirmRegistrationService, getUserDetails } from '../../services/registration_service';
import { toast } from 'react-toastify';
import { useNavigate } from 'react-router-dom';

const RegisterPage = () => {
    const navigate = useNavigate();
    const [loading, setLoading] = useState(false);
    const [user, setUser] = useState(null);
    const [userDetails, setUserDetails] = useState({
        confirmArrival: '1',
        reason_for_not_coming: '',
        emergency_contact: '',
        ameer_permission_taken: '1',
    });

    const handleSubmit = async (e) => {
        e.preventDefault();
        try {
            setLoading(true);
            const isSuccess = await confirmRegistrationService(userDetails);

            if (isSuccess) {
                localStorage.setItem('arrivalConfirmed', userDetails.confirmArrival);
                toast.success('Registration Successful');
                navigate('/home');
            }
        } catch (error) {
            toast.error('Registration Failed. Please come back later');
        } finally {
            setLoading(false);
        }
    };

    useAsyncEffect(async () => {
        const response = await getUserDetails();
        setUser(response);
    }, []);

    const onConfirmed = (e) => {
        // check type of e.target.value

        console.log(typeof e.target.value);
        setUserDetails({ ...userDetails, confirmArrival: e.target.value });
    };

    const calculateAge = (dob) => {
        if (!dob) return 0;
        const today = new Date();
        const birthDate = new Date(dob);
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        return age;
    };

    return (
        <RegistrationLayout>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="rukn-id" className="ml-1 w-1/3 mb-0">
                    Rukn ID
                </label>
                <input id="rukn-id" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Rukn ID" readOnly value={user ? user.data.user_number : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="full-name" className="ml-1 w-1/3 mb-0">
                    Name
                </label>
                <input id="full-name" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Name" readOnly value={user ? user.data.name : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="phone-number" className="ml-1 w-1/3 mb-0">
                    Phone Number
                </label>
                <input id="phone-number" type="tel" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter phone number" readOnly value={user ? user.data.phone : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="unit-name" className="ml-1 w-1/3 mb-0">
                    Unit Name
                </label>
                <input id="unit-name" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter unit Name" readOnly value={user ? user.data.unit_name : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="district" className="ml-1 w-1/3 mb-0">
                    District
                </label>
                <input id="district" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter District" readOnly value={user ? user.data.unit_name : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="halqa" className="ml-1 w-1/3 mb-0">
                    Halqa
                </label>
                <input id="halqa" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Halqa" readOnly value={user ? user.data.zone_name : ''} />
            </div>
            <div className="flex flex-col items-start mt-4 gap-2 w-full">
                <label htmlFor="gender" className="ltr:mr-2 rtl:ml-2 w-1/3 mb-0">
                    Gender
                </label>
                <input id="halqa" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Gender" readOnly value={user ? user.data.gender : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="Age" className="ml-1 w-1/3 mb-0">
                    dob
                </label>
                <input id="Age" type="number" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Age" value={calculateAge(user ? user.data.dob : null)} />
            </div>
            <div className="flex flex-col items-start mt-4 gap-2 w-full">
                <label htmlFor="confirmation" className="ltr:mr-2 rtl:ml-2 w-full mb-0">
                    Will you be able to attend the event?
                </label>
                <select id="confirmation" name="confirmation" className="form-select text-gray-300 " onChange={onConfirmed}>
                    <option value={1}>Yes</option>
                    <option value={0}>No</option>
                </select>
            </div>
            {userDetails.confirmArrival === '0' ? (
                <>
                    <div className="mt-4 flex flex-col items-start w-full gap-1">
                        <label htmlFor="reason-for-not-confirming" className="ml-1 w-full mb-0">
                            Reason for not coming?
                        </label>
                        <input
                            id="reason-for-not-confirming"
                            type="text"
                            name="reason_for_not_coming"
                            className="form-input "
                            placeholder="Enter reason"
                            onChange={(e) => setUserDetails((prev) => ({ ...prev, reason_for_not_coming: e.target.value }))}
                        />
                    </div>
                    <div className="flex flex-col items-start mt-4 gap-2 w-full">
                        <label htmlFor="ameers-permission-taken" className="ltr:mr-2 rtl:ml-2 w-full mb-0 text-gray-300">
                            Ameer's permission taken?
                        </label>
                        <select
                            id="ameers-permission-taken"
                            name="ameer_permission_taken"
                            className="form-select "
                            onChange={(e) => setUserDetails((prev) => ({ ...prev, ameer_permission_taken: e.target.value }))}
                        >
                            <option value={1}>Yes</option>
                            <option value={0}>No</option>
                        </select>
                    </div>
                    <div className="mt-4 flex flex-col items-start w-full gap-1">
                        <label htmlFor="emergency-contact" className="ml-1 w-full mb-0">
                            Emergency Contact Details
                        </label>
                        <input
                            id="emergency-contact"
                            type="number"
                            name="emergency_contact"
                            className="form-input "
                            placeholder="Enter contact details"
                            onChange={(e) => setUserDetails((prev) => ({ ...prev, emergency_contact: e.target.value }))}
                        />
                    </div>
                </>
            ) : null}

            <button className={`btn btn-primary mx-auto my-4 w-full`} onClick={handleSubmit} disabled={loading || userDetails.confirmArrival == null}>
                {loading ? 'Registering' : 'Register'}
            </button>
        </RegistrationLayout>
    );
};

export default RegisterPage;
