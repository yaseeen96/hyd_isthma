import React from 'react';
import { Formik, Field, Form, ErrorMessage, FieldArray } from 'formik';
import * as Yup from 'yup';
import RegistrationLayout from '../layout/registrationLayout';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';
import 'tailwindcss/tailwind.css';
import { updateAdditionalDetails } from '../../../services/registration_service';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import { useRecoilValue } from 'recoil';
import { registrationDetailsAtom } from '../../../store/atoms/registrationDetailsAtom';
import { useLoading } from '../../../utils/hooks/useLoading';
import LoadingComponent from '../../../components/common/loadingComponent';
import { ROUTES } from '../../../router/routes';
import { format } from 'date-fns';

const AdditionalDetailsRegistration = () => {
    const { loading, setLoading } = useLoading();
    const navigate = useNavigate();
    const registrationDetails = useRecoilValue(registrationDetailsAtom);

    const defaultItems = [
        { name: 'Mattress', qty: '0' },
        { name: 'Cot', qty: '0' },
        { name: 'Plate', qty: '0' },
        { name: 'Spoons', qty: '0' },
        { name: 'Carpet', qty: '0' },
    ];

    const mergePurchaseDetails = (existingDetails, defaultItems) => {
        const existingNames = existingDetails.map((item) => item.name);
        const mergedItems = [...existingDetails, ...defaultItems.filter((item) => !existingNames.includes(item.name))];
        return mergedItems;
    };

    const initialValues = {
        arrival_details: {
            datetime: registrationDetails.member_reg_data?.arrival_details?.datetime ? new Date(registrationDetails.member_reg_data.arrival_details.datetime) : null,
            mode: registrationDetails.member_reg_data?.arrival_details?.mode || '',
            mode_identifier: registrationDetails.member_reg_data?.arrival_details?.mode_identifier || '',
            start_point: registrationDetails.member_reg_data?.arrival_details?.start_point || '',
            end_point: registrationDetails.member_reg_data?.arrival_details?.end_point || '',
        },
        departure_details: {
            datetime: registrationDetails.member_reg_data?.departure_details?.datetime ? new Date(registrationDetails.member_reg_data.departure_details.datetime) : null,
            mode: registrationDetails.member_reg_data?.departure_details?.mode || '',
            mode_identifier: registrationDetails.member_reg_data?.departure_details?.mode_identifier || '',
            start_point: registrationDetails.member_reg_data?.departure_details?.start_point || '',
            end_point: registrationDetails.member_reg_data?.departure_details?.end_point || '',
        },
        hotel_required: registrationDetails.member_reg_data?.hotel_required || 'no',
        special_considerations: {
            food_preferences: registrationDetails.member_reg_data?.special_considerations?.food_preferences || '',
            need_attendant: registrationDetails.member_reg_data?.special_considerations?.need_attendant || '',
            cot_or_bed: registrationDetails.member_reg_data?.special_considerations?.cot_or_bed || '',
        },
        sight_seeing: {
            required: registrationDetails.member_reg_data?.sight_seeing?.required || 'no',
            members_count: registrationDetails.member_reg_data?.sight_seeing?.members_count || '',
        },
        health_concern: registrationDetails.member_reg_data?.health_concern || '',
        management_experience: registrationDetails.member_reg_data?.management_experience || 'no',
        purchases_required: mergePurchaseDetails(
            registrationDetails.member_reg_data?.purchase_details.length > 0
                ? registrationDetails.member_reg_data.purchase_details.map((item) => ({
                      name: item.type,
                      qty: item.qty || '0',
                  }))
                : [],
            defaultItems
        ),
        comments: registrationDetails.member_reg_data?.comments || '',
        year_of_rukniyat: registrationDetails.member_data[0]?.year_of_rukniyat || '',
    };

    const transportOptions = {
        BUS: [
            'Mg Bus Station, Imlibun, Gowliguda, Hyderabad (15 KM, 30 Mins from venue)',
            'Jubilee Bus Station, Gandhi Nagar (30 KM, 55 Mins from venue)',
            'L. B. Nagar, Hyderabad (16 KM, 30 Mins from venue)',
            'Aramghar Bus Stop (10 KM, 16 Mins from venue)',
            'Other',
        ],
        TRAIN: [
            'Secunderabad Railway Station (22 KM, 50 Mins from venue)',
            'Kacheguda Railway Station (15 KM, 35 Mins from venue)',
            'Lingampally Railway Station (37 KM, 50 Mins from venue)',
            'Hyderabad Deccan (Nampally) Railway Station (14 KM, 35 Mins from venue)',
            'Other',
        ],
        FLIGHT: ['Rajiv Gandhi International Airport, Shamshabad (11 KM, 20 Mins from venue)', 'Wadi e Huda'],
        SELF: ['Wadi e Huda', 'Other'],
    };

    const validationSchema = Yup.object().shape({
        hotel_required: Yup.string().required('Required'),
        sight_seeing: Yup.object().shape({
            required: Yup.string().required('Required'),
            members_count: Yup.number()
                .nullable()
                .when('required', {
                    is: 'yes',
                    then: (schema) => schema.required('Required').min(1, 'At least 1'),
                    otherwise: (schema) => schema.nullable(),
                }),
        }),
        management_experience: Yup.string().required('Required'),
        purchases_required: Yup.array().of(
            Yup.object().shape({
                name: Yup.string().required('Required'),
                qty: Yup.number().required('Required').min(0, 'Cannot be negative'),
            })
        ),
        year_of_rukniyat: Yup.number().required('Required').min(1900, 'Year must be greater than or equal to 1900').max(new Date().getFullYear(), 'Year cannot be in the future'),
    });

    const handleSubmit = async (values) => {
        // Format dates to 'YYYY-MM-DD' before logging the result
        const formattedValues = {
            ...values,
            arrival_details: {
                ...values.arrival_details,
                datetime: values.arrival_details.datetime instanceof Date ? format(values.arrival_details.datetime, 'yyyy-MM-dd hh:mm a') : '',
            },
            departure_details: {
                ...values.departure_details,
                datetime: values.departure_details.datetime instanceof Date ? format(values.departure_details.datetime, 'yyyy-MM-dd hh:mm a') : '',
            },
        };
        setLoading(true);
        const isSuccess = await updateAdditionalDetails(formattedValues);
        if (isSuccess) {
            navigate(-1);
            toast.success('Registration Successful. You are all done');
        } else {
            toast.error('Something seems to be wrong. Please come back later');
        }
        setLoading(false);
    };

    return loading ? (
        <LoadingComponent />
    ) : (
        <RegistrationLayout>
            <Formik initialValues={initialValues} validationSchema={validationSchema} onSubmit={handleSubmit}>
                {({ values, setFieldValue }) => (
                    <Form className="w-full space-y-6">
                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Arrival Details</h2>
                            <div className="mb-4">
                                <label>Exact Date and Time of Arrival</label>
                                <DatePicker
                                    wrapperClassName="w-full"
                                    selected={values.arrival_details.datetime ? new Date(values.arrival_details.datetime) : null}
                                    onChange={(date) => setFieldValue('arrival_details.datetime', date)}
                                    showTimeSelect
                                    timeIntervals={15} // Set time intervals to 15 minutes
                                    dateFormat="Pp"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="arrival_details.datetime" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Mode of Transport</label>
                                <Field
                                    name="arrival_details.mode"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                    onChange={(e) => {
                                        const { value } = e.target;
                                        setFieldValue('arrival_details.mode', value);
                                        setFieldValue('arrival_details.end_point', '');
                                    }}
                                >
                                    <option value="">Select</option>
                                    <option value="BUS">Bus</option>
                                    <option value="TRAIN">Train</option>
                                    <option value="FLIGHT">Flight</option>
                                    <option value="SELF">Own Car/Bike/Vehicle</option>
                                </Field>
                                <ErrorMessage name="arrival_details.mode" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Bus/Flight/Train/Own vehicle number</label>
                                <Field
                                    name="arrival_details.mode_identifier"
                                    type="text"
                                    placeholder="Enter bus or flight number"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="arrival_details.mode_identifier" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Station Start Point</label>
                                <Field
                                    name="arrival_details.start_point"
                                    type="text"
                                    placeholder="Start Point"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="arrival_details.start_point" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Station End Point</label>
                                <Field
                                    name="arrival_details.end_point"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                    disabled={!transportOptions[values.arrival_details.mode] || transportOptions[values.arrival_details.mode].length === 0}
                                >
                                    <option value="">Select</option>
                                    {transportOptions[values.arrival_details.mode]?.map((option, index) => (
                                        <option key={index} value={option}>
                                            {option}
                                        </option>
                                    ))}
                                </Field>
                                <ErrorMessage name="arrival_details.end_point" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                        </div>

                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Departure Details</h2>
                            <div className="w-full mb-4">
                                <label>Exact Date and Time of Departure</label>
                                <DatePicker
                                    wrapperClassName="w-full"
                                    selected={values.departure_details.datetime ? new Date(values.departure_details.datetime) : null}
                                    onChange={(date) => setFieldValue('departure_details.datetime', date)}
                                    showTimeSelect
                                    timeIntervals={15} // Set time intervals to 15 minutes
                                    dateFormat="Pp"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="departure_details.datetime" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Mode of Transport</label>
                                <Field
                                    name="departure_details.mode"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                    onChange={(e) => {
                                        const { value } = e.target;
                                        setFieldValue('departure_details.mode', value);
                                        setFieldValue('departure_details.start_point', '');
                                    }}
                                >
                                    <option value="">Select</option>
                                    <option value="BUS">Bus</option>
                                    <option value="TRAIN">Train</option>
                                    <option value="FLIGHT">Flight</option>
                                    <option value="SELF">Own Car/Bike/Vehicle</option>
                                </Field>
                                <ErrorMessage name="departure_details.mode" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Bus/Flight/Train/Own vehicle number</label>
                                <Field
                                    name="departure_details.mode_identifier"
                                    type="text"
                                    placeholder="Enter bus or flight number"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="departure_details.mode_identifier" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Station Start Point</label>
                                <Field
                                    name="departure_details.start_point"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                    disabled={!transportOptions[values.departure_details.mode] || transportOptions[values.departure_details.mode].length === 0}
                                >
                                    <option value="">Select</option>
                                    {transportOptions[values.departure_details.mode]?.map((option, index) => (
                                        <option key={index} value={option}>
                                            {option}
                                        </option>
                                    ))}
                                </Field>
                                <ErrorMessage name="departure_details.start_point" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Station End Point</label>
                                <Field
                                    name="departure_details.end_point"
                                    type="text"
                                    placeholder="End Point"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="departure_details.end_point" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                        </div>

                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Hotel & Special Considerations</h2>
                            <div className="w-full mb-4">
                                <label>Do you require a hotel?</label>
                                <Field
                                    name="hotel_required"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                >
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </Field>
                                <ErrorMessage name="hotel_required" component="div" className="text-red-500 text-sm mt-1" />
                            </div>

                            <div className="w-full mb-4">
                                <label>Special Considerations</label>
                                <Field
                                    name="special_considerations.food_preferences"
                                    type="text"
                                    placeholder="Food Preferences"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="special_considerations.food_preferences" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Do you need an attendant?</label>
                                <Field
                                    name="special_considerations.need_attendant"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                >
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </Field>
                                <ErrorMessage name="special_considerations.need_attendant" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>floor mattress or Cot?</label>
                                <Field
                                    name="special_considerations.cot_or_bed"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                >
                                    <option value="">Select</option>
                                    <option value="floor mattress">Floor Mattress</option>
                                    <option value="cot">🛏️Cot</option>
                                </Field>
                                <ErrorMessage name="special_considerations.cot_or_bed" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                        </div>

                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Sight Seeing</h2>
                            <div className="w-full mb-4">
                                <label>Do you require Sight Seeing?</label>
                                <Field
                                    name="sight_seeing.required"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                    onChange={(e) => {
                                        const { value } = e.target;
                                        setFieldValue('sight_seeing.required', value);
                                        if (value !== 'yes') {
                                            setFieldValue('sight_seeing.members_count', '');
                                        }
                                    }}
                                >
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </Field>
                                <ErrorMessage name="sight_seeing.required" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            {values.sight_seeing.required === 'yes' && (
                                <div className="w-full mb-4">
                                    <label>Number of Members for Sight Seeing</label>
                                    <Field
                                        name="sight_seeing.members_count"
                                        type="number"
                                        min="1"
                                        placeholder="Enter number of members"
                                        className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                    />
                                    <ErrorMessage name="sight_seeing.members_count" component="div" className="text-red-500 text-sm mt-1" />
                                </div>
                            )}
                        </div>

                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Are you suffering from any serious illness or weakness that we need to know to serve you better?</h2>
                            <div className="w-full mb-4">
                                <label>Health Concern</label>
                                <Field
                                    name="health_concern"
                                    type="text"
                                    placeholder="Any health concerns"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="health_concern" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                        </div>

                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Do you have past experience of involvement in management of any big Ijtema event?</h2>
                            <div className="w-full mb-4">
                                <Field
                                    name="management_experience"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                >
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </Field>
                                <ErrorMessage name="management_experience" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                        </div>

                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Would you like to purchase any of the below items at a discounted price after Ijtema?</h2>
                            <FieldArray
                                name="purchases_required"
                                render={(arrayHelpers) => (
                                    <div>
                                        {values.purchases_required.map((item, index) => (
                                            <div key={index} className="flex space-x-4 mb-4">
                                                <div className="flex-1">
                                                    <Field
                                                        name={`purchases_required.${index}.name`}
                                                        type="text"
                                                        readOnly
                                                        placeholder="Item Name"
                                                        className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                                    />
                                                    <ErrorMessage name={`purchases_required.${index}.name`} component="div" className="text-red-500 text-sm mt-1" />
                                                </div>
                                                <div className="flex-1">
                                                    <Field
                                                        name={`purchases_required.${index}.qty`}
                                                        type="number"
                                                        min="0"
                                                        placeholder="Quantity"
                                                        className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                                    />
                                                    <ErrorMessage name={`purchases_required.${index}.qty`} component="div" className="text-red-500 text-sm mt-1" />
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            />
                        </div>

                        <div className="w-full mb-4">
                            <label>Any suggestions for Ijtema management or program?</label>
                            <Field
                                name="comments"
                                as="textarea"
                                placeholder="Any additional comments"
                                className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                            />
                            <ErrorMessage name="comments" component="div" className="text-red-500 text-sm mt-1" />
                        </div>

                        <div className="w-full mb-4">
                            <label>Year of Rukniyat</label>
                            <Field
                                name="year_of_rukniyat"
                                type="number"
                                placeholder="Enter year"
                                className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                            />
                            <ErrorMessage name="year_of_rukniyat" component="div" className="text-red-500 text-sm mt-1" />
                        </div>

                        <button type="submit" className="px-6 py-3 btn-primary w-full rounded-lg">
                            Submit
                        </button>
                    </Form>
                )}
            </Formik>
        </RegistrationLayout>
    );
};

export default AdditionalDetailsRegistration;
