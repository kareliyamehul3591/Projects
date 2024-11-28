import React, {useEffect, useState } from 'react';
import date_time from '../../assets/images/date_time.svg';
import infocircle from '../../assets/images/info-circle.svg';
import globeVar from '../../GlobeApi';
import axios from "axios";
import './Appointment.css';

const Appointments = () => {
    const maxDate=new Date(); 
       
        //let date = today.getFullYear() + '-' + parseInt(today.getMonth() + 1) + '-' + today.getDate()
        var date=new Date(); 
        date.setDate(date.getDate() + 1);


         console.log(maxDate);
    const [bookInfo,setBookInfo] = useState([])

        const booked = () => {
            var data = sessionStorage.getItem('user');
            data = JSON.parse(data);
            var user_id = data.id; 
           
                    let item = { "user_id":user_id};
 
                    axios.post( globeVar +`appointment/${user_id}`,item).then((response)=>{
                        if(response.data.success === 1)
                        {
                             var myArray = response.data.data;
                            
                            console.log(myArray);
                            
                            setBookInfo(myArray);

                        }else{
                        
                        }
                     }) 
            }
            
            const re_appointment = (id, therapist_id, session_time, session_date) =>{
                
                
                 sessionStorage.setItem("appointment_id",id);
                sessionStorage.setItem("appointment_therapist_id",therapist_id);
                sessionStorage.setItem("therapist_id",therapist_id);
                sessionStorage.setItem("upd_session_time",session_time);
                sessionStorage.setItem("upd_session_date",session_date);
                window.location.href = '/resechedulesession'; 
   
            }
            const get_link =(id) => {
                let item = {"appointment_id" : id};
                axios.post( globeVar +"zoom/get_link",item).then((response)=>{
                    if(response.data.success === 1)
                    {
                       window.open(response.data.data.zoom_link, '_blank');
                        
                    }
                    }) ;
            }
        useEffect(() => {
         booked();
      }, [])
      
  return (
    <div><section>
    <div className="container section-padding">

        <div className="accordion custom-accordion accordion2 " id="accordionExample">
            <div className="card active">
                <div className="card-header" id="headingOne">
                    <h2 className="mb-0">
                        <button className="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Upcoming Appointments
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" className="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div className="card-body">
                        <div className="row">
                            
                                
                                    {bookInfo.map(rep =>{if(rep.session_date >= date.toISOString().substr(0, 10)){
                                        if(rep.status === "1"){
                                        
                                    return<div className="col-md-6">
                                    <div key={rep.user_id} >
                                       
                                <div className="therapy_box h-auto">
                                    <div className="top" >
                                        <div className="row">
                                            <div className="col-12 col-sm mb-0">
                                                <p className="therapy_name">
                                                {rep.therapist_type}</p>
                                                <div className="user-box">
                                                    <img src={rep.profile_image} alt="" className="user-img profile_image" />
                                                    <div>
                                                        <p className="name">{rep.therapist_fname + ' ' + rep.therapist_lname} </p>

                                                        <div className="icon-box">
                                                            <span className="icon"><img src={date_time} alt="" /></span>
                                                            <p className="mb-0">
                                                                {rep.date} | <br />
                                                                {rep.session_time} IST
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="col-12 col-lg-5 col-xl-4">
                                                <div className="appointment-right mb-0">
                                                    <div className="col-6 col-lg-12">
                                                        <small className="">Your Appointment is in</small><br />
                                                        <span  id='duration'>{rep.differentDays}Days, {rep.hours}Hrs &amp; {rep.mins}Min Remaining</span>
                                                    </div>

                                                    <div className="col-6 col-lg-12">
                                                        <small className="">You Paid:</small><br />
                                                        <span className="badge hour-badge">${rep.session_price}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div className="row align-items-center">
                                            <div className="col-auto">
                                                <a onClick={() => get_link(rep.id) } className="btn gradient-btn">Begin A Session</a>
                                            </div>
                                            <div className="col-auto">
                                                
                                                <a onClick={() => re_appointment(rep.id, rep.therapist_id,rep.session_time,rep.session_date)} className="link-bold-color"><img src={infocircle} className="mr-2" alt="" />Reschedule Session</a>
                                            </div>
                                        </div>
                                        </div>
                                    </div></div>      
                            </div>
                                }else{
                                    return<div className="col-md-6">
                                    <div key={rep.user_id} >
                                    
                                <div className="therapy_box h-auto">
                                    <div className="top"  >
                                        <div className="row">
                                            <div className="col-12 col-sm mb-0">
                                                <p className="therapy_name">
                                                {rep.therapist_type}</p>
                                                <div className="user-box">
                                                    <img src={rep.profile_image} alt="" className="user-img profile_image" />
                                                    <div>
                                                        <p className="name">{rep.therapist_fname + ' ' + rep.therapist_lname} </p>

                                                        <div className="icon-box">
                                                            <span className="icon"><img src={date_time} alt="" /></span>
                                                            <p className="mb-0">
                                                            {rep.date} | <br />
                                                                {rep.session_time} IST
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div className="col-12 col-lg-5 col-xl-4">
                                                <div className="appointment-right mb-0">
                                                    <div className="col-6 col-lg-12">
                                                        <small className="">Your Appointment is in</small><br />
                                                        
                                                    </div>

                                                    <div className="col-6 col-lg-12">
                                                        <small className="">You Paid:</small><br />
                                                        <span className="badge hour-badge">${rep.session_price}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div className="row align-items-center">
                                            <div className="col-auto">
                                                
                                            </div>
                                            <div className="col-auto">
                                                <a onClick={() => re_appointment(rep.id, rep.therapist_id,rep.session_time,rep.session_date)} id={'re_appointment'+ rep.id} className="link-bold-color"><img src={infocircle} className="mr-2" alt="" />Reschedule Session</a>
                                            </div>
                                            <div className="col-auto" id='canceled'><b>Appointment Cancelled</b></div>
                                        </div>  
                                        </div>
                                      
                                    </div></div>      
                                </div>
                                }}})}
                          
                            
                        </div>
                    </div>
                </div>
            </div>
            <div className="card">
                <div className="card-header" id="headingTwo">
                    <h2 className="mb-0">
                        <button className="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Past Appointments
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" className="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div className="card-body">
                        <div className="row">
                           {bookInfo.map(rep =>{if(rep.session_date < date.toISOString().substr(0, 10)){
                                        if(rep.status === "1"){
                                        
                                    return<div className="col-md-6">
                                    <div key={rep.user_id} >
                                <div className="therapy_box h-auto">
                                    <div className="top">
                                        <div className="row">
                                            <p className="col therapy_name">
                                            {rep.therapist_type}</p>

                                            <div className="col-auto">
                                                <div className="d-flex align-items-center">
                                                    <span className="badge hour-badge float-right">${rep.session_price}/hr</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div className="row">
                                            <div className="user-box col">
                                                <img src={rep.profile_image} alt="" className="user-img profile_image" />
                                                <div>
                                                    <p className="name">{rep.therapist_fname + ' ' + rep.therapist_lname}</p>

                                                    <div className="icon-box">
                                                        <span className="icon"><img src={date_time} alt="" /></span>
                                                        <p className="mb-0">
                                                        {rep.date} | <br />
                                                                {rep.session_time} IST
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div className="row align-items-center">
                                            <div className="col-12">
                                                <a href="dashboardtherapists" className="btn gradient-btn">Re-Book Session</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                </div>
                                </div>}
                                 else{
                                    return<div className="col-md-6">
                                    <div key={rep.user_id} >
                                    <div className="therapy_box h-auto">
                                    <div className="top" >
                                        <div className="row">
                                            <p className="col therapy_name">
                                            {rep.therapist_type}</p>

                                            <div className="col-auto">
                                                <div className="d-flex align-items-center">
                                                    <span className="badge hour-badge float-right">${rep.session_price}/hr</span>
                                                </div>
                                            </div>

                                        </div>
                                        <div className="row">
                                            <div className="user-box col">
                                                <img src={rep.profile_image} alt="" className="user-img profile_image" />
                                                <div>
                                                    <p className="name">{rep.therapist_fname + ' ' + rep.therapist_lname}</p>

                                                    <div className="icon-box">
                                                        <span className="icon"><img src={date_time} alt="" /></span>
                                                        <p className="mb-0">
                                                        {rep.date} | <br />
                                                                {rep.session_time} IST
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div className="row align-items-center">
                                            <div className="col-12">
                                                <a href="dashboardtherapists" className="btn gradient-btn">Re-Book Session</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                </div>
                                </div>
                                }}})}
                          
                           
                               
                            
                        </div>
                    </div>
                </div>
            </div>


        </div>


    </div>
</section></div>
  )
}

export default Appointments