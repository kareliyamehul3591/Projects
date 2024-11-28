export default function Payment2(values) {
    let errors = {};
    if (!values.card_number) {
        errors.card_number = "Card_number is required";
    } else if (values.card_number.length < 15) {
        errors.card_number = "Card_number needs to be more than 15 characters";
      }else if (values.card_number.length > 17) {
        errors.card_number = "Card_number needs not to be more than 16 characters";
      }
    if (!values.username) {
        errors.username = "Enter the Username";
    } 
    if (!values.exp_date) {
        errors.exp_date = "Exp_date is required";
      }
    if (!values.cvv) {
        errors.cvv = "cvv is required";
      } else if (values.cvv.length >= 4) {
        errors.cvv = "cvv needs not to be more than 3 digits";
      }else if (values.cvv.length <= 2) {
        errors.cvv = "cvv needs to be more than 2 digits";
      }
    if (!values.checkbox) {
        errors.checkbox = "Checkbox is required";
      }
    return errors;
  }