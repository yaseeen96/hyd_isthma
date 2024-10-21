import React, { useState, useEffect } from 'react';
import { Formik, Field, Form, ErrorMessage } from 'formik';
import * as Yup from 'yup';
import RegistrationLayout from '../layout/registrationLayout';
import { updateFamilyDetails } from '../../../services/registration_service';
import { useNavigate } from 'react-router-dom';
import { useRecoilValue } from 'recoil';
import { registrationDetailsAtom } from '../../../store/atoms/registrationDetailsAtom';
import { MdDelete } from 'react-icons/md';
import { ROUTES } from '../../../router/routes';

const validationSchema = Yup.object().shape({
    accompanying: Yup.string().required('Required'),
    adults: Yup.array().of(
        Yup.object().shape({
            id: Yup.string().nullable(),
            name: Yup.string().required('Required'),
            age: Yup.number().min(8, 'Must be at least 8').required('Required'),
            gender: Yup.string().required('Required'),
            interested_in_volunteering: Yup.string().oneOf(['yes', 'no']).required('Required'),
        })
    ),
    children: Yup.array().of(
        Yup.object().shape({
            id: Yup.string().nullable(),
            name: Yup.string().required('Required'),
            age: Yup.number().max(7, 'Must be at most 7').required('Required'),
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
        console.log('user data:', user);
        if (user && user.member_reg_data) {
            const familyDetails = user.member_reg_data.family_details || [];
            console.log('familyDetails:', familyDetails);
            const accompanying = familyDetails.length > 0 ? 'yes' : 'no';
            const adults = familyDetails.filter((detail) => detail.type === 'mehram');
            const children = familyDetails.filter((detail) => detail.type === 'children');
            const newInitialValues = {
                accompanying,
                adults: adults.map((adult) => ({
                    id: adult.id || null,
                    name: adult.name,
                    age: adult.age,
                    gender: adult.gender,
                    interested_in_volunteering: adult.interested_in_volunteering || 'no',
                })),
                children: children.map((child) => ({ id: child.id || null, name: child.name, age: child.age, gender: child.gender })),
            };
            console.log('newInitialValues:', newInitialValues);
            setInitialValues(newInitialValues);
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
        const newArray = [...values[field], { id: null, name: '', age: '', gender: '', interested_in_volunteering: 'no' }];
        setFieldValue(field, newArray);
    };

    const removePerson = (field, index, values, setFieldValue) => {
        const newArray = values[field].filter((_, idx) => idx !== index);
        setFieldValue(field, newArray);
    };

    const renderPersonFields = (values, field, setFieldValue) =>
        values[field].map((_, index) => (
            <div key={index} className="grid grid-cols-12 justify-start items-center gap-4">
                <div className="flex flex-col gap-2 mt-2 w-full relative col-span-10 border-b border-gray-300 pb-4">
                    <Field type="text" name={`${field}[${index}].name`} placeholder={`${field === 'adults' ? 'Adult' : 'Child'} ${index + 1} Name`} className="form-input flex-grow mr-2" />
                    <ErrorMessage name={`${field}[${index}].name`} component="div" className="text-red-500" />

                    <Field
                        type="number"
                        name={`${field}[${index}].age`}
                        placeholder={`${field === 'adults' ? 'Adult' : 'Child'} ${index + 1} Age`}
                        className="form-input"
                        min={field === 'adults' ? 8 : 0}
                        max={field === 'children' ? 7 : undefined}
                    />
                    <ErrorMessage name={`${field}[${index}].age`} component="div" className="text-red-500" />

                    <Field as="select" name={`${field}[${index}].gender`} className="form-select">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </Field>
                    <ErrorMessage name={`${field}[${index}].gender`} component="div" className="text-red-500" />

                    {field === 'adults' && (
                        <div className="flex items-center mt-2">
                            <label className="mr-2"> Interested in volunteering during Ijtema?</label>
                            <Field as="select" name={`${field}[${index}].interested_in_volunteering`} className="form-select">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </Field>
                        </div>
                    )}
                    <ErrorMessage name={`${field}[${index}].interested_in_volunteering`} component="div" className="text-red-500" />
                </div>
                <button type="button" onClick={() => removePerson(field, index, values, setFieldValue)} className="btn btn-danger col-span-2 p-2">
                    <MdDelete size={24} />
                </button>
            </div>
        ));

    const handleSubmit = async (values) => {
        setLoading(true);

        const result = {
            mehrams:
                values.accompanying === 'yes'
                    ? values.adults.map((adult) => ({
                          id: adult.id,
                          interested_in_volunteering: adult.interested_in_volunteering,
                          name: adult.name,
                          age: adult.age,
                          gender: adult.gender,
                      }))
                    : [],
            childrens:
                values.accompanying === 'yes'
                    ? values.children.map((child) => ({
                          id: child.id,
                          name: child.name,
                          age: child.age,
                          gender: child.gender,
                      }))
                    : [],
        };
        console.log(result);

        const success = await updateFamilyDetails(result);

        setLoading(false);
        if (success) {
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
                        <label className="w-full mb-2">Is anyone in your family accompanying you, mention the details. Do not add those family members names who are also Arkan-e-Jamat</label>
                        <Field as="select" name="accompanying" className="form-select" onChange={(e) => handleAccompanyingChange(e.target.value, setFieldValue)}>
                            <option value="no">No</option>
                            <option value="yes">Yes</option>
                        </Field>

                        <ErrorMessage name="accompanying" component="div" className="text-red-500" />

                        <div className="w-full bg-gray-300 h-[1px] my-2"></div>

                        {values.accompanying === 'yes' && (
                            <>
                                <div className="flex flex-col items-start gap-2 w-full">
                                    <label className="w-full mb-2">Accompanying Adults (Age must be at least 8 and above):</label>
                                    {renderPersonFields(values, 'adults', setFieldValue)}
                                    <button type="button" onClick={() => addPerson('adults', values, setFieldValue)} className="btn btn-info mt-2 self-start">
                                        Add Adult
                                    </button>
                                </div>

                                <div className="w-full bg-gray-300 h-[1px] my-2"></div>

                                <div className="flex flex-col items-start gap-2 w-full">
                                    <label className="w-full mb-2">Accompanying Children (Age must be 7 and below):</label>
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
