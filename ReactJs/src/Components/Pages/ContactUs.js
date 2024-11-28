import React from 'react'
import circle from '../../assets/images/circle.svg';
import facebooklg from '../../assets/images/facebook-lg.svg';
import instagramlg from '../../assets/images/instagram-lg.svg';
import linkedinlg from '../../assets/images/linkedin-lg.svg';
import emaillg from '../../assets/images/email-lg.svg';
import wave from '../../assets/images/wave.svg';



const ContactUs = () => {
  return (
    <div><section class="breadcrumb-box">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-sm-auto">
                <h1>CONTACT US</h1>
            </div>
            
        </div>
    </div>
</section>

<section class="py-5 contact-us-bg" >
    <div class="container section-padding">


        <div class="row justify-content-center py-5">
            <div class="col-12 div col-sm-6 col-md-6 col-lg-5 position-relative">
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
                <img src={wave} alt="" class="wave-right-bottom" />
            </div>
            <div class="col-12 div col-sm-6 col-md-6 col-lg-5 position-relative">
                <div class="contact-us-box">
                    <div class="row justify-content-center align-items-center">
                        <div class="col-auto">
                        <span class="social-box">
                            <img src={facebooklg} alt="" /> 
                            <img src={instagramlg} alt="" />   
                            <img src={linkedinlg} alt="" />   
                        </span>
                        </div>
                        <div class="col-auto">
                        <p class="color-txt">FIND US ON</p>
                            <h4>Social Media</h4>
                        </div>
                  
                    </div>
                    
                </div>
                <img src={circle} alt="" class="circle-topRight" />
            </div>

        </div>
     </div>    
</section>
</div>
  )
}

export default ContactUs;