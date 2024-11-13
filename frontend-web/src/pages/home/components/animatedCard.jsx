import React, { useState } from 'react';

const AnimatedCard = ({ className, textClassName, icon, title, onSelect, isCentered, isDisabled }) => {
    const [isPressed, setIsPressed] = useState(false);

    const handlePress = () => {
        setIsPressed(true);
        setTimeout(() => {
            onSelect();
            setIsPressed(false); // Reset after navigation
        }, 250); // Extended delay for animation
    };

    return (
        <button
            onClick={handlePress}
            disabled={isDisabled}
            className="w-full transform transition-all duration-300 ease-out active:scale-90 active:opacity-70" // Set w-full here
        >
            <div
                className={`${className} shadow-lg flex ${isCentered ? 'justify-center' : 'justify-start'} items-center border-2 border-black rounded-lg py-8 px-4
                    ${isPressed ? 'scale-90 opacity-70' : ''}`}
            >
                <div className="mr-2">{icon}</div> {/* Icon on the left with margin */}
                <h1 className={`${textClassName} text-left whitespace-pre leading-relaxed`} style={{ maxWidth: '100%' }}>
                    {title}
                </h1>
            </div>
        </button>
    );
};

export default AnimatedCard;
