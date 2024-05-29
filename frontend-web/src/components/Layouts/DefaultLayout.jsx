import App from '../../App';

const DefaultLayout = ({ children }) => {
    return (
        <App>
            {/* BEGIN MAIN CONTAINER */}
            <div className="relative"> {children} </div>
        </App>
    );
};

export default DefaultLayout;
