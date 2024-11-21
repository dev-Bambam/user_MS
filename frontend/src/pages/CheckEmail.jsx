// eslint-disable-next-line no-unused-vars
import React from "react";

const CheckEmail = () => (
  <div className="min-h-screen flex items-center justify-center bg-gray-100">
    <div className="text-center">
      <h2 className="text-2xl font-bold mb-4">Check Your Email</h2>
      <p className="text-gray-700">
        A verification link has been sent to your email. Please check your inbox
        and click the link to verify your account.
      </p>
      <p className="mt-4 text-sm text-gray-500">
        Didn&lsquo;t receive the email?{" "}
        <a href="/resend" className="text-blue-500 underline">
          Resend Verification Email
        </a>
      </p>
    </div>
  </div>
);

export default CheckEmail;
