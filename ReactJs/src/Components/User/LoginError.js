export default function LoginError2(values) {
  let errors = {};
  
  if (!values.email) {
      errors.email = "Email is required";
  } else if (!values.email.match(/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/)) {
      errors.email = "Please Enter Valid Email Address ";
    }

  if (!values.password) {
      errors.password = "password is required";
    } else if (!values.password.match(/^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/)) {
      errors.password = "A minimum 8 characters password contains a combination of one uppercase and lowercase letter, and one number, and one Special character.";
    }
  return errors;
}
/* ^[a-zA-Z0-9]+@[a-zA-Z0-9]+\.[A-Za-z]+$ */