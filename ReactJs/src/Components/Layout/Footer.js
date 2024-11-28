import React, {useState, useEffect} from 'react';
import axios from 'axios';
import globeVar from '../../GlobeApi';


const Footer = () => {
  const [setting, setSetting] = useState([])
    const settings = () => {
        axios.get( globeVar+"settings").then((response)=>{
             if(response.data.success === 1)
        {
            console.log(response.data.data); 
            setSetting(response.data.data); 
               }      
        })
       };
       const [socialmedias, setSocialmedias] = useState([])
    const socialmedia = () => {
        axios.get( globeVar+"social_media").then((response)=>{
             if(response.data.success === 1)
        {
            console.log(response.data.data); 
            setSocialmedias(response.data.data); 
               }      
        })
       };

       useEffect(() => {
        settings();
        socialmedia();
       }, [])
  return ( <footer>
    <div class="container">
      <div class="row">
        <div class="col-12 col-sm-12 col-lg-4 ">
           {setting.map(mon=>{
           return<div class="row" key={mon.id}>
          
             <div class="col col-lg-12"><img src={"http://localhost:8000/images/"+mon.logo} alt="" class="img-fluid mb-3" /></div>
            <div class="social-links col-auto col-lg-12 mb-3">
               {socialmedias.map(con =>{
              return  <a href={con.link} key={con.id}><img src={"http://localhost:8000/images/"+con.logo} alt="" /></a>
              })}  
            </div>
            
                <p class="col-12 txt my-2 color-txt pr-lg-5 d-none d-md-block">{mon.footer_text}</p>
            <p class="txt my-2 col-12 d-none d-lg-block">{mon.copyright_text}</p>
          </div>})}
        </div>
        <div class="col-12 col-sm-12 col-lg-8 d-none d-md-block">
          <div class="row">
            <div class="col-12 col-sm-6 col-md-4 footer-links mb-3">
              <h3 class="footer-title">About</h3>
              <a href="aboutus">ABOUT US</a>
              <a href="#">TOP RATED THERAPISTS</a>
              <a href="#">SITE MAP</a>
              <a href="#">TERMS AND CONDITIONS</a>
              <a href="#">PRIVACY POLICY</a>
            </div>
            <div class="col-12 col-sm-6 col-md-8 footer-links">
             <div class="row">
               
            <div class="col-12 col-sm-6 col-md-6 footer-links mb-3">
              <h3 class="footer-title">HOW IT WORKS</h3>
              <a href="#">GETTING STARTED </a>
              <a href="#">WHAT TO EXPECT</a>
            </div>
            <div class="col-12 col-sm-6 col-md-6 footer-links mb-3">
              <h3 class="footer-title">HOW WE CAN HELP</h3>
              <a href="faq">FAQ </a>
              <a href="#">FIND A THERAPIST </a>
              <a href="contactus">CONTACT US</a>
            </div>
            <div class="col-12 col-sm-12">
              <div class="footer-input mb-3 mb-sm-0">
                <input type="text" class="form-control" placeholder="Enter Email Address here to Subscribe our Newsletter" />
                <input type="button" value="SUBSCRIBE" class="btn" />
              </div>
            </div>
             </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-lg-8  d-block d-md-none mobile-footer-links">
          <div class="row">
            <div class="col-12 mb-3">
            <div class="footer-input mb-3 mb-sm-0">
                <input type="text" class="form-control" placeholder="Enter Email Address here to Subscribe our Newsletter" />
                <input type="button" value="SUBSCRIBE" class="btn" />
              </div>
            </div>
            <div class="col-6 col-sm-6 col-md-4 footer-links mb-3">
              <h3 class="footer-title">About</h3>
              <a href="about-us">ABOUT US</a>
              <a href="#">TOP RATED THERAPISTS</a>
              <a href="#">SITE MAP</a>
            </div>
            <div class="col-6 col-sm-6 col-md-6 footer-links mb-3">
              <h3 class="footer-title">HOW IT WORKS</h3>
              <a href="#">GETTING STARTED </a>
              <a href="#">WHAT TO EXPECT</a>
            </div>
            <div class="col-6 col-sm-6 col-md-6 footer-links mb-3">
              <h3 class="footer-title">HOW WE CAN HELP</h3>
              <a href="faq">FAQ </a>
              <a href="#">FIND A THERAPIST </a>
              <a href="#">CONTACT US</a>
            </div>
            <div class="col-6 col-sm-6 col-md-6 footer-links mb-3">
              <a href="#">TERMS AND CONDITIONS</a>
              <a href="#">PRIVACY POLICY</a>
            </div>
          </div>
        </div>
        <p class="txt my-2 col-12 text-center d-block d-lg-none color-txt">© 2022 THE YOGA OF IMMORTALS</p>
    </div>
   </div>   
  </footer>
  )
}

export default Footer;