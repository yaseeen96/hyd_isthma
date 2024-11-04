import { useState } from 'react';

const BigCard = ({ className, textClassName, icon, title, onSelect, isCentered, isDisabled }) => {
    const [isPressed, setIsPressed] = useState(false);

    const handlePress = () => {
        setIsPressed(true);
        setTimeout(() => {
            onSelect();
            setIsPressed(false); // Reset after navigation
        }, 250); // Extended delay to enhance the transition effect
    };

    return (
        <button onClick={handlePress} disabled={isDisabled} className="transform transition-all duration-300 ease-out active:scale-90 active:opacity-70">
            <div
                className={`${className} shadow-lg flex flex-col ${isCentered ? 'justify-center' : 'justify-end'} items-center border-2 border-black rounded-lg p-12 
                    ${isPressed ? 'scale-90 opacity-70' : ''}`}
            >
                <div className="my-2">{icon}</div>
                <h1 className={`${textClassName}`}>{title}</h1>
            </div>
        </button>
    );
};

export default BigCard;
