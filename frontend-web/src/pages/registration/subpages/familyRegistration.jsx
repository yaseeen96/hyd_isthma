import React, { useState, useEffect } from 'react';
import { Formik, Field, Form, ErrorMessage } from 'formik';
import * as Yup from 'yup';
import RegistrationLayout from '../layout/registrationLayout';
import { updateFamilyDetails } from '../../../services/registration_service';
import { localStorageConstant } from '../../../utils/constants/localStorageConstants';
import { useNavigate } from 'react-router-dom';
import { useRecoilValue } from 'recoil';
import { registrationDetailsAtom } from '../../../store/atoms/registrationDetailsAtom';
import { MdDelete } from 'react-icons/md';

const validationSchema = Yup.object().shape({
    accompanying: Yup.string().required('Required'),
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

const FamilyRegistrationPage = () => {
    const user = useRecoilValue(registrationDetailsAtom);
    const [initialValues, setInitialValues] = useState({
        accompanying: 'no',
        adults: [],
        children: [],
    });
    const [loading, setLoading] = useState(false);
    const navigate = useNavigate();

    useEffect(() => {
        if (user && user.member_reg_data && user.member_reg_data.length > 0) {
            const familyDetails = user.member_reg_data[0].family_details;
            const accompanying = familyDetails.length > 0 ? 'yes' : 'no';
            const adults = familyDetails.filter((detail) => detail.type === 'mehram');
            const children = familyDetails.filter((detail) => detail.type === 'children');
            setInitialValues({
                accompanying,
                adults: adults.map((adult) => ({ name: adult.name, age: adult.age, gender: adult.gender })),
                children: children.map((child) => ({ name: child.name, age: child.age, gender: child.gender })),
            });
        }
    }, [user]);

    const handleAccompanyingChange = (value, setFieldValue) => {
        setFieldValue('accompanying', value);
        if (value === 'no') {
            setFieldValue('adults', []);
            setFieldValue('children', []);
        }
    };

    const addPerson = (field, values, setFieldValue) => {
        const newArray = [...values[field], { name: '', age: '', gender: '' }];
        setFieldValue(field, newArray);
    };

    const removePerson = (field, index, values, setFieldValue) => {
        const newArray = values[field].filter((_, idx) => idx !== index);
        setFieldValue(field, newArray);
    };

    const renderPersonFields = (values, field, setFieldValue) =>
        values[field].map((_, index) => (
            <div key={index} className="grid grid-cols-12 justify-start items-center gap-4">
                <div className="flex flex-col gap-2 mt-2 w-full relative col-span-10 ">
                    <Field type="text" name={`${field}[${index}].name`} placeholder={`${field === 'adults' ? 'Adult' : 'Child'} ${index + 1} Name`} className="form-input flex-grow mr-2" />
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
                <button type="button" onClick={() => removePerson(field, index, values, setFieldValue)} className="btn btn-danger col-span-2 p-2">
                    <MdDelete size={1000} />
                </button>
            </div>
        ));

    const handleSubmit = async (values) => {
        setLoading(true);
        const result = {
            mehrams: values.accompanying === 'yes' ? values.adults : [],
            childrens: values.accompanying === 'yes' ? values.children : [],
        };

        const success = await updateFamilyDetails(result);

        setLoading(false);
        if (success) {
            localStorage.setItem(localStorageConstant.familyDetails, '1');
            navigate(-1);
        } else {
            console.error('Failed to update family details.');
        }
    };

    return (
        <RegistrationLayout>
            <Formik initialValues={initialValues} validationSchema={validationSchema} onSubmit={handleSubmit} enableReinitialize>
                {({ values, setFieldValue }) => (
                    <Form className="flex flex-col items-start mt-4 gap-2 w-full">
                        <label className="w-full mb-2">Will anyone be accompanying you?</label>
                        <Field as="select" name="accompanying" className="form-select" onChange={(e) => handleAccompanyingChange(e.target.value, setFieldValue)}>
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </Field>

                        <ErrorMessage name="accompanying" component="div" className="text-red-500" />

                        <div className="w-full bg-gray-300 h-[1px] my-2"></div>

                        {values.accompanying === 'yes' && (
                            <>
                                <div className="flex flex-col items-start gap-2 w-full">
                                    <label className="w-full mb-2">Accompanying Adults (Above age 14):</label>
                                    {renderPersonFields(values, 'adults', setFieldValue)}
                                    <button type="button" onClick={() => addPerson('adults', values, setFieldValue)} className="btn btn-info mt-2 self-start">
                                        Add Adult
                                    </button>
                                </div>

                                <div className="w-full bg-gray-300 h-[1px] my-2"></div>

                                <div className="flex flex-col items-start gap-2 w-full">
                                    <label className="w-full mb-2">Accompanying Children (Below age 14):</label>
                                    {renderPersonFields(values, 'children', setFieldValue)}
                                    <button type="button" onClick={() => addPerson('children', values, setFieldValue)} className="btn btn-info mt-2 self-start">
                                        Add Child
                                    </button>
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
