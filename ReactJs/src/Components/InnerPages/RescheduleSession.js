import React, { useEffect, useState } from 'react';
import Polygon1 from '../../assets/images/Polygon 1.svg';
import Polygon2 from '../../assets/images/Polygon 2.svg';
import './DashboardTherapists.css';
import language from '../../assets/images/language.svg';
import experience_icon from '../../assets/images/experience_icon.svg';
import expertiseicon from '../../assets/images/expertise-icon.svg';
import globeVar from '../../GlobeApi';
import $ from 'jquery';
import Swal from 'sweetalert2';
import axios from "axios";

function RescheduleSession() {
    
    const [word, setWord] = useState([])

    const defineWord = () => {
        const therapist_id = sessionStorage.getItem("appointment_therapist_id");
       
      fetch(globeVar+`therapist/${therapist_id}`)
      
        .then(response => response.json())
        .then(data => {
          console.log(data);
          setWord(data.data)
          console.log(data.data);              
        })
        .catch(error => {
          console.log(error)
        })
    }

    const [clocks, setClocks] = useState([])

    const fetchData = (id) => {
        if(id){
       
        return fetch(globeVar+`therapist/schedule_list/`+id)
              .then((response) => response.json())
              .then((data) => {console.log(data);
                if(data.data){
                    $(".booking").hide();
                    $(".hide_session").show();
                    setClocks(data.data)
                }

                console.log(data.data);
              }) 
              .catch(error => {
                console.log(error)
              })
            }}
            
            var date=new Date(); 
            date.setDate(date.getDate() + 1);

            const [time, setTime] = useState([])
    
            const fetchTime = (therapist_id,schedule_date) => {
                    if(schedule_date){
                        $(".schedule_date").removeClass("active");
                        $("#schedule_date_"+therapist_id+"_"+schedule_date).addClass("active");
                    let item = { "date":schedule_date, "therapist_id":therapist_id }; 
                    axios.post( globeVar+"therapist/schedule_list/get_schedule_by_date",item).then((response)=>{
                        
                    if(response.data.success === 1)
                    {
                    
                        
                         var myArray = response.data.data;
                         var myArray_q = '';
                         var myArray_qq = '';
                        var myArray_th = [];
                        var myArray_date = [];

                         myArray.forEach(rec =>{
                           var test_time = JSON.stringify(rec);
                            var test_time2 = JSON.parse(test_time);

                            myArray_q += test_time2.schedule_time  + ',';
                            myArray_qq += test_time2.id  + ',';
                            myArray_th = test_time2.therapist_id ;
                            myArray_date = test_time2.schedule_date;
                         
                        });
                        var schedule_timess = myArray_q.split(',');
                        var schedule_time = schedule_timess.slice(0, -1);
                        var timeArray = schedule_time;

                        var schedule_timesqs = myArray_qq.split(',');
                        var schedule_time_id = schedule_timesqs.slice(0, -1);
                        timeArray.schedule_time_id = schedule_time_id;

                        timeArray.therapist_id = myArray_th;
                        timeArray.schedule_date = myArray_date; 
                        console.log(timeArray);
                        setTime(timeArray);

                    }else{
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please select date '
                    })
                    }
                    }) 
                }   
            } 
    useEffect(() => {
        defineWord();
       
       
      },[])
   
  return (
    <div>
      <section className="breadcrumb-box d-none d-md-block">
    <div className="container">
        <div className="row align-items-center">
            <div className="col-12 col-sm-auto">
                <h1>SHIVYOG COSMIC THERAPISTS</h1>
            </div>
           
        </div>
    </div>
</section>

<section className="top-mob-bg">
    <div className="container section-padding pb-0">

    <div className=" mb-3 mb-md-4">
            <div className="align-items-center row">
                <h3 className="col mb--4 mb-md-0 title d-none d-md-block">Reschedule ShivYog Therapist</h3>
               
            </div>
        </div>
      

    </div>
</section>
<section>
<div class="container section-padding pt-0">




<div class="form-row" id="accordionExample">
    <div class="col-12 col-sm-12 col-md-12">
        <div class="therapy_box bg-pink">
            <div class="top" id="headingOne">
                <div class="row">
                    <p class="col therapy_name">
                        {word.therapist_type}</p>

                    <div class="col-auto">
                        <div class="d-flex align-items-center">
                            <p class="d-inline-block mb-0 mr-3">
                                <img src={Polygon1} alt="" />
                                <img src={Polygon1} alt="" />
                                <img src={Polygon1} alt="" />
                                <img src={Polygon1} alt="" />
                                <img src={Polygon2} alt="" />
                                4/5
                            </p>
                            <span class="badge hour-badge float-right">${word.hourly_price}/hr</span>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="user-box col">
                        <img src={word.profile_image} alt="" class="user-img profile_image" />
                        <p class="name">{word.f_name + ' ' + word.l_name}</p>
                    </div>
                    <div class="col-auto">
                        <div class="icon-box">
                            <span class="icon tooltip-bottom" data-tooltip="Languages Therapist Speaks">
                                <img src={language} alt="" />
                            </span>
                            <p class="mb-0"> <span class="title">LANGUAGES</span>
                                {word.language}

                            </p>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-box">
                            <span class="icon tooltip-bottom" data-tooltip="Years of Experience"><img src={experience_icon} alt="" /></span>
                            <p class="mb-0"> <span class="title">EXPERIENCE</span>
                                Practicing Shiv Yog since <span class="color-txt">4</span>yrs Practicing Shiv Yog Therapy since <span class="color-txt">4</span>yrs
                            </p>
                        </div>
                    </div>

                    <div class="col">
                        <div class="icon-box">
                            <span class="icon tooltip-bottom" data-tooltip="Expertise"><img src={expertiseicon} alt="" /></span>
                            <p class="mb-0"> <span class="title">EXPERTISE</span>
                            ShivYog Cosmic Therapy
                            </p>
                        </div>
                    </div>

                    <div className="col-auto">
                        <a href="#" id={'hide_session_'+word.user_id}  onClick={() => fetchData(word.user_id)}  className="btn gradient-btn collapsed hide_session" data-toggle="collapse" data-target={"#collapseOne"+ word.user_id} aria-expanded="true" aria-controls="collapseOne">Book A Session</a>
                        <a  href='/rescheduleconfirmpayment'  id={'hide_book_'+word.user_id} style={{display: 'none'}} className="btn gradient-btn booking">Book</a>
                    </div>
                </div>

            </div>
            <div id={"collapseOne" + word.user_id}  className="collapse session-book-box" aria-labelledby="headingOne" data-parent="#accordionExample">
                <div class="card-body">


                    <div class="session-scroller">
                        <span class="arrow left-arrow"><i class="fa fa-angle-left"></i></span>
                        <div class="session-dates" id="foo">
                        {clocks.map(items => {
                               if(word.user_id === items.therapist_id){
                                 if(date.toISOString().substr(0, 10) <= items.schedule_date){ 
                               return <a onClick={() => fetchTime(items.therapist_id,items.schedule_date)} id={"schedule_date_"+items.therapist_id+"_"+items.schedule_date} className="schedule_date" key={items.id}>{items.date}</a>
                            }}
                        })}  
                        </div>
                        <span class="arrow right-arrow"><i class="fa fa-angle-right"></i></span>
                    </div>

                    <div class="time-boxes">
                    <input type="hidden" id='booking_time'  name="booking_time"  value=""/>
                        <input type="hidden" id='schedule_date'  name="schedule_date"  value={time.schedule_date}/>
                        <input type="hidden" id='therapist_id'  name="therapist_id"  value={time.therapist_id}/>
                        <input type="hidden" id='schedule_time_id'  name="schedule_time_id"  value=""/>
                        {time.map((session,i) =>{
                            sessionStorage.setItem('rescheduleconfirmtherapist_id',time.therapist_id);
                            sessionStorage.setItem('schedule_date',time.schedule_date);
                             
                            if(word.user_id === time.therapist_id){
                             
                             return <a id={"myDIV_"+time.therapist_id+"_"+i } className="time-period" onClick={() => hidesession(i,session,time.therapist_id, time.schedule_time_id)} ><span id={time.schedule_time_id[i]}>{session}</span></a>
                                
                              }})}
                    </div>

                </div>
            </div>
        </div>

    </div>
    </div>
      

      </div>
  </section>
  </div>
  )
}

function hidesession(id,session,therapist_id, schedule_time_id){

    $(".booking").hide();
    $(".hide_session").show();
    $("#hide_session_"+therapist_id).hide();
    $("#hide_book_"+therapist_id).show();
    
    
    
    var element = document.getElementById("myDIV_"+therapist_id+"_"+id);

   $('.time-period').removeClass("active");
   var hd = document.getElementById("myDIV_"+therapist_id+"_"+id).classList.contains("active");

   $("#booking_time").val(session);

  sessionStorage.setItem('schedule_time',(session));
  
  $("#schedule_time_id").val(schedule_time_id[id]);
  sessionStorage.setItem('schedule_time_id',(schedule_time_id[id]));

   if(hd === false){

    element.classList.add("active");
   
    }else{

    element.classList.remove("active");
   
}

   var elementh = document.getElementById("myDIV_"+therapist_id+"_"+id);
   elementh.classList.add("active");

  

   // $('.time-period').removeClass("active");

    
}
export default RescheduleSession
