import { useState, useEffect } from 'react';
import RegistrationLayout from './layout/registrationLayout';
import { confirmRegistrationService, getUserDetails } from '../../services/registration_service';
import { toast } from 'react-toastify';
import { useNavigate } from 'react-router-dom';
import Datepicker from 'react-tailwindcss-datepicker';

const RegisterPage = () => {
    const navigate = useNavigate();
    const [loading, setLoading] = useState(true);
    const [user, setUser] = useState(null);
    const [userDetails, setUserDetails] = useState({
        date_of_birth: { startDate: null, endDate: null },
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

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await getUserDetails();
                if (response && response.data && response.data.length > 0) {
                    setUser(response);

                    if (response.data[0].dob != null) {
                        setUserDetails((prevDetails) => ({
                            ...prevDetails,
                            date_of_birth: { startDate: response.data[0].dob, endDate: response.data[0].dob },
                        }));
                    }
                    if (response.data[0].registration != null) {
                        setUserDetails((prevDetails) => ({
                            ...prevDetails,
                            confirmArrival: response.data[0].registration.confirm_arrival?.toString() ?? '0',
                            reason_for_not_coming: response.data[0].registration.reason_for_not_coming ?? '',
                            emergency_contact: response.data[0].registration.emergency_contact ?? '',
                            ameer_permission_taken: response.data[0].registration.ameer_permission_taken?.toString() ?? '0',
                        }));
                    }
                }
            } catch (error) {
                console.error('Failed to fetch user details', error);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    const onConfirmed = (e) => {
        setUserDetails({ ...userDetails, confirmArrival: e.target.value });
    };

    const onReasonChange = (e) => {
        setUserDetails({ ...userDetails, reason_for_not_coming: e.target.value });
    };

    if (loading) {
        return <div className="flex h-screen justify-center items-center">Loading...</div>; // or any other loading indicator you prefer
    }

    return (
        <RegistrationLayout>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="rukn-id" className="ml-1 w-1/3 mb-0">
                    Rukn ID
                </label>
                <input id="rukn-id" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Rukn ID" readOnly defaultValue={user ? user.data[0].user_number : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="full-name" className="ml-1 w-1/3 mb-0">
                    Name
                </label>
                <input id="full-name" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Name" readOnly defaultValue={user ? user.data[0].name : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="phone-number" className="ml-1 w-1/3 mb-0">
                    Phone Number
                </label>
                <input
                    id="phone-number"
                    type="tel"
                    name="reciever-name"
                    className="form-input text-gray-400 "
                    placeholder="Enter phone number"
                    readOnly
                    defaultValue={user ? user.data[0].phone : ''}
                />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="unit-name" className="ml-1 w-1/3 mb-0">
                    Unit Name
                </label>
                <input id="unit-name" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter unit Name" readOnly defaultValue={user ? user.data[0].unit_name : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="district" className="ml-1 w-1/3 mb-0">
                    District
                </label>
                <input id="district" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter District" readOnly defaultValue={user ? user.data[0].unit_name : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="halqa" className="ml-1 w-1/3 mb-0">
                    Halqa
                </label>
                <input id="halqa" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Halqa" readOnly defaultValue={user ? user.data[0].zone_name : ''} />
            </div>
            <div className="flex flex-col items-start mt-4 gap-2 w-full">
                <label htmlFor="gender" className="ltr:mr-2 rtl:ml-2 w-1/3 mb-0">
                    Gender
                </label>
                <input id="halqa" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Gender" readOnly defaultValue={user ? user.data[0].gender : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="dob" className="ml-1 w-1/3 mb-0">
                    Date of Birth
                </label>

                <Datepicker
                    asSingle={true}
                    useRange={false}
                    value={userDetails.date_of_birth}
                    onChange={(value) => {
                        console.log(value);
                        setUserDetails((prevDetails) => ({ ...prevDetails, date_of_birth: value }));
                    }}
                />
            </div>
            <div className="flex flex-col items-start mt-4 gap-2 w-full">
                <label htmlFor="confirmation" className="ltr:mr-2 rtl:ml-2 w-full mb-0">
                    Will you be able to attend the event?
                </label>
                <select value={userDetails.confirmArrival} id="confirmation" name="confirmation" className="form-select text-gray-300 " onChange={onConfirmed}>
                    <option value={''}>Choose Option</option>
                    <option value={'1'}>Yes</option>
                    <option value={'0'}>No</option>
                </select>
            </div>
            {userDetails.confirmArrival === '0' ? (
                <>
                    <div className="mt-4 flex flex-col items-start w-full gap-1">
                        <label htmlFor="reason-for-not-confirming" className="ml-1 w-full mb-0">
                            Reason for not coming?
                        </label>
                        <select id="reason_for_not_coming" name="reason_for_not_coming" className="form-select text-gray-300 " onChange={onReasonChange} value={userDetails.reason_for_not_coming}>
                            <option value={''}>Choose Option</option>
                            <option value={'Health problem'}>Health problem</option>
                            <option value={'Emergency'}>Emergency</option>
                            <option value={'Financial problem'}>Financial problem</option>
                            <option value={'No reason'}>No reason</option>
                        </select>
                    </div>
                    <div className="flex flex-col items-start mt-4 gap-2 w-full">
                        <label htmlFor="ameers-permission-taken" className="ltr:mr-2 rtl:ml-2 w-full mb-0 text-gray-300">
                            Ameer's permission taken?
                        </label>
                        <select
                            value={userDetails.ameer_permission_taken}
                            id="ameers-permission-taken"
                            name="ameer_permission_taken"
                            className="form-select "
                            onChange={(e) => setUserDetails((prev) => ({ ...prev, ameer_permission_taken: e.target.value }))}
                        >
                            <option value={'1'}>Yes</option>
                            <option value={'0'}>No</option>
                        </select>
                    </div>
                </>
            ) : null}
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="emergency-contact" className="ml-1 w-full mb-0">
                    Emergency Contact Details
                </label>
                <input
                    value={userDetails.emergency_contact}
                    id="emergency-contact"
                    type="number"
                    name="emergency_contact"
                    className="form-input "
                    placeholder="Enter contact details"
                    onChange={(e) => setUserDetails((prev) => ({ ...prev, emergency_contact: e.target.value }))}
                />
            </div>

            <button className={`btn btn-primary mx-auto my-4 w-full`} onClick={handleSubmit} disabled={loading || userDetails.confirmArrival == null}>
                {loading ? 'Registering' : 'Register'}
            </button>
        </RegistrationLayout>
    );
};

export default RegisterPage;
