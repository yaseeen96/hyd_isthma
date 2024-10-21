// main.jsx
import React, { Suspense } from 'react';
import ReactDOM from 'react-dom/client';
import { RouterProvider } from 'react-router-dom';
import router from './router/index';
import { RecoilRoot } from 'recoil';
import { QueryClientProvider, QueryClient } from 'react-query';
import { ReactQueryDevtools } from 'react-query/devtools';

// Perfect Scrollbar
import 'react-perfect-scrollbar/dist/css/styles.css';

// Tailwind css
import './index.css';

import { DarkModeProvider } from './utils/hooks/useDarkMode';
import App from './App';
import { LoadingProvider } from './utils/hooks/useLoading';
import LoadingComponent from './components/common/loadingComponent';

const queryClient = new QueryClient();
ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
        <Suspense fallback={<LoadingComponent />}>
            <DarkModeProvider>
                <RecoilRoot>
                    <LoadingProvider>
                        <QueryClientProvider client={queryClient}>
                            <RouterProvider router={router}>
                                <App />
                                <ReactQueryDevtools initialIsOpen={false} />
                            </RouterProvider>
                        </QueryClientProvider>
                    </LoadingProvider>
                </RecoilRoot>
            </DarkModeProvider>
        </Suspense>
    </React.StrictMode>
);
