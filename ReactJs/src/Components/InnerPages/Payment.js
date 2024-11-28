import React, {useEffect, useState } from 'react';
import axios from "axios";

import Payment_1 from './Payment_1';
import Payment2 from './Payment2';
import Swal from 'sweetalert2';
import './Payment.css';
import date_time from '../../assets/images/date_time.svg';
import clock from '../../assets/images/clock.svg';
import payment_method2 from '../../assets/images/payment_method2.png';
import payment_method from '../../assets/images/payment_method.png';
import $ from 'jquery';
import globeVar from '../../GlobeApi';

const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

function Payment() {
   const session_price = sessionStorage.getItem("session_price")
   var data = sessionStorage.getItem('user');
   data = JSON.parse(data);
var user_email = data.email;
   const [book,setBook] = useState([])

        const booked = () => {
            var therapist_id = sessionStorage.getItem("therapist_id"); 
           

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

        /* const today = Date.now(); */
    /*  const maxDate=new Date(); 
        //let date = today.getFullYear() + '-' + parseInt(today.getMonth() + 1) + '-' + today.getDate()
        var date=new Date(); 
        date.setDate(date.getDate() + 1);


         console.log(maxDate);  */

        const { handleChange, handleSubmit, values, errors } = Payment_1(
         submit,
         Payment2
       );
    
       function submit() { 
         console.log("Submitted Succesfully");
         
            if (!window.document.getElementById("stripe-script")) {
               var s = window.document.createElement("script");
               s.id = "stripe-script";
               s.type = "text/javascript";
               s.src = "https://js.stripe.com/v2/";
               s.onload = () => {
                 window["Stripe"].setPublishableKey(
                   "pk_test_51K5RgGGlIMsag8BjMJJWwyDbgqjuaWcDfb5zHjEpM7RrfSPM9SwJ7z5kgQLJ1nqaqbasYxweHU1Q9DncWLR39llW000D85cyaI"
                 );
               };
               window.document.body.appendChild(s);
             }  
            
            stripepayment();
         }
      

         const stripepayment = async () => {
            var therapist_id = $("#therapist_id").val();
            var schedule_date = $("#schedule_date").val();
            var schedule_time = $("#schedule_time").val(); 
           
            await sleep(300);
            try {
              window.Stripe.card.createToken(
                {
                  number: values.card_number,
                  exp_month: values.exp_date.split("/")[0],
                  exp_year: values.exp_date.split("/")[1],
                  cvc: values.cvc,
                  name: values.username,
                },
                //    daynamic amount  amount: 1,
                (status, response) => {
                  if (status === 200) {
                    axios
                      .post(globeVar+"payment/card_payment", {
                        token: response,
                        email: user_email,
                        amount: 1/* session_price */,
                      })
                      .then((res) => appointment(therapist_id,schedule_date,schedule_time,res))
                      .catch((err) => console.log(err));
                  } else {
                    const card_error =  response.error.message;
                        console.log(card_error);
                        alert_msg(card_error);
                  }
                }
              );
            } catch (error) {
               console.log(error);
               window.location.href = '/cancelpayment';
            }
          };
          

   const [pay, setPay]= useState([]);
   var datas = sessionStorage.getItem('user');
   datas = JSON.parse(datas);
   var user_id = datas.id;
   console.log(user_id);
  
   const appointment = (therapist_id,schedule_date,schedule_time, res) => 
   {
      
      const t_details = JSON.stringify(res);
   console.log(t_details);
      const transection_details = JSON.parse(t_details);
   console.log(transection_details);
      const transaction_id = transection_details.data.id
      const payment_method = transection_details.data.payment_method_details.type;
      const payment_status = transection_details.data.status;
      var schedule_time_id = sessionStorage.getItem("schedule_time_id");
   let item = {"user_id":user_id,"therapist_id":therapist_id,"session_date":schedule_date ,"session_time":schedule_time ,"session_price":session_price,
   "payment_id":123, "schedule_time":schedule_time, "schedule_time_id":schedule_time_id,"transection_id":transaction_id ,"transection_details":t_details, "payment_method": payment_method, "payment_status":payment_status}; 
   axios.post( globeVar+"appointment/add_appointment",item).then((response)=>{
       
     // set the state of the user
     
     if(response.data.success === 1)

   {   let item = {"therapist_id":therapist_id,"session_date":schedule_date ,"session_time":schedule_time }; 
      axios.post( globeVar+"zoom/checking",item).then((response)=>{
       
         if(response.data.success === 2){
            let item = {"email":user_email , "therapist_id":therapist_id, "session_date":schedule_date ,"session_time":schedule_time, "reschedule_status":0}; 
               axios.post( globeVar+"zoom/zoom_info/send_email",item).then((response)=>{
               console.log(response);
              
               });
         }else{
       const zoom_data = JSON.parse(response.data);
      const zoom_link = zoom_data.join_url;
      const zoom_id = zoom_data.id;
      const zoom_password = zoom_data.password;
      console.log(zoom_data);
      let items = {"therapist_id":therapist_id, "session_date":schedule_date , "session_time":schedule_time, "zoom_link":zoom_link , "zoom_id":zoom_id, "zoom_password":zoom_password }; 
      axios.post( globeVar+"zoom/zoom_info",items).then((response)=>{
         if(response.data.success === 1){
            
             let item = {"email":user_email , "therapist_id":therapist_id, "session_date":schedule_date ,"session_time":schedule_time, "reschedule_status":0}; 
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
         setPay(myArrays)
   
      localStorage.setItem('appointment',  response.config.data)
     
    sessionStorage.setItem('appointment', JSON.stringify( response.config.data));
    window.location.href = '/bookingInformation';
   
})
      
       
      }else{
     
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
    <div className="container section-padding">
       <div className=" mb-4">
          <div className="align-items-center row">
             <h3 className="col mb-4 mb-md-0 title">Booking Information</h3>
             <div className="col-12 col-md-auto">
                <h5><a href="dashboardtherapists" className="weight-600 color-txt">Back to Previous Page</a></h5>
             </div>
          </div>
       </div> 
        <div className="form-row">
         
         <div className="col-12 col-sm-12 col-md-12">
            <div className="therapy_box txt-15 bg-pink">
                <div className="top">
                   <div className="row align-items-center mx-5">
                      <div className="user-box align-items-center col">
                         <img src={book.profile_image} alt="" className="user-img profile_image" />
                         <div>
                            <p className="name">{book.f_name} {book.l_name}</p>
                            <p className="color-txt mb-0">{book.therapist_type}</p>
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
                      <div className="col-auto">
                         <span className="badge hour-badge float-right">${book.hourly_price}/hr</span>
                      </div>
                   </div>
                </div>
             </div>
             
           </div>
          
       </div>
        
       <div className=" mb-4">
          <div className="align-items-center row">
             <h3 className="col mb-4 mb-md-0 title">Payment Method</h3>
          </div>
       </div>
       <div className="custom-tabs">
          <ul className="nav nav-pills mb-4" id="payment-tab" role="tablist">
             <li className="nav-item" role="presentation">
                <a className="nav-link active" id="creadit_card-tab" data-toggle="pill" href="#creadit_card" role="tab" aria-controls="creadit_card" aria-selected="true">
                   <img src={payment_method2} alt="" className="tab-icon active" />
                   <img src={payment_method} alt="" className="tab-icon inactive" />
                   Credit Card</a>
             </li>
          </ul>
                    
          <div id="alert_msg"> </div> 
                          
          <div className="tab-content" id="payment-tabContent">
             <div className="tab-pane fade show active" id="creadit_card" role="tabpanel" aria-labelledby="creadit_card-tab">
                <div className="row">
                   <div className="col-md-8 mb-4">

                      <form id='myform' method='POST' /* action='/bookingInformation' */ onSubmit={handleSubmit} noValidate>
                      
                      <div className="form-row align-items-baseline">
                         <div className="form-group input-box col-sm-6">
                            <label for="card_number">Credit Card Number</label>
                            <input type="text" className= "form-control inputError" name="card_number"  
                            value={values.card_number} onChange={handleChange} id="card_number" pattern="\d{16}" placeholder="Your card number here" />
                            {errors.card_number && <p className="error">{errors.card_number}</p>}
                         </div>
                         <div className="form-group input-box col-sm-6">
                            <label for="username">Name</label>
                            <input type="text"  className= "form-control inputError" name="username"  
                            value={values.username} onChange={handleChange} id="username" placeholder="Your Username here" />
                            {errors.username && <p className="error">{errors.username}</p>}
                         </div>
                         <div className="form-group input-box col-sm-3">
                            <label for="exp_date">Expiration Date</label>
                            <input type="text" className= "form-control inputError" name="exp_date"  
                            value={values.exp_date} /* size="2" */ /* min={date.toISOString().substr(0, 10)} */   onChange={handleChange} id="exp_date" placeholder="MM/YY" />
                           {errors.exp_date && <p className="error">{errors.exp_date}</p>}
                         </div>
                         <div className="form-group input-box col-sm-3">
                            <label for="cvv">CVV</label>
                            <input type="text" className= "form-control inputError" name="cvv"  
                            value={values.cvv} onChange={handleChange} id="cvv" pattern="\d{3}" placeholder="Your cvv here" />
                            
                            <input type="hidden" className="form-control" name="therapist_id"  value={sessionStorage.getItem('therapist_id')}  id="therapist_id" />
                            <input type="hidden" className="form-control" name="schedual_date" value={sessionStorage.getItem('schedule_date')}  id="schedule_date" />
                            <input type="hidden" className="form-control" name="schedual_time" value={sessionStorage.getItem('schedule_time')}  id="schedule_time" />
                            {errors.cvv && <p className="error">{errors.cvv}</p>}
                         </div>
                         <div className="form-group col-sm-12">
                            <div className="checkbox">
                               <input type="checkbox" className= "form-control inputError" name="checkbox" onChange={handleChange}  value={values.checkbox}  id="lang_english" />
                               <label for="lang_english"  className="weight-600">Accept the <a href="#" className="color-txt">Terms & Conditions</a> of the transaction.</label>
                               {errors.checkbox && <p className="error">{errors.checkbox}</p>}
                            </div>
                         </div>
                         <div className="form-group col-sm-12">
                            <input type="submit" id='submit' className="btn gradient-btn" /* onClick={() =>appointment(sessionStorage.getItem('therapist_id'),sessionStorage.getItem('schedule_date'),sessionStorage.getItem('schedule_time'),card_number,username,exp_date,cvv)} */  value="Confirm Booking" />
                         </div>
                      </div>
                      </form>
                   </div>
                   <div className="col-md-4 mb-4">
                       <div style={{backgroundColor: '#FFF0DF'}} className="p-4">
                           <h5 className="weight-600 color-txt">Cancellation Policy</h5>
                           <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Dolores repellendus exercitationem quidem adipisci incidunt. Cupiditate reprehenderit totam facilis, quas ipsa vitae voluptates?</p>
                           <p>Lorem ipsum dolor sit amet consectetur adipisicing.</p>
                       </div>
                   </div>
                </div>
             </div>
             <div className="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">...</div>
          </div>
       </div>
    </div>
 </section></div>
  )
}

			
function alert_msg(card_error) {
   //(success,danger)
     
   var html = '';
   html += '<div class="alert alert-danger  alert-dismissible fade show" id="alert_success" role="alert">';
   html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
   html += '<span aria-hidden="true">&times;</span>';
   html += '</button>';
   html += '<div class="msg">' + card_error + '</div>';
   html += '</div>';
   $("#alert_msg").html(html);
   $("#alert_msg").show();
   setTimeout(function () {
       $('#alert_msg').hide();
   }, 5000);
}
export default Payment;