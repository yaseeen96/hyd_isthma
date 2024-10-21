import React, { useState } from 'react';
import { CgProfile } from 'react-icons/cg';
import { logoutService } from '../../../services/login_service';

const ProfileDropdown = ({ isSticky }) => {
    const [isOpen, setIsOpen] = useState(false);

    const toggleDropdown = () => {
        setIsOpen(!isOpen);
    };

    return (
        <div className="relative inline-block text-left">
            <button
                id="dropdownDefaultButton"
                data-dropdown-toggle="dropdown"
                className="text-white  font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center"
                type="button"
                onClick={toggleDropdown}
            >
                <CgProfile
                    size={35}
                    style={{
                        color: isSticky ? '#FF0000' : '#8635BD', // Change color when sticky
                    }}
                />
            </button>

            {/* Dropdown menu */}
            {isOpen && (
                <div id="dropdown" className="absolute right-0 mt-2 w-44 z-10 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700">
                    <ul className="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                        {/* <li>
                            <a href="#" className="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                Settings
                            </a>
                        </li> */}

                        <li>
                            <button
                                onClick={async () => {
                                    await logoutService();
                                }}
                                className="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                            >
                                Sign out
                            </button>
                        </li>
                    </ul>
                </div>
            )}
        </div>
    );
};

export default ProfileDropdown;
