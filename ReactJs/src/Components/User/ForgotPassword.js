import React, {useState } from 'react';
import formsideimg from '../../assets/images/form-side-img.png';
import Swal from "sweetalert2";
import globeVar from '../../GlobeApi';

const initialState = { email:"",
emailError:""};

const ForgotPassword = () => {
    const [user, setUser]= useState(initialState);
    const {email} = user;
    const handelInput = e =>{
      
       setUser({ ...user, [e.target.name]: e.target.value });
       };
       
       const validate = () =>{
          let emailError = "";
          /* let passwordError = ""; */
  
          if(!user.email.includes("@")){
              emailError = "Please Enter Valid Email";
          }
          if(!user.email){
              emailError = "Please Enter Email";
          }
          
          if(emailError ){
              setUser({emailError});
              return false;
          }
  
          return true;
        }

       const handelSubmit = async e => {
        e.preventDefault();
        const isValid = validate();
        if(isValid){
          setUser(initialState);
        }
        console.warn(email)
        let item = { "email":email };
        let result =await fetch(globeVar +"user/forgetpassword/sed_email",{
          method : 'POST',
          headers:{
            "Content-Type": "application/json",
            "Accept": 'application/json'
          },
          body: JSON.stringify(item)
        });
        result = await result.json();
        // set the state of the user
        if(result.success === 1)
        {
          Swal.fire({
          position: 'top-end',
          title: '<span style="font-size:1.4rem";><small>Email Send successfully</small></span>',
          showConfirmButton: false,
          customClass: 'swal-wide',
          timer: 1500,
        }).then(function () {
          window.location = '/login';
          setUser(result.data);
      })
        
        }else{
         //alert("Invalid Email and Password");
         Swal.fire({
            icon: 'error',
            title: 'Email Does Not Exist !...',
            text: 'Error ...!'
          })
        }


     
       };
     
  return (
    <div><section class="breadcrumb-box">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-sm-auto">
                <h1>FORGOT PASSWORD</h1>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container section-padding">

        <div class="row justify-content-center flex-column-reverse flex-lg-row">

            <div class="col-12 col-md-12 col-lg-10 col-xl-8">
                <div class="form-box">
                    <form action="" onSubmit={handelSubmit}>
                        <div class="row">
                            <div class="col-12 col-xl-10">
                                <h3 class="title">Forgot Password</h3>
                                <div class="form-row">

                                    <div class="form-group input-box col-sm-12">
                                        <label for="email">Email</label>
                                        <input type="text" class="form-control" name="email" id="email" 
                                        onChange={e => handelInput(e)} value={email} placeholder="Your email here" />
                                         <div style={{color:"red"}}>{user.emailError}</div>
                                    </div>
                                                    
                                    <div class="col-sm-12">
                                        <input  type="submit" value="Submit" class="btn gradient-btn px-5" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
            <div class="col-12 col-md-12 col-lg-10 col-xl-4 text-center">

                <img src={formsideimg} class="radius-shadow-img img-fluid mb-4" alt="" />

            </div>

        </div>
    </div>
</section></div>
  )
}

export default ForgotPassword;