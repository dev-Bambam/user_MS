import { useState } from "react";
import { useNavigate } from "react-router-dom";
import axios from "axios";
import Input from "./components/Input"; // Import the reusable Input component

/**
 * Registers a user and redirects to the "Check Email" page.
 * @function Register
 * @returns {JSX.Element} The registration form.
 */
const Register = () => {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    firstname: "",
    lastname: "",
    username: "",
    email: "",
    password: "",
  });
  const [error, setError] = useState("");

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError("");
    try {
      const { data } = await axios.post(
        "http://localhost:8000/register",
        formData
      );
      if (data.status === "success") {
        navigate("/check-email"); // Redirect to the "Check Email" page
      }
    } catch (err) {
      setError(err.response?.data?.message || "Something went wrong.");
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <form
        onSubmit={handleSubmit}
        className="bg-white shadow-md rounded p-8 w-full max-w-lg"
      >
        <h2 className="text-2xl font-bold mb-6 text-center text-gray-700">
          Register
        </h2>
        {error && <p className="text-red-500 text-center mb-4">{error}</p>}

        <div className="flex flex-wrap -mx-2">
          <div className="w-full md:w-1/2 px-2">
            <Input
              type="text"
              name="firstname"
              placeholder="First Name"
              value={formData.firstname}
              onChange={({ target }) =>
                setFormData({ ...formData, firstname: target.value })
              }
            />
          </div>
          <div className="w-full md:w-1/2 px-2">
            <Input
              type="text"
              name="lastname"
              placeholder="Last Name"
              value={formData.lastname}
              onChange={({ target }) =>
                setFormData({ ...formData, lastname: target.value })
              }
            />
          </div>
        </div>

        <Input
          type="email"
          name="email"
          placeholder="Email"
          value={formData.email}
          onChange={({ target }) =>
            setFormData({ ...formData, email: target.value })
          }
        />

        <div className="flex flex-wrap -mx-2">
          <div className="w-full md:w-1/2 px-2">
            <Input
              type="text"
              name="username"
              placeholder="Username"
              value={formData.username}
              onChange={({ target }) =>
                setFormData({ ...formData, username: target.value })
              }
            />
          </div>
          <div className="w-full md:w-1/2 px-2">
            <Input
              type="password"
              name="password"
              placeholder="Password"
              value={formData.password}
              onChange={({ target }) =>
                setFormData({ ...formData, password: target.value })
              }
            />
          </div>
        </div>

        <button
          type="submit"
          className="w-full bg-blue-500 text-white py-3 rounded mt-4 hover:bg-blue-600 transition"
        >
          Register
        </button>
      </form>
    </div>
  );
};

export default Register;
