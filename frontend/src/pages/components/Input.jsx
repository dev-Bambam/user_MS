import PropTypes from "prop-types";

/**
 * A reusable input component.
 *
 * @param {string} type - The type of input to use. E.g. "text", "password", etc.
 * @param {string} name - The name of the input.
 * @param {string} placeholder - An optional placeholder string to display in the input.
 * @param {string} value - The current value of the input.
 * @param {function} onChange - A callback function to call when the input value changes.
 * @returns {JSX.Element} The input element.
 */
const Input = ({ type, name, placeholder, value, onChange }) => {
  return (
    <input
      type={type}
      name={name}
      placeholder={placeholder}
      value={value}
      onChange={onChange}
      className="mb-4 p-2 border rounded w-full"
    />
  );
};

// PropTypes validation
Input.propTypes = {
  type: PropTypes.string.isRequired,
  name: PropTypes.string.isRequired,
  placeholder: PropTypes.string.isRequired,
  value: PropTypes.string.isRequired,
  onChange: PropTypes.func.isRequired,
};

export default Input;
