import { useState } from "react";

import { DarkModeProvider } from "./utils/check_dark_mode";
import { useDarkMode } from "./utils/check_dark_mode";

function App() {
  const isDarkMode = useDarkMode();

  return (
    <DarkModeProvider>
      <div className="dark:bg-black h-[100vh] w-[100vw] flex justify-center items-center">
        <h1 className="text-2xl dark:text-white text-red-600">Ijtema App</h1>
      </div>
    </DarkModeProvider>
  );
}

export default App;
