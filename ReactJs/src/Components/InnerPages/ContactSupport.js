import React from 'react'
import emaillg from '../../assets/images/email-lg.svg';
const ContactSupport = () => {
  return (
    <div><section class="contact-support">
    <div class="container section-padding">
         <h3 class="title mb-4">Happy to help; reach us at</h3>
    <div class="form-row">
             <div class="col-12 div col-sm-6 col-md-6 col-lg-5 col-xl-4">
                 <div class="contact-us-box">
                     <div class="row justify-content-center align-items-center">
                         <div class="col-auto">
                         <span class="img-box">
                             <img src={emaillg} alt="" />      </span>
                         </div>
                         <div class="col-auto">
                         <p class="color-txt">EMAIL ADDRESS</p>
                             <h4><a href="mailto:info@sycinfinite.com" class="text-dark">info@sycinfinite.com</a></h4>
                         </div>
                   
                     </div>
                 </div>
             </div>
             
 
         </div>
    </div>
 </section></div>
  )
}

export default ContactSupport;