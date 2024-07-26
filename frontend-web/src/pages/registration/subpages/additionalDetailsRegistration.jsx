import React from 'react';
import { Formik, Field, Form, ErrorMessage, FieldArray } from 'formik';
import * as Yup from 'yup';
import RegistrationLayout from '../layout/registrationLayout';
import Datepicker from 'react-tailwindcss-datepicker';

const AdditionalDetailsRegistration = () => {
    const initialValues = {
        arrival_details: {
            datetime: '',
            mode: '',
            mode_identifier: '',
            start_point: '',
            end_point: '',
        },
        departure_details: {
            datetime: '',
            mode: '',
            mode_identifier: '',
            start_point: '',
            end_point: '',
        },
        hotel_required: 'no',
        special_considerations: {
            food_preferences: '',
            need_attendant: '',
            cot_or_bed: '',
        },
        sight_seeing: {
            required: 'no',
            members_count: '',
        },
        health_concern: '',
        management_experience: 'no',
        purchases_required: [
            { name: 'Bed', qty: '' },
            { name: 'Cot', qty: '' },
            { name: 'Plate', qty: '' },
            { name: 'Spoons', qty: '' },
            { name: 'Carpet', qty: '' },
        ],
        comments: '',
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
    });

    const handleSubmit = (values) => {
        // Log the result in JSON format
        console.log(JSON.stringify(values, null, 2));
    };

    return (
        <RegistrationLayout>
            <Formik initialValues={initialValues} validationSchema={validationSchema} onSubmit={handleSubmit}>
                {({ values, setFieldValue }) => (
                    <Form className="w-full space-y-6">
                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Arrival Details</h2>
                            <div className="w-full mb-4">
                                <label>Exact Date and Time of Arrival</label>
                                <Datepicker
                                    asSingle={true}
                                    useRange={false}
                                    value={values.arrival_details.datetime}
                                    onChange={(value) => setFieldValue('arrival_details.datetime', value)}
                                    displayFormat="YYYY-MM-DD HH:mm:ss"
                                    inputClassName="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="arrival_details.datetime" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Mode of Transport</label>
                                <Field
                                    name="arrival_details.mode"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                >
                                    <option value="">Select</option>
                                    <option value="Air">Air</option>
                                    <option value="Government RTC Bus">Government RTC Bus</option>
                                    <option value="Train">Train</option>
                                    <option value="Private bus hired by your Jamat">Private bus hired by your Jamat</option>
                                    <option value="Own car">Own car</option>
                                    <option value="Own bike">Own bike</option>
                                    <option value="Any other own mode of transport">Any other own mode of transport</option>
                                </Field>
                                <ErrorMessage name="arrival_details.mode" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Flight Number/Train Number etc.</label>
                                <Field
                                    name="arrival_details.mode_identifier"
                                    type="text"
                                    placeholder="Enter Flight/Train Number"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="arrival_details.mode_identifier" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Station Start Point and End Point</label>
                                <Field
                                    name="arrival_details.start_point"
                                    type="text"
                                    placeholder="Start Point"
                                    className="w-full mb-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <Field
                                    name="arrival_details.end_point"
                                    type="text"
                                    placeholder="End Point"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="arrival_details.start_point" component="div" className="text-red-500 text-sm mt-1" />
                                <ErrorMessage name="arrival_details.end_point" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                        </div>

                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Departure Details</h2>
                            <div className="w-full mb-4">
                                <label>Exact Date and Time of Departure</label>
                                <Datepicker
                                    asSingle={true}
                                    useRange={false}
                                    value={values.departure_details.datetime}
                                    onChange={(value) => setFieldValue('departure_details.datetime', value)}
                                    displayFormat="YYYY-MM-DD HH:mm:ss"
                                    inputClassName="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="departure_details.datetime" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Mode of Transport</label>
                                <Field
                                    name="departure_details.mode"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                >
                                    <option value="">Select</option>
                                    <option value="Air">Air</option>
                                    <option value="Government RTC Bus">Government RTC Bus</option>
                                    <option value="Train">Train</option>
                                    <option value="Private bus hired by your Jamat">Private bus hired by your Jamat</option>
                                    <option value="Own car">Own car</option>
                                    <option value="Own bike">Own bike</option>
                                    <option value="Any other own mode of transport">Any other own mode of transport</option>
                                </Field>
                                <ErrorMessage name="departure_details.mode" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Flight Number/Train Number etc.</label>
                                <Field
                                    name="departure_details.mode_identifier"
                                    type="text"
                                    placeholder="Enter Flight/Train Number"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
                                <ErrorMessage name="departure_details.mode_identifier" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
                            <div className="w-full mb-4">
                                <label>Station Start Point and End Point</label>
                                <Field
                                    name="departure_details.start_point"
                                    type="text"
                                    placeholder="Start Point"
                                    className="w-full mb-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                />
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
                                <label>Bed or Cot?</label>
                                <Field
                                    name="special_considerations.cot_or_bed"
                                    as="select"
                                    className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                                >
                                    <option value="">Select</option>
                                    <option value="bed">Bed</option>
                                    <option value="cot">Cot</option>
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
                                >
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </Field>
                                <ErrorMessage name="sight_seeing.required" component="div" className="text-red-500 text-sm mt-1" />
                            </div>
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
                        </div>

                        <div className="border-b border-gray-300 pb-4">
                            <h2 className="text-lg font-semibold mb-2">Health Concern</h2>
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
                            <h2 className="text-lg font-semibold mb-2">Management Experience</h2>
                            <div className="w-full mb-4">
                                <label>Management Experience</label>
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
                            <h2 className="text-lg font-semibold mb-2">Purchases Required</h2>
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
                                                <button type="button" onClick={() => arrayHelpers.remove(index)} className="px-4 py-2 bg-red-500 text-white rounded-md">
                                                    Remove
                                                </button>
                                            </div>
                                        ))}
                                    </div>
                                )}
                            />
                        </div>

                        <div className="w-full mb-4">
                            <label>Comments</label>
                            <Field
                                name="comments"
                                as="textarea"
                                placeholder="Any additional comments"
                                className="w-full border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm p-2"
                            />
                            <ErrorMessage name="comments" component="div" className="text-red-500 text-sm mt-1" />
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
