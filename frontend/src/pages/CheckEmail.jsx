// eslint-disable-next-line no-unused-vars
import React, { useState } from "react";
import axios from "axios";

const CheckEmail = () => {
  const [message, setMessage] = useState(null);
  const [error, setError] = useState(null);

  const handleResend = async () => {
    setMessage(null);
    setError(null);

    try {
      const email = "user@example.com"; // Replace with user email from state/context
      const response = await axios.post("/api/resend-verification", { email });
      setMessage(response.data.message);
    } catch (err) {
      setError(err.response?.data?.message || "An error occurred");
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <div className="text-center">
        <h2 className="text-2xl font-bold mb-4">Check Your Email</h2>
        <p className="text-gray-700">
          A verification link has been sent to your email. Please check your
          inbox and click the link to verify your account.
        </p>
        {message && <p className="text-green-500 mt-4">{message}</p>}
        {error && <p className="text-red-500 mt-4">{error}</p>}
        <p className="mt-4 text-sm text-gray-500">
          Didn&apos;t receive the email?{" "}
          <button onClick={handleResend} className="text-blue-500 underline">
            Resend Verification Email
          </button>
        </p>
      </div>
    </div>
  );
};

export default CheckEmail;
