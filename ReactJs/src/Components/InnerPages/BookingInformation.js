import React, {useEffect, useState } from 'react';
import date_time from '../../assets/images/date_time.svg';
import './BookingInformation.css';
import clock from '../../assets/images/clock.svg';
import doller from '../../assets/images/doller.svg';
import globeVar from '../../GlobeApi';


const BookingInformation = () => {

   const [bookInfo,setBookInfo] = useState([])

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
              setBookInfo(data.data)
                            
            })
            .catch(error => {
              console.log(error)
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
                <h5><a href="dashboard" className="weight-600 color-txt">  Go to Dashboard</a></h5>
             </div>
          </div>
       </div>
       <div className="form-row">
          <div className="col-12 col-sm-12 col-md-12">
             <div className="therapy_box txt-15 bg-pink">
                <div className="top">
                   <div className="row align-items-center mx-5">
                      <div className="user-box align-items-center col">
                         <img src={bookInfo.profile_image} alt="" className="user-img profile_image" />
                         <div>
                            <p className="name">{bookInfo.f_name} {bookInfo.l_name}</p>
                            <p className="color-txt mb-0">{bookInfo.therapist_type}</p>
                         </div>
                      </div>
                      <div className="col-auto">
                         <div className="icon-box">
                            <span className="icon tooltip-bottom" data-tooltip="Date & Time">
                            <img src={date_time} alt="" />
                            </span>
                            <p className="mb-0">
                               {bookInfo.book_schedule_date}<br /> {bookInfo.book_schedule_time} IST
                            </p>
                         </div>
                      </div>
                      <div className="col-auto">
                         <div className="icon-box">
                            <span className="icon tooltip-bottom" data-tooltip="Hours"><img src={clock} alt="" /></span>
                            <p className="mb-0">
                               1hr
                            </p>
                         </div>
                      </div>
                      <div className="col-auto">
                         <div className="icon-box">
                            <span className="icon tooltip-bottom" data-tooltip="Hours"><img src={doller} alt="" /></span>
                            <p className="mb-0">
                               {bookInfo.hourly_price}/hr
                            </p>
                         </div>
                      </div>
                   </div>
                   
                   <div className="cancellation-badge mx-5 pb-5">
            <p className="weight-500">Cancellation Policy : <small className="text-dark">You can Reschedule this appointment within 24 hours of booking, the appointment is non refundable</small></p>
        </div>
 
                </div>
             </div>
          </div>
       </div>
      
     
    </div>
 </section></div>
  )
}

export default BookingInformation;