const TileButton = ({ title, icon, onClick, isCompleted }) => {
    return (
        <button onClick={onClick} className="w-full" disabled={isCompleted}>
            <div className={`flex  items-center justify-start w-full h-full p-4  rounded-lg shadow-sm bg-gray-50 dark:bg-gray-800 border-gray-950`}>
                <div className={`flex items-center justify-center w-16 h-16 ${isCompleted ? 'bg-green-100 dark:bg-green-400' : 'bg-yellow-100 dark:bg-yellow-700'} text-gray-200 rounded-lg `}>
                    {icon}
                </div>
                <div className={`ml-4 text-lg font-semibold ${isCompleted ? 'text-gray-300 dark:text-gray-400' : 'text-gray-800 dark:text-gray-300'}`}>{title}</div>
            </div>
        </button>
    );
};

export default TileButton;
