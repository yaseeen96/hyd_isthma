const BigCard = ({ className, textClassName, icon, title, onSelect, isCentered }) => {
    return (
        <button onClick={onSelect}>
            <div className={`${className} shadow-lg flex flex-col ${isCentered ? 'justify-center' : 'justify-end'}  items-center border-2 border-black rounded-lg p-12`}>
                <div className="my-2">{icon}</div>
                <h1 className={`${textClassName}`}>{title}</h1>
            </div>
        </button>
    );
};

export default BigCard;
