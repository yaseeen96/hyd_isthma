import { useDarkMode } from "../../utils/check_dark_mode";

import { Link } from "react-router-dom";

const PageNotFound = () => {
  const isDark = useDarkMode();
  return (
    <div className="h-[100vh] w-[100vw] flex justify-center items-center dark:bg-black text-black text-4xl dark:text-white">
      404 Page not found
    </div>
  );
};
export default PageNotFound;
