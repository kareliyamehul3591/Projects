import React from 'react'
import Swal from 'sweetalert2';
/* import { useParams } from 'react-router-dom'; */
import works2 from '../../assets/images/works2.jpg';
import globeVar from '../../GlobeApi';

class TherapistRegisterSignup  extends React.Component {
    
    constructor() {
        super(); 
        /* const { token } = useParams(); */
            this.state = {
                password:"",
              input: {},
              errors: {}
            };
           
           this.handleChange = this.handleChange.bind(this);
            this.handleSubmit = this.handleSubmit.bind(this);
            
          }
          
      handleChange(event) {
            let input = this.state.input;
            input[event.target.name] = event.target.value;
    
      
    
        this.setState({
             input,
             
            });
          }
        
          handleSubmit(event) {
    
        event.preventDefault();
       /*  let item = { "password":password }; */
        let result =fetch(globeVar+"generatepassword",{
          method : 'POST',
          headers:{
            "Content-Type": "application/json",
            "Accept": 'application/json'
          },
          body: JSON.stringify()
        });
        result = result.json();
        // set the state of the user
        if(result.success === 1)
        {Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: 'Password Generate successfully',
            showConfirmButton: false,
            timer: 1500,
          })
          /* setUser(result.data); */
          
      /*   window.location.href = '/login'; */
        }else{
         //alert("Invalid Email and Password");
         Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Error ...!'
          })
        }

                
        if(this.validate()){
                console.log(this.state);
        
                let input = {};
               input["password"] = "";
                input["confirm_password"] = "";
                this.setState({input:input});
             
            alert('Demo Form is submited');
            }
          }
        
      validate(){
              let input = this.state.input;
              let errors = {};
              let isValid = true;
        
          if (!input["password"]) {
                isValid = false;
                errors["password"] = "Please enter your password.";
              }
         
              if (!input["confirm_password"]) {
                isValid = false;
                errors["confirm_password"] = "Please enter your confirm password.";
              }
             
          if (typeof input["password"] !== "undefined" && typeof input["confirm_password"] !== "undefined") {
                if (input["password"] !== input["confirm_password"]) {
                  isValid = false;
                  errors["password"] = "Passwords don't match.";
                }
              } 
                        this.setState({
                errors: errors
              });
         
              return isValid;
          }    
      render() {
  return (
    <div><section class="breadcrumb-box">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-sm-auto">
                <h1>Generate Password</h1>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container section-padding">

        <div class="row justify-content-center flex-column-reverse flex-lg-row">

            <div class="col-12 col-md-12 col-lg-10 col-xl-8">
                <div class="form-box">
                    <form action="" onSubmit={this.handleSubmit}>
                        <div class="row">
                            <div class="col-12 col-xl-10">
                            <h3 class="title">Create Password</h3>
                        <div class="form-row">
           
            <div class="form-group input-box col-sm-12">
                <label for="password">Password</label>
                <div class="position-relative">
                    <input type="password" class="form-control" value={this.state.input.password}
                        onChange={this.handleChange} name="password" id="password" placeholder="Your password here" />
                    <i class="fa fa-eye input-icon"></i>
                    <div className="text-danger">{this.state.errors.password}</div>
                </div>
            </div>
            <div class="form-group input-box col-sm-12">
                <label for="confirm_password">Re-Enter Password</label>
                <div class="position-relative">
                    <input type="password" class="form-control" value={this.state.input.confirm_password}
                        onChange={this.handleChange} name="confirm_password" id="confirm_password" placeholder="Re-Enter Your password here" />
                    <i class="fa fa-eye input-icon"></i>
                    <div className="text-danger">{this.state.errors.confirm_password}</div>
                </div>
            </div>
            <div class="col-sm-12">
                <input type="submit"  value="Signup" class="btn gradient-btn px-5" />
            </div>
        </div>
                            </div>
                        </div>
                  
                    </form>
                </div>

            </div>
            <div class="col-12 col-md-12 col-lg-10 col-xl-4 text-center">
                
            <img src={works2} class="radius-shadow-img img-fluid mb-4" alt="" />

            </div>

        </div>
    </div>
</section>
</div>
  )
}
}
export default TherapistRegisterSignup;