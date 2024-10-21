import React from 'react';
import { Formik, Field, Form, ErrorMessage } from 'formik';
import * as Yup from 'yup';
import RegistrationLayout from '../layout/registrationLayout';
import { useRecoilValue } from 'recoil';
import { registrationDetailsAtom } from '../../../store/atoms/registrationDetailsAtom';
import { toast } from 'react-toastify';
import { updateFinancialDetails } from '../../../services/registration_service';
import { useNavigate } from 'react-router-dom';
import { ROUTES } from '../../../router/routes';

const FinancialRegistration = () => {
    const navigate = useNavigate();
    const user = useRecoilValue(registrationDetailsAtom);
    const familyDetails = user?.member_reg_data?.family_details || [];
    const totalFees = user?.member_reg_data?.member_fees || 0;
    const feesPaidToAmeer = user?.member_reg_data?.fees_paid_to_ameer;

    const validationSchema = Yup.object().shape({
        amountPaid: Yup.number().required('Amount paid is required').min(0, 'Amount paid cannot be negative'),
    });

    const initialValues = {
        amountPaid: feesPaidToAmeer !== null ? feesPaidToAmeer : 0,
    };

    const handleSubmit = async (values) => {
        console.log(`Amount Paid: ${values.amountPaid}`);
        try {
            await updateFinancialDetails(values.amountPaid);
            navigate(-1);

            toast.success('Registration Successful. You are all done');
        } catch (error) {
            toast.error('Something went wrong. Please come back later');
        }
    };

    return (
        <RegistrationLayout>
            <div className="container mx-auto my-8 rounded-lg">
                <div className="mb-4">
                    <h2 className="text-xl font-semibold mb-2">Delegate Fees</h2>
                    <ul className="list-disc list-inside">
                        <li className="flex justify-between">
                            <span>Individual Registration</span>
                            <span>₹{totalFees}</span>
                        </li>
                        {familyDetails.map((member, index) => (
                            <li key={index} className="flex justify-between">
                                <span>
                                    {member.name} ({member.type === 'mehram' ? 'Above 14' : 'Below 14'})
                                </span>
                                <span>₹{member.fees}</span>
                            </li>
                        ))}
                    </ul>
                    <div className="flex justify-between mt-2 font-bold">
                        <span>Total Delegate fees</span>
                        <span>₹{user.total_fees}</span>
                    </div>
                </div>
                <Formik initialValues={initialValues} validationSchema={validationSchema} onSubmit={handleSubmit} enableReinitialize>
                    {({ values, handleChange, isValid }) => (
                        <Form>
                            <div className="mb-4">
                                <label htmlFor="amountPaid" className="block text-sm font-medium text-gray-700">
                                    Pay above amount to your Ameer-e-Muqami or local office bearer and mention the amount here. If you have paid partially then please mention the amount paid so far
                                </label>
                                <Field
                                    type="number"
                                    id="amountPaid"
                                    name="amountPaid"
                                    value={values.amountPaid}
                                    onChange={handleChange}
                                    className="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                    required
                                />
                                <ErrorMessage name="amountPaid" component="div" className="text-red-500 text-sm mt-1" />
                                <div className="text-gray-600 text-sm mt-2">
                                    {values.amountPaid === 0 ? 'Please make sure to enter the amount paid to proceed.' : 'Thank you for entering the payment details.'}
                                </div>
                            </div>
                            <button
                                type="submit"
                                className={`w-full py-2 px-4 font-semibold rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-opacity-50 ${
                                    !isValid || values.amountPaid <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-primary-600 text-white hover:bg-primary-700'
                                }`}
                                disabled={!isValid || values.amountPaid <= 0}
                            >
                                {values.amountPaid <= 0 ? 'You need to pay some amount to complete this step' : 'Submit'}
                            </button>
                        </Form>
                    )}
                </Formik>
            </div>
        </RegistrationLayout>
    );
};

export default FinancialRegistration;
