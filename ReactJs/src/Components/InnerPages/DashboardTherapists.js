import React, { useEffect, useState } from 'react';
import axios from "axios";
//import { Link } from 'react-router-dom';
import Swal from 'sweetalert2';
import './DashboardTherapists.css';
import Polygon1 from '../../assets/images/Polygon 1.svg';
import Polygon2 from '../../assets/images/Polygon 2.svg';
/* import user1 from '../../assets/images/user1.png'; */
import language from '../../assets/images/language.svg';
import experience_icon from '../../assets/images/experience_icon.svg';
import expertiseicon from '../../assets/images/expertise-icon.svg';
import $ from 'jquery';
import globeVar from '../../GlobeApi';


const DashboardTherapists = () => {
    /* var data = sessionStorage.getItem('user');
        data = JSON.parse(data); */
        /* const token = data.token ; */
        
        const [word, setWord] = useState([])
        const [results, setResults] = useState([]);
        const defineWord = () => {
          fetch(globeVar+'therapist')
          
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


        const [searchTerm, setSearchTerm] = useState("");
        const inputEvent = (event) => {
        const data =  event.target.value;
        console.log(data);
        setSearchTerm(data);
    }


    const handleCheck = (e) => {
        var language = [];

        var therapyType = [];

        var order_by = "";
     
          $("input[name='therapyType']:checked").each(function() {
           therapyType.push($(this).val());
          });
      
           $("input[name='languages']:checked").each(function() {
               language.push($(this).val());
           });
           
           order_by= e;
           console.warn(language, therapyType, order_by);
           let item = {  language:language, therapyType:therapyType, order_by:order_by }; 
           axios.post( globeVar+"therapist/fiter",item).then((response)=>{
           if(response.data.success === 1)
           {
                   console.log(response.data.data);
                   setWord(response.data.data);
           }else{
               alert("something is wrong");
           }
       })

   };

   const [visible, setVisible] = useState(3);
   
    const showMoreTherapist = () => {
        let therapist_number = word.length;
        const currentLimit = visible;
      
     
       if(currentLimit < therapist_number){
           setVisible((prevValue) => prevValue + 3 );
      
       }else{
           $('#loadMore').hide();
       }
       
   }; 

        const [clocks, setClocks] = useState([])

        const fetchData = (id,hourly_price) => {
            if(id){
            sessionStorage.setItem('session_price',(hourly_price));
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
                            text: 'Please Select Other Date '
                        })
                        }
                        }) 
                    }   
                } 
        
        
        
        useEffect(() => {
          defineWord();
          fetchData();
          fetchTime();
         
        },[])
     
        useEffect(() => {
            const filtered = word.filter((value) => {
             
                if (searchTerm.length < 3) {
                  return value;
                } else if (
                  value.f_name.toLowerCase().includes(searchTerm.toLowerCase()) || value.language.toLowerCase().includes(searchTerm.toLowerCase()) || value.therapist_type.toLowerCase().includes(searchTerm.toLowerCase()) || value.l_name.toLowerCase().includes(searchTerm.toLowerCase())
                ) {
                    return value;
                } else if(!value){
                    return <span>NO</span>
                }
                
              })
            setResults(filtered)
          },[searchTerm, word])
        
  return (
    <div>
          
          <section className="top-mob-bg">
    <div className="container section-padding pb-0">

    <div className=" mb-3 mb-md-4">
            <div className="align-items-center row">
                <h3 className="col mb--4 mb-md-0 title d-none d-md-block">Find A ShivYog Therapist</h3>
                <div className="col-12 col-md-6 col-lg-4">
                    <div className="search-box">
                        <input type="text" className="form-control search-input" value={searchTerm} onChange={inputEvent} placeholder="Search for Therapists . . ." />
                    </div>
                </div>
            </div>
        </div>
        <div className="full-badge-btn filter-box mb-md-5">
            <div className="align-items-center form-row">
                <p className="col-auto mb-0 color-txt">Filter Search</p>
                <div className="col-auto  ">
                    <div className="dropdown">
                    <a className="btn dropdown-btn dropdown-toggle w-100 text-left" href="#" id="languageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span className="user-name">Language</span>
                    </a>
                    <div className="dropdown-menu click-open" aria-labelledby="languageDropdown">
                    <div className="dropdown-box px-2">
                            <div className="checkbox">
                                <input value="English" type="checkbox" onChange={(e)=>handleCheck()} name="languages" id="lang_english" />
                                <label  for="lang_english">English</label>
                            </div>
                            <div className="checkbox">
                                <input value="Hindi" type="checkbox" onChange={(e)=>handleCheck()} name="languages" id="lang_hindi" />
                                <label for="lang_hindi">Hindi</label>
                            </div>
                            <div className="checkbox">
                                <input value="French" type="checkbox" onChange={(e)=>handleCheck()} name="languages" id="lang_french" />
                                <label for="lang_french">French</label> 
                            </div> 
                        </div>
                    </div>
                    </div>
                </div>
                <div className="col col-md-auto  ">
                    <div className="dropdown">
                    <a className="btn dropdown-btn dropdown-toggle w-100 text-left" href="#" id="therapyTypeDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span className="user-name">Type of Therapist</span>
                    </a>
                    <div className="dropdown-menu click-open" aria-labelledby="therapyTypeDropdown">
                    <div className="dropdown-box px-2">
                            <div className="checkbox">
                                <input type="checkbox" value="ShivYog Master Cosmic Therapist" onChange={(e)=>handleCheck()} name="therapyType" id="therapyType1" />
                                <label for="therapyType1">ShivYog Master Cosmic Therapist</label>
                            </div>
                            <div className="checkbox">
                                <input type="checkbox" value="ShivYog Master Cognitive Therapist" onChange={(e)=>handleCheck()} name="therapyType" id="therapyType2" />
                                <label for="therapyType2">ShivYog Master Cognitive Therapist</label>
                            </div>
                            <div className="checkbox">
                                <input type="checkbox" value="ShivYog Cosmic Therapist" onChange={(e)=>handleCheck()} name="therapyType" id="therapyType3" />
                                <label for="therapyType3">ShivYog Cosmic Therapist</label>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div className="col col-md-auto  ">
                    <div className="dropdown">
                    <a className="btn dropdown-btn dropdown-toggle w-100 text-left" href="#" id="signupDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span className="user-name">Filter Therapist By</span>
                    </a>
                    <div className="dropdown-menu" aria-labelledby="signupDropdown">
                        <div className="dropdown-box">
                        <a className="dropdown-item" onClick={(e)=>handleCheck("Years of ShivYog Therapy Experience")} >Years of ShivYog Therapy Experience</a>
                            <a className="dropdown-item" onClick={(e)=>handleCheck("Later Date Availability")}>Later Date Availability</a>
                            <a className="dropdown-item" onClick={(e)=>handleCheck("Fee Low To High")} >Fee – Low To High</a>
                            <a className="dropdown-item" onClick={(e)=>handleCheck("Fee High To Low")}>Fee – High To Low</a>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
 </section>

 <section>
    <div className="container section-padding pt-0">



        <div className="form-row" id="accordionExample">

      {results.length > 0 ? results
          .slice(0,visible)
          .map(item => {
         
        return <div key={item.id} >
            <div className="col-12 col-sm-12 col-md-12" >
                <div className="therapy_box bg-pink">
                    <div className="top" id="headingOne">
                        <div className="row">
                            <p className="col therapy_name">
                            {item.therapist_type}</p>

                            <div className="col-auto">
                                <div className="d-flex align-items-center">
                                    <p className="d-inline-block mb-0 mr-3">
                                        <img src={Polygon1} alt="" />
                                        <img src={Polygon1} alt="" />
                                        <img src={Polygon1} alt="" />
                                        <img src={Polygon1} alt="" />
                                        <img src={Polygon2} alt="" />
                                        4/5
                                    </p>
                                    <span className="badge hour-badge float-right">${item.hourly_price}/hr</span>
                                </div>
                            </div>

                        </div>
                        <div className="row">
                            <div className="user-box col">
                                <img src={item.profile_image} alt="" className="profile_image" />
                                <p className="name">{item.f_name} {item.l_name}</p>
                            </div>
                            <div className="col-auto">
                                <div className="icon-box">
                                    <span className="icon tooltip-bottom" data-tooltip="Languages Therapist Speaks">
                                        <img src={language} alt="" />
                                    </span>
                                    <p className="mb-0"> <span className="title">LANGUAGES</span>
                                    {item.language}

                                    </p>
                                </div>
                            </div>

                            <div className="col">
                                <div className="icon-box">
                                    <span className="icon tooltip-bottom" data-tooltip="Years of Experience"><img src={experience_icon} alt="" /></span>
                                    <p className="mb-0"> <span className="title">EXPERIENCE</span>
                                    {item.experience}
                                    </p>
                                </div>
                            </div>

                            <div className="col">
                                <div className="icon-box">
                                    <span className="icon tooltip-bottom" data-tooltip="Expertise"><img src={expertiseicon} alt="" /></span>
                                    <p className="mb-0"> <span className="title">EXPERTISE</span>
                                    {item.expertise[0]},<br/>
                                    {item.expertise[1]}
                                    </p>
                                </div>
                            </div>
                        
                            <div className="col-auto">
                                <a href="#" id={'hide_session_'+item.user_id} onClick={() => fetchData(item.user_id,item.hourly_price)} className="btn gradient-btn collapsed hide_session" data-toggle="collapse" data-target={"#collapseOne"+ item.user_id} aria-expanded="true" aria-controls="collapseOne">Book A Session</a>
                                <a  href='/payment'  id={'hide_book_'+item.user_id} style={{display: 'none'}} className="btn gradient-btn booking">Book</a>
                            </div>
                            
                        </div>

                    </div>
                    <div id={"collapseOne" + item.user_id}  className="collapse session-book-box" aria-labelledby="headingOne" data-parent="#accordionExample">
                      
                    <div className="card-body" >
                            
                            <div className="session-scroller">
                           <span className="arrow left-arrow"><i className="fa fa-angle-left"></i></span>
                           <div className="session-dates" id="foo">
                              
                           
                           {clocks.map(items => {
                               if(item.user_id === items.therapist_id){
                                 if(date.toISOString().substr(0, 10) <= items.schedule_date){ 
                               return <a onClick={() => fetchTime(items.therapist_id,items.schedule_date)} id={"schedule_date_"+items.therapist_id+"_"+items.schedule_date} className="schedule_date" key={items.id}>{items.date}</a>
                            }}
                        })}  
                           </div>
                           <span className="arrow right-arrow"><i className="fa fa-angle-right"></i></span>
                       </div>
                      
                       <div className="time-boxes">
                       
                       <input type="hidden" id='booking_time'  name="booking_time"  value=""/>
                        <input type="hidden" id='schedule_date'  name="schedule_date"  value={time.schedule_date}/>
                        <input type="hidden" id='therapist_id'  name="therapist_id"  value={time.therapist_id}/>
                        <input type="hidden" id='schedule_time_id'  name="schedule_time_id"  value=""/>
                        {time.map((session,i) =>{
                            sessionStorage.setItem('therapist_id',time.therapist_id);
                            sessionStorage.setItem('schedule_date',time.schedule_date);
                             
                            if(item.user_id === time.therapist_id){
                                
                             return <a id={"myDIV_"+time.therapist_id+"_"+i } className="time-period" onClick={() => hidesession(i,session,time.therapist_id,time.schedule_time_id)} ><span id={time.schedule_time_id[i]}>{session}</span></a>
                                
                              }})}

                            </div>
                
                            </div>
                        </div> 
                    </div>

                </div> 
           </div>;
         }): <p id='result_found'>No Results Found</p>}


            <div className="col-12 text-center">
            <a className="btn border-btn w-auto px-3" id="loadMore" onClick={() => showMoreTherapist()} >
                VIEW MORE
            </a>
        </div>  
        </div>
    </div>
 </section></div>
  )
}

function hidesession(id,session,therapist_id,schedule_time_id){

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
  
  /* $("#session_price").val(hourly_price);
  sessionStorage.setItem('session_price',(hourly_price));
 */
   if(hd === false){

    element.classList.add("active");
   
    }else{

    element.classList.remove("active");
   
}

   var elementh = document.getElementById("myDIV_"+therapist_id+"_"+id);
   elementh.classList.add("active");

  

   // $('.time-period').removeClass("active");

    
}

/* $(function() {
    $('#hide-book').hide();
 }); */


    
export default DashboardTherapists;