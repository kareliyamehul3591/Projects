import React, { useEffect, useState } from 'react';
import Polygon1 from '../../assets/images/Polygon 1.svg';
import Polygon2 from '../../assets/images/Polygon 2.svg';
import date_time from '../../assets/images/date_time.svg';
import language from '../../assets/images/language.svg';
import experience_icon from '../../assets/images/experience_icon.svg';
import expertiseicon from '../../assets/images/expertise-icon.svg';
import globeVar from '../../GlobeApi';
import axios from "axios";

const Dashboard = () => {
    const maxDate=new Date(); 
    let today = new Date();
    //let date = today.getFullYear() + '-' + parseInt(today.getMonth() + 1) + '-' + today.getDate()
    var date=new Date(); 
    date.setDate(date.getDate() + 1);


     console.log(maxDate);

    const [dash, setDash] = useState([])

    const dash_therapist = () => {
      fetch(globeVar+'therapist')
      
        .then(response => response.json())
        .then(data => {
          console.log(data);
          setDash(data.data)
          console.log(data.data);              
        })
        .catch(error => {
          console.log(error)
        })
    }

    const [searchTerm, setSearchTerm] = useState("");
    const inputEvent = (event) => {
        const data =  event.target.value;
        console.log(data);
        setSearchTerm(data);
    }

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
                            sessionStorage.setItem("reschedual_session", JSON.stringify(myArray))
                            

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
        dash_therapist();
        booked();
       
      },[])
   
  return (
    <div><section className="mb-4 mb-md-5">
    <div className="container pb-0 section-padding">
    
    <div className="">
        <div className="align-items-center row">
            <h3 className="col mb--4 mb-md-0 title">My Dashboard</h3>
            <div className="col-12 col-md-6 col-lg-4">
                <div className="search-box">
                    <input type="text" className="form-control search-input"  value={searchTerm} onChange={inputEvent} placeholder="Search for Therapists . . ." />
                </div>
            </div>
        </div>
    </div>
    </div>
    </section>
    <section>
        <div className="container section-padding pt-0">    
    
            <div className="form-row">
                <div className="col-12 col-sm-12 col-lg-6">
                    <div className="dashboard-column">
                        <div className="top-color-box">
                            <div className="align-items-center row">
                                <h3 className="col mb-0 title">Earliest Available Therapist</h3>
                                <div className="col-auto"><a href="dashboardtherapists" className="color-txt weight-600">View All</a></div>
                            </div>
                        </div>
                        {dash.slice(0,2).filter((value) => {
            if (searchTerm === "") {
              return value;
            } else if (
              value.f_name.toLowerCase().includes(searchTerm.toLowerCase()) 
            ) {
              return value;
            }
          }).map((item,i) => {
                                return<div key={i}>
                    <div className="therapy_box">
                        <div className="top">
                            
                            <div className="row">
                                <p className="col therapy_name">
                                    {item.therapist_type}</p>
    
                                <div className="col-auto">
                                    <div className="d-flex align-items-center">
                                        <span className="badge hour-badge float-right">${item.hourly_price}/hr</span>
                                    </div>
                                </div>
    
                            </div>
                           
                            <div className="row">
                                <div className="user-box col">
                                    <img src={item.profile_image} alt="" className="user-img profile_image" />
                                    <p className="name">{item.f_name + " " + item.l_name}</p>
                                </div>
                                <div className="col-auto">
                                    
                                <p className="d-inline-block mb-0 mr-3">
                                            <img src={Polygon1} alt="" />
                                            <img src={Polygon1} alt="" />
                                            <img src={Polygon1} alt="" />
                                            <img src={Polygon1} alt="" />
                                            <img src={Polygon2} alt="" />
                                            4/5
                                        </p>
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-auto">
                                    <div className="icon-box">
                                        <span className="icon"><img src={language} alt="" /></span>
                                        <p className="mb-0"> <span className="title">LANGUAGES</span>
                                        {item.language}
    
                                        </p>
                                    </div>
                                </div>
    
                                <div className="col">
                                    <div className="icon-box">
                                        <span className="icon"><img src={experience_icon} alt="" /></span>
                                        <p className="mb-0"> <span className="title">EXPERIENCE</span>
                                        {item.experience}
                                        </p>
                                    </div>
                                </div>
    
                                <div className="col">
                                    <div className="icon-box">
                                        <span className="icon"><img src={expertiseicon} alt="" /></span>
                                        <p className="mb-0"> <span className="title">EXPERTISE</span>
                                        {item.expertise[0]},  {item.expertise[1]}
                                        </p>
                                    </div>
                                </div>
    
                               
                            </div>
                            
                            <div className="row">
                            <div className="col-auto">
                                    <a href="dashboardtherapists" className="btn gradient-btn">Book A Session</a>
                                </div>
                            </div>
                
                        </div>
                        
                    </div>
                    </div>
})}
                 
                    </div>
                </div>
    
                <div className="col-12 col-sm-12 col-lg-6">
                    <div className="dashboard-column">
                        <div className="top-color-box">
                            <div className="align-items-center row">
                                <h3 className="col mb-0 title">Upcoming Appointments</h3>
                                <div className="col-auto"><a href="appointments" className="color-txt weight-600">View All</a></div>
                            </div>
                        </div>

                        {bookInfo.slice(0,2).map(rep =>{if(rep.session_date >= date.toISOString().substr(0, 10)){
                        
                            if(rep.status === "1"){
                                    return<div key={rep.user_id} >
                    <div className="therapy_box">
                        <div className="top">
                            <div className="row">
                                <p className="col therapy_name">
                                {rep.therapist_type}</p>
    
                                <div className="col-auto">
                                    <div className="d-flex align-items-center">
                                        <span className="badge hour-badge float-right"> {rep.differentDays}Days, {rep.hours}Hrs &amp; {rep.mins}Min Remaining </span>
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
                                        {rep.date} | {rep.session_time} IST
                                        </p>
                                    </div>
                                    </div>
                                </div>
                                
                            </div>
                            
                            
                            <div className="row align-items-center">
                            <div className="col-auto">
                                    <a onClick={()=>get_link(rep.id)} className="btn gradient-btn">Begin A Session</a>
                                </div>
                            <div className="col-auto">
                                    <a onClick={() => re_appointment(rep.id, rep.therapist_id,rep.session_time,rep.session_date)}  className="link-bold-color">Reschedule Session</a>
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
    </section></div>
  )
}

export default Dashboard;