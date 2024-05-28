import { lazy } from "react";
import PageNotFound from "../pages/miscallenious/404_not_found.page";
const LoginPage = lazy(() => import("../pages/login.page"));
const RegisterPage = lazy(() => import("../pages/register.page"));

const routes = [
  {
    path: "/",
    element: <LoginPage />,
  },
  {
    path: "/login",
    element: <LoginPage />,
  },
  {
    path: "/register",
    element: <RegisterPage />,
  },
  {
    path: "*",
    element: <PageNotFound />,
  },
];

export { routes };
