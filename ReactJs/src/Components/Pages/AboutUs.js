import React, {useState, useEffect} from 'react';
import about1 from '../../assets/images/about1.png';
import wave from '../../assets/images/wave.svg';
import dravdhoot from '../../assets/images/dr-avdhoot.jpg';
import ishan from '../../assets/images/ishan.jpg';
import axios from "axios";
import globeVar from '../../GlobeApi';
const AboutUs = () => {
    const [cms, setCms] = useState([]);
    const [cms_id, setCms_id] = useState([]);
    
    const cms_of_aboutus = () => {
        fetch(globeVar+'cms')
      
        .then(response => response.json())
        .then(data => {
          console.log(data);
          setCms(data.data)
          console.log(data.data);              
        })
        .catch(error => {
          console.log(error)
        })
    }

    /* const [cms_byid, setCms_byid] = useState('');
    const cms_of_aboutus_byid = (cms) => {
        axios.get( globeVar+`cms/${id}`).then((response)=>{
            if(response.data.success === 1)
            {
                    console.log(response.data.data);
                    setCms_byid(response.data.data);
            }else{
                alert("something is wrong");
            }
        })
    } */
    useEffect(() => {
        cms_of_aboutus();
        /* cms_of_aboutus_byid(); */
    }, [])
  

  return (
    <div><section className="breadcrumb-box">
    <div className="container">
        <div className="row align-items-center">
            <div className="col-12 col-sm-auto">
                <h1>ABOUT US</h1>
            </div>
        </div>
    </div>
</section>

<section>
<div className="container section-padding">

   
<div className="flex-column-reverse flex-sm-row mb-5 row">
        <div className="col-12 div col-sm-6 col-md-6">
    <h2 className="title mb-4">WHAT IS SHIVYOG<br />COSMIC THERAPY
    </h2>
    <p>“In ancient India, the great Saints, the Himalayan Monks through the practice of these ancient yogic techniques unraveled the secrets of the Universe and in doing so, they developed a mastery over their own bodies, their psychologies and their own self. Such a mastery enabled them to command their cellular biology and the legend is if they so wished it, they had the power of immortality.</p>
    <p>This elixir of immortality has many a times been written in legends in the form of amrit or the ponds of Shangri-La whereas if this nectar is partaken, such a person shall forever be above the rest of humanity and remain immortal. But in reality, it is not a drink to be drunk. It is a nectar of knowledge.”  <span className="color-txt">Ishan Shivanand</span></p>


        </div>
        
        <div className="col-12 div col-sm-6 col-md-6 mb-4 mb-sm-0">
            <div className="position-relative">
            <img src={about1} alt="" className="radius-shadow-img img-fluid" />
            <img src={wave} alt="" className="wave-leftImg" />
            </div>
        </div>
     
    </div>
</div>
</section>

<section  style={{backgroundColor: '#FFF0DF'}}>
<div className="container section-padding">
<h2 className="title mb-4 pb-3 text-center">FOUNDERS</h2>
    <div className="row align-items-center mb-5">
        <div className="col-12 div col-sm-6 col-md-6 mb-4 mb-sm-0">
            <img src={dravdhoot} alt="" className="radius-shadow-img img-fluid" />
        </div>
        <div className="col-12 div col-sm-6 col-md-6 ">
    <h2 className="title mb-2">
    DR. AVDHOOT SHIVANAND
    </h2>
    <p className="color-txt weight-600">FOUNDER | SPIRITUAL SCIENTIST | HIMALAYAN YOGI</p>
    <p>Dr. Avdhoot Shivanand is a celebrated Himalayan Yogi, Spiritual Scientist and Social Reformer from India. He has been recognized the world over for his contributions to important areas of concern for human societies including medicine, education, nutrition, agriculture,horticulture, and environment as well restoration of family values.</p>
    

        </div>
    </div>
    <div className="flex-column-reverse flex-sm-row row align-items-center">
        <div className="col-12 div col-sm-6 col-md-6 ">
    <h2 className="title mb-2">
    ISHAN SHIVANAND
    </h2>
    <p className="color-txt weight-600">FOUNDER | MONK | VISIONARY</p>
    <p>Born into a highly accomplished spiritual family, Ishan was put through rigorous physical training and many years of deep transformative meditation. Over time, Ishan evolved into a transformative leader with astounding insights about life, spirituality and success. Today, Ishan is on a mission to bring the wisdom of the Himalayan yogis to those who seek purpose, meaning, and success in their lives. Ishan is on a mission to unlock the secrets of the ancient, esoteric sciences to help people find happiness, good health and abundance in their lives, raising human consciousness, productivity, and performance and transforming people from the inside out!</p>
    

        </div>
        <div className="col-12 div col-sm-6 col-md-6 mb-4 mb-sm-0">
            <img src={ishan} alt="" className="radius-shadow-img img-fluid" />
        </div>
    </div>
</div>
</section></div>
  )
}

export default AboutUs;