import { useState } from "react";

const Register = () => {
  const [formData, setFormData] = useState({
    first_name: "",
    last_name: "",
    email: "",
    username: "",
    password: "",
  });

  const [error, setError] = useState(null);
  const [message, setMessage] = useState("");

  // Handle input changes
  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({
      ...prevData,
      [name]: value,
    }));
  };

  // Handle form submission
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await fetch("http://localhost:8000/index/register", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(formData),
      });
      const result = await response.json();
      if (result.status === "success") {
        setMessage(result.message);
        setError(null);
      } else {
        setError(result.message);
        setMessage("");
      }
    } catch (err) {
      setError(`An error occurred: ${err.message}`);
    }
  };

  return (
    <div className="max-w-md mx-auto p-4 bg-white rounded shadow-lg">
      <h2 className="text-center text-2xl font-bold">Register</h2>
      <form onSubmit={handleSubmit}>
        <div className="my-4">
          <label htmlFor="first_name" className="block text-sm font-medium">
            First Name
          </label>
          <input
            type="text"
            id="first_name"
            name="first_name"
            value={formData.first_name}
            onChange={handleChange}
            className="w-full p-2 mt-1 border border-gray-300 rounded"
            required
          />
        </div>

        <div className="my-4">
          <label htmlFor="last_name" className="block text-sm font-medium">
            Last Name
          </label>
          <input
            type="text"
            id="last_name"
            name="last_name"
            value={formData.last_name}
            onChange={handleChange}
            className="w-full p-2 mt-1 border border-gray-300 rounded"
            required
          />
        </div>

        <div className="my-4">
          <label htmlFor="email" className="block text-sm font-medium">
            Email
          </label>
          <input
            type="email"
            id="email"
            name="email"
            value={formData.email}
            onChange={handleChange}
            className="w-full p-2 mt-1 border border-gray-300 rounded"
            required
          />
        </div>

        <div className="my-4">
          <label htmlFor="username" className="block text-sm font-medium">
            Username
          </label>
          <input
            type="text"
            id="username"
            name="username"
            value={formData.username}
            onChange={handleChange}
            className="w-full p-2 mt-1 border border-gray-300 rounded"
            required
          />
        </div>

        <div className="my-4">
          <label htmlFor="password" className="block text-sm font-medium">
            Password
          </label>
          <input
            type="password"
            id="password"
            name="password"
            value={formData.password}
            onChange={handleChange}
            className="w-full p-2 mt-1 border border-gray-300 rounded"
            required
          />
        </div>

        {error && <p className="text-red-500 text-sm">{error}</p>}
        {message && <p className="text-green-500 text-sm">{message}</p>}

        <button
          type="submit"
          className="w-full py-2 mt-4 bg-blue-500 text-white rounded"
        >
          Register
        </button>
      </form>
    </div>
  );
};

export default Register;
