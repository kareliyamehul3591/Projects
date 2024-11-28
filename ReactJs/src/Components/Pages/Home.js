import React, {useState, useEffect} from 'react';
import OwlCarousel from "react-owl-carousel";
import "owl.carousel/dist/assets/owl.carousel.css";
import "owl.carousel/dist/assets/owl.theme.default.css";
import './Home.css';  
import about1 from '../../assets/images/about1.png';
import gallery from '../../assets/images/gallery.svg';
import wave from '../../assets/images/wave.svg';
import circle from '../../assets/images/circle.svg';
import axios from 'axios';
import globeVar from '../../GlobeApi';
import { Link } from 'react-router-dom';

const Home = () => { 
    const [img, setImg] = useState([])
    const img_slider = () => {
        axios.get( globeVar+"image_slider").then((response)=>{
             if(response.data.success === 1)
        {
            console.log(response.data.data); 
            setImg(response.data.data); 
               }      
        })
       };

       const [storyimg, setStoryimg] = useState([])
    const success_img_slider = () => {
        axios.get( globeVar+"success_image_slider").then((response)=>{
             if(response.data.success === 1)
        {
            console.log(response.data.data); 
            setStoryimg(response.data.data); 
               }      
        })
       };
       const [storyimgs, setStoryimgs] = useState([])
       const success_img_sliders = () => {
           axios.get( globeVar+"success_image_slider").then((response)=>{
                if(response.data.success === 1)
           {
               console.log(response.data.data); 
               setStoryimgs(response.data.data); 
                  }      
           })
          };

       useEffect(() => {
        img_slider();
        success_img_slider();
        success_img_sliders();
       }, [])
      return (
    <div>
        <section>
        <div className='container-fluid' >   
          <OwlCarousel  items={1} margin={8} loop autoplay ={true} >
            {img.map(isa =>{
            return<div className="item" key={isa.title}>
                <img src={"http://localhost:8000/images/"+isa.images} className="w-100 banner-img" height="405px" />
                <div className="container banner-text">
                    <h2>{isa.content}</h2>
                </div>
            </div>
        })} 
      </OwlCarousel>  
      </div>  
     
    
</section>
<section>
<div className="container section-padding">
<div className="full-badge-btn home-top-badge mb-5">
    <div className="align-items-center row">
        <h4 className="col-lg mb-3 mb-lg-0 title">Consult our top therapist to get perfect physical and mental health.</h4>
        <div className="col-12 col-lg-auto">
            <Link to={`/therapists`} className="accordion btn btn-regular px-3 w-auto">Find a ShivYog Therapist</Link>
        </div>
    </div>
</div>

<div className="flex-column-reverse flex-sm-row mb-5 row">
        <div className="col-12 div col-sm-6 col-md-6">
    <h2 className="title mb-4">WHAT IS SHIVYOG <br />
    COSMIC THERAPY
    </h2>
    <p>“In ancient India, the great Saints, the Himalayan Monks through the practice of these ancient yogic techniques unraveled the secrets of the Universe and in doing so, they developed a mastery over their own bodies, their psychologies and their own self. Such a mastery enabled them to command their cellular biology and the legend is if they so wished it, they had the power of immortality.</p>
    <p>This elixir of immortality has many a times been written in legends in the form of ‘amrit’ or the ponds of Shangri-La whereas if this nectar is partaken, such a person shall forever be above the rest of humanity and remain immortal. But in reality, it is not a drink to be drunk. It is a nectar of knowledge.” – <span className="color-txt">Ishan Shivanand</span></p>


        </div>
        
        <div className="col-12 div col-sm-6 col-md-6">
            <div className="position-relative mb-4 mb-sm-0">
            <img src={about1} alt="" className="radius-shadow-img img-fluid" />
            <div className="gallery-icon"><img src={gallery} alt="" /></div>
            <img src={wave} alt="" className="wave-leftImg" />
            </div>
        </div>
     
    </div>


    <div className="row mb-5 pb-5">
        <div className="col-12 div col-sm-6 col-md-6">
            <div className="position-relative mb-4 mb-sm-0">
            <img src={about1} alt="" className="radius-shadow-img img-fluid" />
            <div className="gallery-icon right-side"><img src={gallery} alt="" /></div>
            <img src={circle} alt="" className="circle-leftImg" />
            </div>
        </div>
        <div className="col-12 div col-sm-6 col-md-6">
    <h2 className="title mb-4">HOW IT WORKS ?
    </h2>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non, expedita officiis. Cumque iste eveniet vero consequatur natus! Dignissimos corporis odit ab necessitatibus officiis temporibus laboriosam deserunt eligendi ullam. Excepturi, facere.</p>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non, expedita officiis. Cumque iste eveniet vero consequatur natus! Dignissimos corporis odit ab necessitatibus officiis temporibus laboriosam deserunt eligendi ullam. Excepturi, facere.</p>


        </div>
        
     
    </div>

<div className="row mb-4">
    <div className="col-12 div col-sm-6 col-md-6">
<h2 className="title mb-4">WHAT IS SHIVYOG COSMIC THERAPY</h2>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non, expedita officiis. Cumque iste eveniet vero consequatur natus! Dignissimos corporis odit ab necessitatibus officiis temporibus laboriosam deserunt eligendi ullam. Excepturi, facere.</p>
<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non, expedita officiis. Cumque iste eveniet vero consequatur natus! Dignissimos corporis odit ab necessitatibus officiis temporibus laboriosam deserunt eligendi ullam. Excepturi, facere.</p>
    </div>
    <div className="col-12 div col-sm-6 col-md-6">
        <div className="position-relative mb-4 mb-sm-0">
        <img src={about1} alt="" className="radius-shadow-img img-fluid" />
            <div className="gallery-icon"><img src={gallery} alt="" /></div>
            <img src={wave} alt="" className="wave-leftImg" />
        </div>
    </div>
    
 
</div>


    
    
</div>
</section>

<section className="stories-section">
    <div className="container section-padding">
        <h3 className="title text-center mb-4">SUCCESS STORIES</h3>
        <div >
        <div id="stories-slider">
        <div className='container-fluid' >   
   <OwlCarousel  items={3} margin={8} loop autoplay ={true} > 
              {storyimg.map(suimg =>{
          return <div className="item" key={suimg.id}>
                <div className="story-box pb-3">
                    <div className="img-box">
                        <img src={"http://localhost:8000/images/"+suimg.images} alt="" height="266.117px" id='success_story' />
                        <h3>{suimg.name}</h3>
                        <p className="color-txt">{suimg.title}</p>
                        <p>{suimg.content}</p>
                    </div>
                </div>
            </div>
            })}
      </OwlCarousel>   
      </div>    
      
        </div>
    </div>
    </div>
</section> </div>
  )
}

export default Home;