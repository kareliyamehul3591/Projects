import React from 'react';
import { GoogleLogin } from 'react-google-login';
import globeVar from '../../GlobeApi';
import axios from "axios";
import Swal from "sweetalert2";

const clientId = "479275290890-v81dfl997kin3730kliargstac4ptt4g.apps.googleusercontent.com";

function LoginByGoogle() {
    
    const onLoginSuccess = (res) => {
        localStorage.setItem('isLogin', 1);
        console.log('Login Success:', res.profileObj);
        sessionStorage.setItem('googledata', JSON.stringify(res.profileObj));
        userlogin();
    };

    const onLoginFailure = (res) => {
        console.log('Login Failed:', res);
    };

    

    const userlogin = () => {
        let data = sessionStorage.getItem('googledata');
        data = JSON.parse(data); 
        const email = data.email;
        const f_name = data.givenName;
        const l_name = data.familyName;
        const social = data.googleId;  
        console.warn(social, f_name, l_name, email)
        let item = { "email":email, "social":social, "f_name":f_name, "l_name":l_name };
        
        axios.post( globeVar +"socialmedialogin",item).then((response)=>{
                if(response.data.success === 1)
        {Swal.fire({
            position: 'top-end',
            title: '<span style="font-size:1.4rem";><small>Login successfully</small></span>',
            showConfirmButton: false,
            timer: 1500,
            customClass: 'swal-wide'
            
          }).then(function () {
           
            localStorage.setItem('user', response.data.data)
            // console.log(response.data) 
            sessionStorage.setItem('user', JSON.stringify(response.data.data));

            
            window.location.href = '/dashboard'; 
    })
          
        }else{
         //alert("Invalid Email and Password");
         Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Incorrect Email and Password!'
          })
        }

        })
        /* navigate('/dashboard') */
       };

    return (
        <div id="signingoogle">
                <GoogleLogin
                    clientId={clientId}
                    buttonText="Sign-In with Google"
                    onSuccess={onLoginSuccess}
                    onFailure={onLoginFailure}
                     cookiePolicy={'single_host_origin'} 
                    isSignedIn={true}
                />
            
        </div>
    );
}
export default LoginByGoogle;