import { useState, useEffect } from "react";

const UpadateProfile = (callback, validate) => {
  var data = sessionStorage.getItem('user');
        data = JSON.parse(data);
    const [values1, setValues1] = useState({
        f_name:data.f_name,
        l_name:data.l_name,
        phone_number:data.mobile,
        current_country:data.country,
        current_state:data.state,
    
  });
    const [errors1, setErrors1] = useState({});
    const [isSubmitting, setIsSubmitting] = useState(false);
  
    const handleChange1 = event => {
      const { name, value } = event.target;
      setValues1({
        ...values1,
        [name]: value,
      });
    };
  
    const handleSubmit1 = event => {
      event.preventDefault();
      setErrors1(validate(values1));
      setIsSubmitting(true);
    };
  
    useEffect(() => {
      if (Object.keys(errors1).length === 0 && isSubmitting) {
        callback();
      }
    }, [errors1]);
  
    return {
      handleChange1,
      handleSubmit1,
      values1,
      errors1
    };
  };
  
  
  export default UpadateProfile;