export default function UpadateProfile1(values) {
    let errors = {};
    if (!values.f_name) {
      errors.f_name = "Please Enter the First Name";
  }else  if (!values.f_name.match(/^[a-zA-Z]+$/)) {
      errors.f_name = "Please Enter Only Letters";
  }

  if (!values.l_name) {
      errors.l_name = "Please Enter the Last Name";
  }else if (!values.l_name.match(/^[a-zA-Z]+$/)) {
      errors.l_name = "Please Enter Only Letters";
  }  

  if (!values.phone_number.match(/^[0-9\b]+$/)) {
    errors.phone_number = "Please Enter Only digits";
  } 
    return errors;
  }