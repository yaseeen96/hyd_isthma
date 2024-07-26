import { useState, useEffect } from 'react';
import RegistrationLayout from '../layout/registrationLayout';
import { confirmRegistrationService } from '../../../services/registration_service';
import { toast } from 'react-toastify';
import { useNavigate } from 'react-router-dom';
import Datepicker from 'react-tailwindcss-datepicker';
import { trackSelectContent } from '../../../utils/hooks/trackSelectContent';
import { useRecoilValue } from 'recoil';
import { registrationDetailsAtom } from '../../../store/atoms/registrationDetailsAtom';
import { useLoading } from '../../../utils/hooks/useLoading';
import LoadingComponent from '../../../components/common/loadingComponent';
import { localStorageConstant } from '../../../utils/constants/localStorageConstants';
import { analyticsState } from '../../../store/atoms/analyticsAtom';

const ArrivalRegistrationPage = () => {
    const user = useRecoilValue(registrationDetailsAtom);
    const navigate = useNavigate();
    const { loading, setLoading } = useLoading();
    const analytics = useRecoilValue(analyticsState);
    const [userDetails, setUserDetails] = useState({
        date_of_birth: { startDate: null, endDate: null },
        confirmArrival: '1',
        reason_for_not_coming: '',
        emergency_contact: '',
        ameer_permission_taken: '1',
        email: '',
    });

    useEffect(() => {
        if (user && user.member_data && user.member_data.length > 0) {
            const userData = user.member_data[0];
            setUserDetails({
                date_of_birth: { startDate: userData.dob, endDate: userData.dob },
                confirmArrival: userData.confirmArrival ?? '1',
                reason_for_not_coming: userData.reason_for_not_coming ?? '',
                emergency_contact: userData.emergency_contact ?? '',
                ameer_permission_taken: userData.ameer_permission_taken ?? '1',
                email: userData.email ?? '',
            });
        }
    }, [user]);

    const handleSubmit = async (e) => {
        e.preventDefault();

        // Validate required fields
        const requiredFields = ['date_of_birth.startDate', 'confirmArrival', 'emergency_contact', 'email'];
        if (userDetails.confirmArrival === '0') {
            requiredFields.push('reason_for_not_coming', 'ameer_permission_taken');
        }

        for (const field of requiredFields) {
            const fieldValue = field.split('.').reduce((obj, key) => obj && obj[key], userDetails);
            if (!fieldValue) {
                toast.error('Please fill all required fields.');
                return;
            }
        }

        try {
            setLoading(true);
            const isSuccess = await confirmRegistrationService(userDetails);

            if (isSuccess) {
                toast.success(
                    <div>
                        Thanks for completing the first phase of registration for Arkan Ijtema. Here are the next steps:
                        <br />
                        <ul>
                            <li>We will soon provide program details, informative videos, and other important information.</li>
                            <li>We will collect your arrival dates and interests to better serve you.</li>
                            <li>Stay tuned for updates.</li>
                        </ul>
                    </div>,
                    {
                        autoClose: false,
                        hideProgressBar: false,
                    }
                );
                navigate(-1);
                if (analytics) {
                    trackSelectContent(analytics, 'button', 'register-ijtema', 'Register for Ijtema');
                }
            }
        } catch (error) {
            toast.error(`Registration Failed. Please come back later ${error}`);
        } finally {
            setLoading(false);
        }
    };

    const onConfirmed = (e) => {
        setUserDetails({ ...userDetails, confirmArrival: e.target.value });
    };

    const onReasonChange = (e) => {
        setUserDetails({ ...userDetails, reason_for_not_coming: e.target.value });
    };

    if (loading) {
        return <LoadingComponent />;
    }

    return (
        <RegistrationLayout>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="rukn-id" className="ml-1 w-1/3 mb-0">
                    Rukn ID
                </label>
                <input
                    id="rukn-id"
                    type="text"
                    name="reciever-name"
                    className="form-input text-gray-400 "
                    placeholder="Enter Rukn ID"
                    readOnly
                    defaultValue={user ? user.member_data[0].user_number : ''}
                />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="full-name" className="ml-1 w-1/3 mb-0">
                    Name
                </label>
                <input id="full-name" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Name" readOnly defaultValue={user ? user.member_data[0].name : ''} />
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
                    defaultValue={user ? user.member_data[0].phone : ''}
                />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="unit-name" className="ml-1 w-1/3 mb-0">
                    Unit Name
                </label>
                <input
                    id="unit-name"
                    type="text"
                    name="reciever-name"
                    className="form-input text-gray-400 "
                    placeholder="Enter unit Name"
                    readOnly
                    defaultValue={user ? user.member_data[0].unit_name : ''}
                />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="district" className="ml-1 w-1/3 mb-0">
                    District
                </label>
                <input
                    id="district"
                    type="text"
                    name="reciever-name"
                    className="form-input text-gray-400 "
                    placeholder="Enter District"
                    readOnly
                    defaultValue={user ? user.member_data[0].unit_name : ''}
                />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="halqa" className="ml-1 w-1/3 mb-0">
                    Halqa
                </label>
                <input id="halqa" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Halqa" readOnly defaultValue={user ? user.member_data[0].zone_name : ''} />
            </div>
            <div className="flex flex-col items-start mt-4 gap-2 w-full">
                <label htmlFor="gender" className="ltr:mr-2 rtl:ml-2 w-1/3 mb-0">
                    Gender
                </label>
                <input id="halqa" type="text" name="reciever-name" className="form-input text-gray-400 " placeholder="Enter Gender" readOnly defaultValue={user ? user.member_data[0].gender : ''} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="dob" className="ml-1 w-1/3 mb-0">
                    Date of Birth
                </label>
                <Datepicker asSingle={true} useRange={false} value={userDetails.date_of_birth} onChange={(value) => setUserDetails((prevDetails) => ({ ...prevDetails, date_of_birth: value }))} />
            </div>
            <div className="mt-4 flex flex-col items-start w-full gap-1">
                <label htmlFor="email" className="ml-1 w-1/3 mb-0">
                    Email
                </label>
                <input
                    id="email"
                    type="email"
                    name="reciever-name"
                    className="form-input "
                    placeholder="Enter Email"
                    value={userDetails.email}
                    onChange={(e) => setUserDetails((prev) => ({ ...prev, email: e.target.value }))}
                />
            </div>
            <div className="flex flex-col items-start mt-4 gap-2 w-full">
                <label htmlFor="confirmation" className="ltr:mr-2 rtl:ml-2 w-full mb-0">
                    Please confirm you are going to attend the event?
                </label>
                <select value={userDetails.confirmArrival} id="confirmation" name="confirmation" className="form-select " onChange={onConfirmed}>
                    <option value={'1'}>Yes</option>
                    <option value={'0'}>No</option>
                </select>
            </div>
            {userDetails.confirmArrival === '0' ? (
                <>
                    <div className="mt-4 flex flex-col items-start w-full gap-1">
                        <label htmlFor="reason-for-not-confirming" className="ml-1 w-full mb-0">
                            What is the reason for not attending?
                        </label>
                        <select id="reason_for_not_coming" name="reason_for_not_coming" className="form-select " onChange={onReasonChange} value={userDetails.reason_for_not_coming}>
                            <option value={''}>Choose Option</option>
                            <option value={'Health problem'}>Health problem</option>
                            <option value={'Emergency'}>Emergency</option>
                            <option value={'Financial problem'}>Financial problem</option>
                            <option value={'No reason'}>No reason</option>
                        </select>
                    </div>
                    <div className="flex flex-col items-start mt-4 gap-2 w-full">
                        <label htmlFor="ameers-permission-taken" className="ltr:mr-2 rtl:ml-2 w-full mb-0">
                            Did you seek Permission from your Ameer-e-Muqami/Ameer-e-Halqa?
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

export default ArrivalRegistrationPage;
