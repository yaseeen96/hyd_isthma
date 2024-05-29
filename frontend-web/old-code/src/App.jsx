import { useState } from "react";

import { DarkModeProvider } from "./utils/check_dark_mode";
import { useDarkMode } from "./utils/check_dark_mode";
import router from "./routes";
import { RouterProvider } from "react-router-dom";

function App() {
  return (
    <DarkModeProvider>
      <RouterProvider router={router} />
    </DarkModeProvider>
  );
}

export default App;
