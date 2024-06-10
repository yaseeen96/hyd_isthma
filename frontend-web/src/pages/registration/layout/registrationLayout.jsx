const RegistrationLayout = ({ children }) => {
    return (
        <div className="flex flex-col min-h-screen items-start py-16 px-8 bg-gray-50 dark:bg-black">
            <h1 className="text-2xl text-gray-900 dark:text-gray-50 font-bold w-5/6">Please fill the below details</h1>
            {children}
        </div>
    );
};

export default RegistrationLayout;
