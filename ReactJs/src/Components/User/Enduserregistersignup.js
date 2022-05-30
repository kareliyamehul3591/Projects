import React,{useState} from 'react';
import Enduserregistersignup1 from './Enduserregistersignup1';
import Enduserregistersignup2 from './Enduserregistersignup2';
import Swal from "sweetalert2";
import { Link } from 'react-router-dom';
import google from '../../assets/images/google.png';
import formsideimg from '../../assets/images/form-side-img.png';
import globeVar from '../../GlobeApi';
import axios from "axios";



function Enduserregistersignup() {
   const [user, setUser] = useState([]);
    const { handleChange, handleSubmit, values, errors } = Enduserregistersignup1(
        submit,
        Enduserregistersignup2
      );
    
      function submit() {
        let item = {"email": values.email };
        axios.post(  globeVar +"user/email_check",item).then((response)=>{
            if(response.data.success === 1)
      // set the state of the user
      {
        userregister( values.f_name, values.l_name, values.email);
      }else{
       //alert("Invalid Email and Password");
       Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Email Already Exist ...!'
        })
      }

    });
        
        }

         const userregister =(f_name, l_name, email) => {
         
          console.warn(f_name, l_name, email)
          let item = { "f_name":f_name, "l_name":l_name, "email":email };
            axios.post(  globeVar +"user/ragistration/sed_email",item).then((response)=>{
                if(response.data.success === 1)
          // set the state of the user
          {
            Swal.fire({
            position: 'top-end',
            title: '<span style="font-size:1.4rem";><small>Email Send successfully</small></span>',
            showConfirmButton: false,
            customClass: 'swal-wide',
            timer: 1500,
          }).then(function () {
              setUser(response.data.data);
            window.location = '/userregistersignup';
            
        })
          
          }else{
           //alert("Invalid Email and Password");
           Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: 'Error ...!'
            })
          }

        });
          /* navigate('/dashboard') */
         };
        /*  function storeProductData() {
            window.location.href = '/userregistersignup';
        } */
  return (
    <div><section class="breadcrumb-box">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-sm-auto">
                <h1>REGISTER</h1>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container section-padding">

        <div class="row justify-content-center flex-column-reverse flex-lg-row">

            <div class="col-12 col-md-12 col-lg-10 col-xl-8">
                <div class="form-box">
                    <form action="" onSubmit={handleSubmit}>
                        <div class="row">
                            <div class="col-12 col-xl-10">
                            <h3 class="title">Create Your Account</h3>
                        <div class="form-row align-items-baseline">
                        <div class="form-group input-box col-sm-6">
                    <label for="firstname">Firstname</label>
                    <input type="text" class="form-control" maxLength="30" name="f_name" id="f_name" onChange={handleChange} 
                             value={values.f_name} placeholder="Your FistName here" />
                             {errors.f_name && <p className="error">{errors.f_name}</p>}
            </div>
            <div class="form-group input-box col-sm-6">
                    <label for="lastname">LastName</label>
                    <input type="text" class="form-control" maxLength="30" name="l_name" id="l_name" onChange={handleChange} 
                             value={values.l_name} placeholder="Your LastName here" />
                             {errors.l_name && <p className="error">{errors.l_name}</p>}
            </div>
                    <div class="form-group input-box col-sm-12">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" name="email" id="email" onChange={handleChange} 
                             value={values.email} placeholder="Your email here" />
                             {errors.email && <p className="error">{errors.email}</p>}
            </div>

            
            <div class="form-group col-sm pr-4">
                <input type="submit"  value="Signup" class="btn gradient-btn px-5" />
            </div>
            
        </div>
                    
                        <div>
                            
            <p class="weight-600 mb-0 ">Already have an account? <Link to={`/login`} class="color-txt">Login</Link> </p>
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

 
export default Enduserregistersignup;