// main.jsx
import React, { Suspense } from 'react';
import ReactDOM from 'react-dom/client';
import { RouterProvider } from 'react-router-dom';
import router from './router/index';
import { RecoilRoot } from 'recoil';

// Perfect Scrollbar
import 'react-perfect-scrollbar/dist/css/styles.css';

// Tailwind css
import './index.css';

import { DarkModeProvider } from './utils/hooks/useDarkMode';
import App from './App';
import { LoadingProvider } from './utils/hooks/useLoading';

ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
        <Suspense>
            <DarkModeProvider>
                <RecoilRoot>
                    <LoadingProvider>
                        <RouterProvider router={router}>
                            <App />
                        </RouterProvider>
                    </LoadingProvider>
                </RecoilRoot>
            </DarkModeProvider>
        </Suspense>
    </React.StrictMode>
);
