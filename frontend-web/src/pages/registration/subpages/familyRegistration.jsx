import React, { useState } from 'react';
import { Formik, Field, Form, ErrorMessage } from 'formik';
import * as Yup from 'yup';
import RegistrationLayout from '../layout/registrationLayout';
import { updateFamilyDetails } from '../../../services/registration_service';
import { localStorageConstant } from '../../../utils/constants/localStorageConstants';
import { useNavigate } from 'react-router-dom';

const validationSchema = Yup.object().shape({
    accompanying: Yup.string().required('Required'),
    numAdults: Yup.number().min(0, 'Must be at least 0').required('Required'),
    numChildren: Yup.number().min(0, 'Must be at least 0').required('Required'),
    adults: Yup.array().of(
        Yup.object().shape({
            name: Yup.string().required('Required'),
            age: Yup.number().min(15, 'Must be at least 15').required('Required'),
            gender: Yup.string().required('Required'),
        })
    ),
    children: Yup.array().of(
        Yup.object().shape({
            name: Yup.string().required('Required'),
            age: Yup.number().max(14, 'Must be at most 14').required('Required'),
            gender: Yup.string().required('Required'),
        })
    ),
});

const initialValues = {
    accompanying: 'no',
    numAdults: 0,
    numChildren: 0,
    adults: [],
    children: [],
};

const FamilyRegistrationPage = () => {
    const [accompanying, setAccompanying] = useState(false);
    const [loading, setLoading] = useState(false);
    const navigate = useNavigate();

    const handleAccompanyingChange = (value, setFieldValue) => {
        setAccompanying(value === 'yes');
        setFieldValue('accompanying', value);
        if (value === 'no') {
            setFieldValue('numAdults', 0);
            setFieldValue('numChildren', 0);
            setFieldValue('adults', []);
            setFieldValue('children', []);
        }
    };

    const handleNumberChange = (field, value, values, setFieldValue) => {
        const intValue = parseInt(value, 10) || 0;
        setFieldValue(field, intValue);
        const newArray = Array.from({ length: intValue }, (_, index) => values[field][index] || { name: '', age: '', gender: '' });
        setFieldValue(field === 'numAdults' ? 'adults' : 'children', newArray);
    };

    const renderPersonFields = (values, field) =>
        values[field].map((_, index) => (
            <div key={index} className="flex flex-col gap-2 mt-2 w-full">
                <Field type="text" name={`${field}[${index}].name`} placeholder={`${field === 'adults' ? 'Adult' : 'Child'} ${index + 1} Name`} className="form-input" />
                <ErrorMessage name={`${field}[${index}].name`} component="div" className="text-red-500" />

                <Field
                    type="number"
                    name={`${field}[${index}].age`}
                    placeholder={`${field === 'adults' ? 'Adult' : 'Child'} ${index + 1} Age`}
                    className="form-input"
                    min={field === 'adults' ? 15 : 0}
                    max={field === 'children' ? 14 : undefined}
                />
                <ErrorMessage name={`${field}[${index}].age`} component="div" className="text-red-500" />

                <Field as="select" name={`${field}[${index}].gender`} className="form-select">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </Field>
                <ErrorMessage name={`${field}[${index}].gender`} component="div" className="text-red-500" />
            </div>
        ));

    const handleSubmit = async (values) => {
        setLoading(true);
        const result = {
            mehrams: values.accompanying === 'yes' ? values.adults : [],
            childrens: values.accompanying === 'yes' ? values.children : [],
        };
        console.log(result);

        const success = await updateFamilyDetails(result);

        setLoading(false);
        if (success) {
            console.log('Family details updated successfully.');
            localStorage.setItem(localStorageConstant.familyDetails, '1');
            navigate(-1);
        } else {
            console.error('Failed to update family details.');
        }
    };

    return (
        <RegistrationLayout>
            <Formik initialValues={initialValues} validationSchema={validationSchema} onSubmit={handleSubmit}>
                {({ values, setFieldValue }) => (
                    <Form className="flex flex-col items-start mt-4 gap-2 w-full">
                        <label className="w-full mb-2">Will anyone be accompanying you?</label>
                        <Field as="select" name="accompanying" className="form-select" onChange={(e) => handleAccompanyingChange(e.target.value, setFieldValue)}>
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </Field>
                        <ErrorMessage name="accompanying" component="div" className="text-red-500" />

                        {accompanying && (
                            <>
                                <div className="flex flex-col items-start mt-4 gap-2 w-full">
                                    <label className="w-full mb-2">Number of accompanying adults (Above age 14):</label>
                                    <Field type="number" name="numAdults" className="form-input" min={0} onChange={(e) => handleNumberChange('numAdults', e.target.value, values, setFieldValue)} />
                                    <ErrorMessage name="numAdults" component="div" className="text-red-500" />
                                    {renderPersonFields(values, 'adults')}
                                </div>

                                <div className="flex flex-col items-start mt-4 gap-2 w-full">
                                    <label className="w-full mb-2">Number of accompanying children (Below age 14):</label>
                                    <Field type="number" name="numChildren" className="form-input" min={0} onChange={(e) => handleNumberChange('numChildren', e.target.value, values, setFieldValue)} />
                                    <ErrorMessage name="numChildren" component="div" className="text-red-500" />
                                    {renderPersonFields(values, 'children')}
                                </div>
                            </>
                        )}
                        <button type="submit" className="btn btn-primary mx-auto my-4 w-full" disabled={loading}>
                            {loading ? 'Submitting...' : 'Submit'}
                        </button>
                    </Form>
                )}
            </Formik>
        </RegistrationLayout>
    );
};

export default FamilyRegistrationPage;
