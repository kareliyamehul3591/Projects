export default function ResetPassword2(values) {
    let errors = {};
    
    if (!values.old_password) {
        errors.old_password = "Old Password is required";
    } 
  
    if (!values.new_password) {
        errors.new_password = "New Password is required";
      } else if (!values.new_password.match(/^(?=.*\d)(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z]).{8,}$/)) {
        errors.new_password = "A minimum 8 characters password contains a combination of one uppercase and lowercase letter, and one number, and one Special character.";
      }

      if (!values.re_enter_password) {
        errors.re_enter_password = "Re-password is required";
      } else if (values.re_enter_password !== values.new_password) {
        errors.re_enter_password = "Please Enter the Same Password";
      }  

    
    return errors;
  }