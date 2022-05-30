import React, { useState, useEffect} from 'react'
import Polygon1 from '../../assets/images/Polygon 1.svg';
import Polygon2 from '../../assets/images/Polygon 2.svg';
import './Therapist.css';

import language from '../../assets/images/language.svg';
import experience_icon from '../../assets/images/experience_icon.svg';
import expertiseicon from '../../assets/images/expertise-icon.svg';
import globeVar from '../../GlobeApi';
import $ from 'jquery';
import Swal from 'sweetalert2';
import axios from "axios";
const TherapistbyLanguage = () => {
    
    const [checked1, setChecked1] = useState([]);
    const lang = ()=> {
        var data = sessionStorage.getItem('lan');
            data = JSON.parse(data);
    let item = { "language": data }; 
    axios.post( globeVar+"therapist/fiter",item).then((response)=>{
    if(response.data.success === 1)
    {
            console.log(response.data.data);
            setChecked1(response.data.data);
    }else{
        alert("something is wrong");
    }
})
};

    const handleCheck = () => {

        var test = [];
      /*  if (checked) { */
           $("input[name='languages']:checked").each(function() {
               test.push($(this).val());
           });
           console.warn(test);
               sessionStorage.setItem('language', JSON.stringify(test));
               window.location.href = "/therapistbylanguage"

   };
     
useEffect(() => {
    lang();
}, [])
return (
    <div><section className="breadcrumb-box d-none d-md-block">
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
                <h3 className="col mb--4 mb-md-0 title d-none d-md-block">Find A ShivYog Therapist</h3>
                <div className="col-12 col-md-6 col-lg-4">
                    <div className="search-box">
                        <input type="text" className="form-control search-input"  placeholder="Search for Therapists . . ." />
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
                                <input value="English" type="checkbox" onChange={handleCheck} name="languages" id="lang_english" />
                                <label  for="lang_english">English</label>
                            </div>
                            <div className="checkbox">
                                <input value="Hindi" type="checkbox" onChange={handleCheck} name="languages" id="lang_hindi" />
                                <label for="lang_hindi">Hindi</label>
                            </div>
                            <div className="checkbox">
                                <input value="French" type="checkbox" onChange={handleCheck} name="languages" id="lang_french" />
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
                                <input type="checkbox" name="" id="therapyType1" />
                                <label for="therapyType1">ShivYog Master Cosmic Therapist</label>
                            </div>
                            <div className="checkbox">
                                <input type="checkbox" name="" id="therapyType2" />
                                <label for="therapyType2">ShivYog Master Cognitive Therapist</label>
                            </div>
                            <div className="checkbox">
                                <input type="checkbox" name="" id="therapyType3" />
                                <label for="therapyType3">ShivYog Therapist</label>
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
                            <a className="dropdown-item" href="javascript:void(0)">Years of ShivYog Therapy Experience</a>
                            <a className="dropdown-item" href="javascript:void(0)">Earliest Available</a>
                            <a className="dropdown-item" href="javascript:void(0)">Later Date Availability</a>
                            <a className="dropdown-item" href="javascript:void(0)">Fee – Low To High</a>
                            <a className="dropdown-item" href="javascript:void(0)">Fee – High To Low</a>
                            <a className="dropdown-item" href="javascript:void(0)">By Rating</a>
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
        {checked1.map(item => {
         
         return <div key={item.id} >
            <div className="col-12 col-sm-12 col-md-12">
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
                                    <span className="badge hour-badge float-right">{item.hourly_price}/hr</span>
                                </div>
                            </div>

                        </div>
                        <div className="row">
                            <div className="user-box col">
                                <img src={item.profile_image} alt="" className="user-img profile_image" />
                                <p className="name-lg">{item.f_name + ' ' + item.l_name}</p>
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
                                    {item.expertise[0]}, {item.expertise[1]}
                                    </p>
                                </div>
                            </div>

                            <div className="col-auto">
                                <a href="#" id='hide_session'  className="btn gradient-btn collapsed hide_session" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Book A Session</a>
                                <a  href='/login'  id='hide_book' style={{display: 'none'}} className="btn gradient-btn booking">Book</a>
                            </div>
                            
                        </div>

                    </div>
                    <div id={"collapseOne" + item.user_id}  className="collapse session-book-box" aria-labelledby="headingOne" data-parent="#accordionExample">
                      
                        <div className="card-body">

                            <div className="form-row px-lg-5">
                                <div className="col-6 col-md mb-3">
                                <div className=" dropdown custom-dropdown-select">
                                    <a className="btn dropdown-btn dropdown-toggle" href="#" id="signupDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   <span className="value-assign">Select your Country</span>
                                    </a>
                                    <div className="dropdown-menu dropdown-menu-right" aria-labelledby="signupDropdown">
                                        <div className="dropdowm-box">
                                            <a className="dropdown-item" href="javascript:void(0)">NEW THERAPIST</a>
                                            <a className="dropdown-item" href="javascript:void(0)">PSYCHOLOGIST</a>
                                            <a className="dropdown-item" href="javascript:void(0)">COUNSELLOR</a>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div className="col-6 col-md mb-3">
                                <div className=" dropdown custom-dropdown-select">
                                    <a className="btn dropdown-btn dropdown-toggle" href="#" id="signupDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   <span className="value-assign">Select your State</span>
                                    </a>
                                    <div className="dropdown-menu dropdown-menu-right" aria-labelledby="signupDropdown">
                                        <div className="dropdowm-box">
                                            <a className="dropdown-item" href="javascript:void(0)">NEW THERAPIST</a>
                                            <a className="dropdown-item" href="javascript:void(0)">PSYCHOLOGIST</a>
                                            <a className="dropdown-item" href="javascript:void(0)">COUNSELLOR</a>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <div className="col-md-5 mb-3">
                                <div className=" dropdown custom-dropdown-select">
                                    <a className="btn dropdown-btn dropdown-toggle" href="#" id="signupDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    What’s your time zone <span className="mx-2">|</span> <span className="value-assign">IST (India Time)</span>
                                    </a>
                                    <div className="dropdown-menu dropdown-menu-right" aria-labelledby="signupDropdown">
                                        <div className="dropdowm-box">
                                            <a className="dropdown-item" href="javascript:void(0)">NEW THERAPIST</a>
                                            <a className="dropdown-item" href="javascript:void(0)">PSYCHOLOGIST</a>
                                            <a className="dropdown-item" href="javascript:void(0)">COUNSELLOR</a>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>

                       

                        </div>
                    </div>
                </div>

            </div>
            </div>;
         })}

        </div>
    </div>
</section></div>
  )
}

function hidesession(id,session,therapist_id){

    $(".booking").hide();
    $(".hide_session").show();
    $("#hide_session_"+therapist_id).hide();
    $("#hide_book_"+therapist_id).show();
    
    
    
    var element = document.getElementById("myDIV_"+therapist_id+"_"+id);

   $('.time-period').removeClass("active");
   var hd = document.getElementById("myDIV_"+therapist_id+"_"+id).classList.contains("active");

   $("#booking_time").val(session);

  sessionStorage.setItem('schedule_time',(session));
  


   if(hd === false){

    element.classList.add("active");
   
    }else{

    element.classList.remove("active");
   
}

   var elementh = document.getElementById("myDIV_"+therapist_id+"_"+id);
   elementh.classList.add("active");
}

export default TherapistbyLanguage