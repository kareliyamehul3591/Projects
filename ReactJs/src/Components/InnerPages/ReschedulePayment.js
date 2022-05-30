import React, {useEffect, useState } from 'react';
import './Payment.css';
import date_time from '../../assets/images/date_time.svg';
import clock from '../../assets/images/clock.svg';
import globeVar from '../../GlobeApi';
import axios from "axios";
import Swal from 'sweetalert2';
function ReschedulePayment() {

   var data = sessionStorage.getItem('user');
   data = JSON.parse(data);
var user_email = data.email;

    const [book,setBook] = useState([])
        const booked = () => {
            const therapist_id = sessionStorage.getItem("rescheduleconfirmtherapist_id"); 
           
           
          fetch(globeVar+`therapist/${therapist_id}`)
          
            .then(response => response.json())
            .then(data => {
              console.log(data);
              /* const myObjStr = JSON.stringify(data.data); */
              data.data.book_schedule_date = sessionStorage.getItem("schedule_date"); 
              data.data.book_schedule_time = sessionStorage.getItem("schedule_time"); 
              console.log(data.data);
              setBook(data.data)
                            
            })
            .catch(error => {
              console.log(error)
            })
        }

        const upadatappointment = () => {
         const id = sessionStorage.getItem("appointment_id");
         const therapist_id = sessionStorage.getItem("rescheduleconfirmtherapist_id"); 
         const rescheduleconfirmschedule_date = sessionStorage.getItem("schedule_date");
         const rescheduleconfirmschedule_time = sessionStorage.getItem("schedule_time");
         const upd_reschedule_time=sessionStorage.getItem("upd_session_time");
         const upd_reschedule_date =sessionStorage.getItem("upd_session_date");
         const schedule_time_id = sessionStorage.getItem("schedule_time_id");
         
          let item = {"id" : id, "session_date":rescheduleconfirmschedule_date ,"session_time":rescheduleconfirmschedule_time, "schedule_time_id":schedule_time_id, "upd_reschedule_date":upd_reschedule_date, "upd_reschedule_time":upd_reschedule_time, "therapist_id":therapist_id}; 
   axios.post( globeVar+"reschedule_appointment",item).then((response)=>{
       
     console.log(response.data);
     
     if(response.data.success === 1){
      let item = {"therapist_id":therapist_id,"session_date":rescheduleconfirmschedule_date ,"session_time":rescheduleconfirmschedule_time }; 
      axios.post( globeVar+"zoom/checking",item).then((response)=>{
       
         if(response.data.success === 2){
            let item = {"email":user_email , "therapist_id":therapist_id, "session_date":rescheduleconfirmschedule_date ,"session_time":rescheduleconfirmschedule_time, "reschedule_status":1}; 
               axios.post( globeVar+"zoom/zoom_info/send_email",item).then((response)=>{
               console.log(response);
              
               });
         }else{
       const zoom_data = JSON.parse(response.data);
      const zoom_link = zoom_data.join_url;
      const zoom_id = zoom_data.id;
      const zoom_password = zoom_data.password;
      console.log(zoom_data);
      let items = {"therapist_id":therapist_id, "session_date":rescheduleconfirmschedule_date ,"session_time":rescheduleconfirmschedule_time, "zoom_link":zoom_link , "zoom_id":zoom_id, "zoom_password":zoom_password }; 
      axios.post( globeVar+"zoom/zoom_info",items).then((response)=>{
         if(response.data.success === 1){
            
             let item = {"email":user_email , "therapist_id":therapist_id, "session_date":rescheduleconfirmschedule_date ,"session_time":rescheduleconfirmschedule_time, "reschedule_status":1}; 
            axios.post( globeVar+"zoom/zoom_info/send_email",item).then((response)=>{
            console.log(response);
           
            });
             
         }else{
            alert("0");
         }
      });
   }
    
      });
  
      Swal.fire({
         position: 'top-end',
         title: '<span style="font-size:1.4rem";><small>Appointment Book successfully</small></span>',
         showConfirmButton: false,
         customClass: 'swal-wide',
         timer: 1500,
       }).then(function () {
         var myArray = response.config.data;
         var myArrays = myArray.split(',');
            console.log(myArrays);
      
         localStorage.setItem('appointment',  response.config.data)
         /* console.log(response.data) */
       sessionStorage.setItem('appointment', JSON.stringify( response.config.data));
       window.location.href = '/bookingInformation';
      
   })
         /*  var result= JSON.stringify(response.data.data.schedule_time);
          console.log(result); */
          
         }else{
         //alert("Invalid Email and Password");
         Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Incorrect Data!'
          })
        }
   })          
     } 
        useEffect(() => {
            booked();
         }, [])
  return (
    <div><section>
    <div class="container section-padding">
       <div class=" mb-4">
          <div class="align-items-center row">
             <h3 class="col mb-4 mb-md-0 title">Booking Information</h3>
             <div class="col-12 col-md-auto">
                <h5><a href="dashboard-therapists.php" class="weight-600 color-txt"> Back to Previous Page</a></h5>
             </div>
          </div>
       </div>
       <div class="form-row">
          <div class="col-12 col-sm-12 col-md-12">
             <div class="therapy_box txt-15 bg-pink">
                <div class="top">
                   <div class="row align-items-center mx-5">
                      <div class="user-box align-items-center col">
                         <img src={book.profile_image} alt="" class="user-img profile_image" />
                         <div>
                            <p class="name">{book.f_name} {book.l_name}</p>
                            <p class="color-txt mb-0">{book.therapist_type}</p>
                         </div>
                      </div>
                      <div className="col-auto">
                         <div className="icon-box">
                            <span className="icon tooltip-bottom" data-tooltip="Date & Time">
                            <img src={date_time} alt=""  />
                            </span>
                            <p className="mb-0">
                               {book.book_schedule_date}<br /> {book.book_schedule_time} IST
                            </p>
                         </div>
                      </div>
                      <div className="col-lg-2">
                         <div className="icon-box">
                            <span className="icon tooltip-bottom" data-tooltip="Hours"><img src={clock} alt="" /></span>
                            <p className="mb-0">
                               1hr
                            </p>
                         </div>
                      </div>
                      <div class="col-auto">
                         <span class="badge hour-badge float-right">${book.hourly_price}/hr</span>
                      </div>
                   </div>
                </div>
             </div>
          </div>
       </div>
      
       <div class="custom-tabs">
          <div class="tab-content" id="payment-tabContent">
             <div class="tab-pane fade show active" id="creadit_card" role="tabpanel" aria-labelledby="creadit_card-tab">
                <div class="row">
                   <div class="col-md-8 mb-4">
                      <div class="form-row align-items-baseline">
                         
                         <div class="form-group col-sm-12">
                            <input type="submit" class="btn gradient-btn"   onClick={() => upadatappointment() }  value="Confirm Booking" />
                         </div>
                      </div>
                   </div>
                   <div className="cancellation-badge mx-5 pb-5" >
                       <div style={{backgroundColor: '#FFF0DF'}} class="p-4">
                           <h5 class="weight-600 color-txt">Cancellation Policy</h5>
                           <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolores repellendus exercitationem quidem adipisci incidunt. Cupiditate reprehenderit totam facilis, quas ipsa vitae voluptates?</p>
                           <p>Lorem ipsum dolor sit amet consectetur adipisicing.</p>
                       </div>
                   </div>
                </div>
             </div>
             <div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">...</div>
          </div>
       </div>
    </div>
 </section></div>
  )
}

export default ReschedulePayment