import React, {useState, useEffect} from 'react';
import globeVar from '../../GlobeApi';
import logo from '../../assets/images/logo.png';

const Header = () => {
   const [cms, setCms] = useState([]); 
  
  
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
 
  useEffect(() => {
    cms_of_aboutus();
}, []) 
  return (
     <header className=" sticky-top homepage">
    <nav className="container navbar navbar-expand-xl navbar-light">
   <a className="navbar-brand" href="/">
       <img src={logo} alt="Shiv Yog" />
   </a>
   <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
     <span className="navbar-toggler-icon"></span>
   </button>
 
   <div className="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
     <ul className="navbar-nav mx-auto">
        {cms.map(item=>{
         return <li className="nav-item" key={item.id}>
         <a className="nav-link"  href={`pages/${item.id}`}>{item.title}</a>
       </li>
         })} 
       <li className="nav-item">
         <a className="nav-link" href="therapists">Therapists</a>
       </li>
       <li className="nav-item">
         <a className="nav-link" href="philanthropy">Philanthropy</a>
       </li>
       <li className="nav-item dropdown">
         <a className="nav-link" href="javascript:void(0)" id="howWorksDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">HOW IT WORKS <i className="fa fa-angle-down"></i></a>
        
         <div className="dropdown-menu" aria-labelledby="howWorksDropdown">
                         <div className="dropdown-box">
                             <a className="dropdown-item" href="howitworks">Lorem Ipsum</a>
                             <a className="dropdown-item" href="howitworks">Lorem Ipsum</a>
                             <a className="dropdown-item" href="howitworks">Lorem Ipsum</a>
                         </div>
                     </div>
       </li>
       <li className="nav-item">
         <a className="nav-link" href="contactus">Contact Us</a>
       </li>
     
    
     </ul> 
     <ul className="navbar-nav ">
       
       <li className="nav-item">
         <a className="btn border-btn" href="login">
           LOGIN
         </a>
       </li>
       <li className="nav-item ml-0 ml-lg-3">
         <a className="btn btn-regular" href="userregistersignup">
           REGISTER
         </a>
       </li>
     </ul>
   </div>
 </nav>
    </header>
  )
}

export default Header;