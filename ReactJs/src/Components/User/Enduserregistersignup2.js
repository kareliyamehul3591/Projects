export default function Enduserregistersignup2(values) {
    let errors = {};
    if (!values.f_name) {
        errors.f_name = "Please Enter the First Name";
    }else if (!values.f_name.match(/^[a-zA-Z]+$/)) {
        errors.f_name = "Please Enter Only Letters";
    }

    if (!values.l_name) {
        errors.l_name = "Please Enter the Last Name";
    }else if (!values.l_name.match(/^[a-zA-Z]+$/)) {
        errors.l_name = "Please Enter Only Letters";
    }  

    if (!values.email) {
        errors.email = "Email is required";
    } else if (!values.email.match(/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/)) {
        errors.email = "Please Enter Valid Email Address ";
      }
    return errors;
  }