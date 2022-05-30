import React, {useState, useEffect} from 'react'
import Swal from 'sweetalert2';
import globeVar from '../../GlobeApi';
import Resetpassword1 from './Resetpassword1';
import ResetPassword2 from './Resetpassword2';

import UpadateProfile from './UpadateProfile';
import UpadateProfile1 from './UpadateProfile1';

import axios from "axios";

const ManageAccount = () => {
    var data = sessionStorage.getItem('user');
        data = JSON.parse(data);
    var user_id = data.id;    
    
        /* const [resetpass, setResetPass]= useState(initialState); */
        const [passwordType, setPasswordType] = useState("password");
        const [passwordType1, setPasswordType1] = useState("password");
        const [passwordType2, setPasswordType2] = useState("password");
      
        
        const togglePassword =()=>{
            if(passwordType==="password")
            {
             setPasswordType("text")
             return;
            }
            setPasswordType("password")
          }
          const togglePassword1 =()=>{
            if(passwordType1==="password")
            {
             setPasswordType1("text")
             return;
            }
            setPasswordType1("password")
          }
          const togglePassword2 =()=>{
            if(passwordType2==="password")
            {
             setPasswordType2("text")
             return;
            }
            setPasswordType2("password")
          }
        
          const { handleChange, handleSubmit, values, errors } = Resetpassword1(
            submit,
            ResetPassword2
          );
        
          function submit() {
            console.log("Submitted Succesfully");
            PassUpdate( user_id, values.old_password, values.new_password)
            }

            const { handleChange1, handleSubmit1, values1, errors1 } = UpadateProfile(
                submit1,
                UpadateProfile1
              );
        
         function submit1() {
            
           /* Swal.fire({
                position: 'top-end',
                title: '<span style="font-size:1.4rem";><small>Password Update successfully</small></span>',
                showConfirmButton: false,
                timer: 1500,
                customClass: 'swal-wide'
                
              }).then(function(){
              window.location.href = '/manageaccount'; 
            }); */
            console.log("Submitted Succesfully");
            ProfileUpdate( user_id, values1.f_name, values1.l_name, values1.phone_number, values1.current_country, values1.current_state)
                
            }


           const PassUpdate = (user_id, old_password, new_password) => {
    
            console.warn(user_id, old_password, new_password)
            let item = { "user_id":user_id ,"old_password":old_password , "new_password":new_password }
              
                axios.post( globeVar+"user/resetpassword",item).then((response)=>{
                    if(response.data.success === 1)
            
            {Swal.fire({
                position: 'top-end',
                title: '<span style="font-size:1.3rem";><small>Password Changed Successfully</small></span>',
                showConfirmButton: false,
                timer: 1500,
                customClass: 'swal-wide'
                
              }).then(function(){
               
              window.location.href = '/manageaccount'; 
            })
              
            
           }
        })
    };

        const ProfileUpdate = (user_id, f_name, l_name, phone_number, current_country, current_state) => {
        
            console.warn(user_id, f_name, l_name, phone_number, current_country, current_state)
            let item = { "user_id":user_id ,"f_name":f_name , "l_name":l_name, "mobile":phone_number , "country":current_country, "state":current_state }
            
                axios.post( globeVar+"user/updateprofile",item).then((response)=>{
                    console.log(response.data);
                    if(response.data.success === 1)
            
            { Swal.fire({
                position: 'top-end',
                title: '<span style="font-size:1.4rem";><small>Profile Update successfully</small></span>',
                showConfirmButton: false,
                timer: 1500,
                customClass: 'swal-wide'
                
            }).then(function(){ 
                
                 console.log(JSON.stringify(response.data.data));
              sessionStorage.setItem('user', JSON.stringify(response.data.data));   
            window.location.href = '/manageaccount'; 
            })   
        }
        })
    };

    const [country,setCountry] = useState([])
    const country_list = () =>{
        axios.get(globeVar + "country").then((response)=>{
            if(response.data.success === 1){    
                setCountry(response.data.data);
            }
        });
    }
 
 const [state,setState] = useState([])
    const handleCountry= (event) =>{
        const con_id = event.target.value;

        /* const cons_id = JSON.stringify(con_id); */
        let item = {"country_id":con_id}; 
        console.log(item)
        axios.post(globeVar+"state", item).then((response)=>{
            console.log(response.data);
            if(response.data.success === 1){
                setState(response.data.data)
            }
        });
     
    }
  
    useEffect(() => {
        country_list();    
    }, [])
  return (
    <div><section>
    <div className="container section-padding">
 <div className="row">
     <div className="col-12 col-sm">
         <h3 className="title mb-4">Manage Account</h3>
     </div>
 </div>
 <div className="row">
     <div className="col-12 col-md-12">
                 <div className="form-box">
                     <form action="" onSubmit={handleSubmit1}>
                         <div className="row">
                             <div className="col-md-8">
                                 <div className="form-row">
                                 <div className="form-group input-box col-sm-6">
                                         <label for="f_name">F Name</label>
                                         <input type="text" maxLength="30" className="form-control" value={values1.f_name} onChange={handleChange1} name="f_name" id="f_name" placeholder="Your FirstName here" />
                                         {errors1.f_name && <p className="error">{errors1.f_name}</p>}
                                     </div>
                                     <div className="form-group input-box col-sm-6">
                                         <label for="l_name">L Name</label>
                                         <div className="position-relative">
                                             <input type="text" maxLength="30" className="form-control" value={values1.l_name}  onChange={handleChange1} name="l_name" id="l_name" placeholder="Your Last Name here" />
                                             {errors1.l_name && <p className="error">{errors1.l_name}</p>}
                                         </div>
                                     </div>
                                     <div className="form-group input-box col-sm-6">
                                         <label for="email">Email</label>
                                         <input type="text" className="form-control" value={data.email}  name="email" id="email" placeholder="Your email here" readOnly />
                                     </div>
                                     <div className="form-group input-box col-sm-6">
                                         <label for="phone_number">Phone Number (Optional)</label>
                                         <div className="position-relative">
                                             <input type="text" className="form-control" value={values1.phone_number} onChange={handleChange1} 
                                                                maxLength="10" minLength="10" name="phone_number" id="phone_number" placeholder="Your number here" />
                                     {errors1.phone_number && <p className="error">{errors1.phone_number}</p>}
                                         </div>
                                     </div>
                                     <div className="form-group input-box col-sm-6">
                                         <label for="current_country">Current Country of Residence</label>
                                         <select  className="form-control country" onClick={(e)=> handleCountry(e)} onChange={handleChange1} name="current_country" id="current_country" placeholder="Your Current_Country here">
                                         <option value={data.country}>{data.country_name}</option>
                                                           {country.map(cname => {
                                                            return <option value={cname.id} key={cname.id} >{cname.name}</option>
                                                           })}
                                                        </select>
                                            {errors1.current_country && <p className="error">{errors1.current_country}</p>}
                                     </div>
                                     <div className="form-group input-box col-sm-6">
                                         <label for="current_state">Current State</label>
                                         <div className="position-relative">
                
                                             <select  className="form-control state"  name="current_state" id="current_state" onChange={handleChange1} placeholder="Your Current_State here">
                                                <option value={data.state}>{data.state_name}</option>
                                                            {state.map(sname => {
                                                            return <option value={sname.id} key={sname.id}>{sname.name}</option>
                                                           })}  
                                                        </select>
                                            {errors1.current_state && <p className="error">{errors1.current_state}</p>}
                                         </div>
                                     </div>
                                     <div className="form-group col-sm-12">
                                         <div className="row">
                                             <div className="col-auto mb-3">
                                         <input type="submit" value="Submit" className="btn gradient-btn px-5" />
                                             </div>
                                             <div className="col-auto mb-3">
                                         <input type="button" value="Cancel" className="btn pink-border-btn px-5" />
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div className="col-md-4">
                             <div style={{backgroundColor: '#FFF0DF'}} className="p-4">
                           <h5 className="weight-600 color-txt">Want to Update Password?</h5>
                           <p>Click on button to set New Password.</p>
                                         <a href="#update_pass" className="btn gradient-btn px-4">Update Password</a>
                       </div>
                             </div>
                         </div>
 
                     </form>
                 </div>
     </div>
    
 </div>
 <div className="row" id="update_pass">
     <div className="col-12 col-sm">
         <h3 className="title mb-4">Update Password</h3>
     </div>
 </div>
 <div className="row">
 <div className="col-12 col-md-12">
                 <div className="form-box">
                     <form action="" onSubmit={handleSubmit}>
                         <div className="row">
                             <div className="col-md-8">
                                 <div className="form-row">
                                     <div className="form-group input-box col-sm-6">
                                         <label for="current_password">Current Password</label>
                                         <div className="position-relative">
                                             <input type={passwordType} className="form-control" value={values.old_password}
                                             onChange={handleChange} name="old_password" id="current_password" placeholder="Your password here" />
                                             <a onClick={togglePassword}> { passwordType==="password" ? <i className="fa fa-eye-slash input-icon"></i> :<i className="fa fa-eye input-icon "></i> }</a>
                                             {errors.old_password && <p className="error">{errors.old_password}</p>}
                                         </div>
                                     </div>
                                 </div>
                                 <div className="form-row">
                                     <div className="form-group input-box col-sm-6">
                                         <label for="new_password">New Password</label>
                                         <div className="position-relative">
                                             <input type={passwordType1} className="form-control" value={values.new_password} 
                                             onChange={handleChange} name="new_password" id="new_password" placeholder="Your password here" />
                                             <a onClick={togglePassword1}> { passwordType1==="password" ? <i className="fa fa-eye-slash input-icon"></i> :<i className="fa fa-eye input-icon "></i> }</a>
                                             {errors.new_password && <p className="error">{errors.new_password}</p>}
                                         </div>
                                     </div>
                                     <div className="form-group input-box col-sm-6">
                                         <label for="re_enter_password">Re-Enter Password</label>
                                         <div className="position-relative">
                                             <input type={passwordType2} className="form-control" value={values.re_enter_password} 
                                             onChange={handleChange} name="re_enter_password" id="re_enter_password" placeholder="Re-Enter Your password here" />
                                             <a onClick={togglePassword2}> { passwordType2==="password" ? <i className="fa fa-eye-slash input-icon"></i> :<i className="fa fa-eye input-icon "></i> }</a>
                                             {errors.re_enter_password && <p className="error">{errors.re_enter_password}</p>}
                                         </div>
                                     </div>
                                     <div className="form-group col-sm-12">
                                         <div className="row">
                                             <div className="col-auto mb-3">
                                         <input type="submit" value="Submit" className="btn gradient-btn px-5" />
                                             </div>
                                             <div className="col-auto mb-3">
                                         <input type="button" value="Cancel" className="btn pink-border-btn px-5" />
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>
 
                     </form>
                 </div>
     </div>
 </div>
    </div>
 </section></div>
  )
}
/* function hidesession(user_id,session){
  
   $("#current_country").val(session);

  sessionStorage.setItem('schedule_time',(session));
  


    
} */
/* $("#edit_country").val(data.country_name); */
export default ManageAccount;