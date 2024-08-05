import React from 'react';
import { FaCheckCircle, FaArrowRight } from 'react-icons/fa';

const StepsList = ({ steps, activeStep, onStepClick }) => {
    return (
        <ol className="space-y-4 w-full">
            {steps.map((step, index) => (
                <li key={step.id}>
                    <div
                        className={`w-full p-4 border rounded-lg cursor-pointer flex items-center ${
                            index < activeStep
                                ? 'text-green-700 bg-green-50 border-green-300 dark:bg-gray-800 dark:border-green-800 dark:text-green-400'
                                : index === activeStep
                                ? 'text-blue-700 bg-blue-100 border-blue-300 dark:bg-gray-800 dark:border-blue-800 dark:text-blue-400'
                                : 'text-gray-900 bg-gray-100 border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400'
                        }`}
                        role="alert"
                        onClick={() => (index <= activeStep ? onStepClick(step.id) : null)}
                    >
                        <div className="flex-shrink-0">
                            {index < activeStep ? (
                                <FaCheckCircle className="w-6 h-6 text-green-500" />
                            ) : index === activeStep ? (
                                <FaArrowRight className="w-6 h-6 text-blue-500" />
                            ) : (
                                <div className="w-6 h-6 border-2 border-gray-300 rounded-full"></div>
                            )}
                        </div>
                        <div className="ml-4">
                            <h3 className="font-medium">
                                {step.id}. {step.title}
                            </h3>
                            <p className="text-sm">{step.description}</p>
                        </div>
                    </div>
                </li>
            ))}
        </ol>
    );
};

export default StepsList;
