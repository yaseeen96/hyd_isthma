import React from 'react';

const IjtemaBanner = () => {
    return (
        <div
            className="bg-white overflow-hidden rounded-lg shadow-lg text-center text-white p-4 md:p-6 lg:p-8 my-6"
            style={{
                backgroundImage: `
                    url('https://cdn.britannica.com/44/102944-050-18D72EC5/Domes-mosque-Malaysia.jpg')
                `,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
            }}
        >
            {/* English Text */}
            <h2 className="text-2xl md:text-3xl lg:text-4xl font-extrabold mb-2">5th ALL India Ijtema-E-Arkan</h2>
            <h3 className="text-lg md:text-xl lg:text-2xl font-semibold mb-4">Jamaat-E-Islami Hind</h3>

            {/* Decorative Divider */}
            <div className="flex justify-center mb-4">
                <span className="inline-block w-1 h-6 bg-white mx-2"></span>
                <span className="inline-block w-1 h-6 bg-white mx-2"></span>
                <span className="inline-block w-1 h-6 bg-white mx-2"></span>
            </div>

            {/* Urdu Text */}
            <h2 className="text-2xl md:text-3xl lg:text-4xl font-extrabold mb-2" style={{ direction: 'rtl' }}>
                پانچواں کل ہند اجتماع ارکان
            </h2>
            <h3 className="text-lg md:text-xl lg:text-2xl font-semibold mb-4" style={{ direction: 'rtl' }}>
                جماعت اسلامی ہند
            </h3>

            {/* Event Date and Location */}
            <p className="text-md md:text-lg lg:text-xl font-medium mt-4">
                At Wadi E Huda, Hyderabad on <br /> 15-17 November, 2024
            </p>
        </div>
    );
};

export default IjtemaBanner;
