import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import Register from "./pages/Register";
import CheckEmail from "./pages/CheckEmail";

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="*" element={<Navigate to="/register" />} />
        {/* Routes definition */}
        <Route path="/register" element={<Register />} />
        <Route path="/check-email" element={<CheckEmail />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;
