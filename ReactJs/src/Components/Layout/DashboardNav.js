
import React from 'react';
import LogoutByGoogle from '../User/LogoutByGoogle';
import './DashboardNav.css'
/* import axios from "axios"; */
/* import { useParams } from "react-router-dom"; */
import logo from '../../assets/images/logo.png';
import profile_fill from '../../assets/images/profile_fill.svg';
import profile from '../../assets/images/profile.svg';
import support_fill from '../../assets/images/support_fill.svg';
import logout from '../../assets/images/logout.svg';
import $ from 'jquery';
import Swal from 'sweetalert2';


const DashboardNav = () => {
  let data = sessionStorage.getItem('user');
    data = JSON.parse(data);
    const str = data.email;
    const str1 = str.split("@");
    /* console.log(data.email); */

  const handleLogout = async (e) => {
    e.preventDefault();
    /* let data = sessionStorage.getItem('user');
    data = JSON.parse(data);
    console.log(data.email); */
    Swal.fire({
      title: 'Are you sure, do you want to log out?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes'
    }).then((result) => {

      if (result.isConfirmed) {
        $(".logout").trigger('click');
    localStorage.clear();
    sessionStorage.clear();
 
    window.location.href = '/'
      }
    })
    
   
  };
  
  return (
  
    <div><header className=" sticky-top dashboard-header">
    <nav className="container navbar navbar-expand-lg navbar-light">
   <a className="navbar-brand" href="/">
       <img src={logo} alt="ShivYog" />
   </a>
   <button className="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
     <span className="navbar-toggler-icon"></span>
   </button>
 
   <div className="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
     <ul className="navbar-nav mx-auto">
       <li className="nav-item">
         <a className="nav-link" href="dashboard">Dashboard</a>
       </li>
       <li className="nav-item">
         <a className="nav-link" href="dashboardtherapists">Therapists</a>
       </li>
       <li className="nav-item">
         <a className="nav-link" href="appointments">Appointments</a>
       </li>
     </ul> 
    
   </div>
   <div className="ml-auto">
   <ul className="navbar-nav ">
     
     <li className="nav-item dropdown user-dropdown">
       <a className="nav-link m-0" href="javascript:void(0)" id="howWorksDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img src={profile_fill} alt="" className="usen-img" /><span className="user-name">{str1[0]}</span> <i className="fa fa-angle-down" ></i></a>
      
       <div className="dropdown-menu dropdown-menu-right" aria-labelledby="howWorksDropdown">
                       <div className="dropdown-box">
                           <a className="dropdown-item" href="manageaccount"><span className="dpdwn-icons"><img src={profile} alt="" /></span> Manage Profile</a>
                           <a className="dropdown-item" href="contactsupport"><span className="dpdwn-icons"><img src={support_fill} alt="" /></span>Contact Support</a>
                           <a className="dropdown-item"  href="/" onClick={handleLogout} ><span className="dpdwn-icons"><img src={logout} alt="" /></span>Logout </a>
                           <LogoutByGoogle/>
              
                       </div>
                   </div>
     </li>
   </ul>
   </div>
 </nav>
    </header></div>
  )
}

export default DashboardNav;