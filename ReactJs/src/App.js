import React from 'react';
import './App.css';

import Header from './Components/Layout/Header';
import Footer from './Components/Layout/Footer';
import { BrowserRouter as Router, Route, Routes} from "react-router-dom";
import Home from './Components/Pages/Home';
import AboutUs from './Components/Pages/AboutUs';
import Therapists from './Components/Pages/Therapists';
import Philanthropy from './Components/Pages/Philanthropy';
import HowItWorks from './Components/Pages/HowItWorks';
import ContactUs from './Components/Pages/ContactUs';
import Register from './Components/Pages/Register';
import Login from './Components/User/Login';
import Enduserregistersignup from './Components/User/Enduserregistersignup';
import Resetpassword from './Components/User/Resetpassword';

import TherapistRegisterSignup from './Components/Pages/TherapistRegisterSignup';
import ForgotPassword from './Components/User/ForgotPassword';
import Dashboard from './Components/InnerPages/Dashboard';
import Appointments from './Components/InnerPages/Appointments';
import AppointmentsUnbook from './Components/InnerPages/AppointmentsUnbook';
import BookingInformation from './Components/InnerPages/BookingInformation';
import ContactSupport from './Components/InnerPages/ContactSupport';
import DashboardTherapists from './Components/InnerPages/DashboardTherapists';
import DashboardNav from './Components/Layout/DashboardNav';
import Payment from './Components/InnerPages/Payment';
import ManageAccount from './Components/InnerPages/ManageAccount';
import FAQ from './Components/Pages/FAQ';
import CancelPayment from './Components/InnerPages/CancelPayment';
import TherapistAfterLoginDashboard from './Components/InnerPages/TherapistAfterLoginDashboard';
import TherapistbyLanguage from './Components/Pages/TherapistbyLanguage';
import RescheduleSession from './Components/InnerPages/RescheduleSession';
import ReschedulePayment from './Components/InnerPages/ReschedulePayment';
 import Pages from './Components/Pages/Pages'; 

function App() {
  if(sessionStorage.getItem('user')){
    return (
      <>
      <DashboardNav />
      <Router>
      <Routes>
         <Route path='/dashboard' element={<Dashboard />} />
         <Route path='/appointments' element={<Appointments />} />
         <Route path='/appointmentsunbook' element={<AppointmentsUnbook />} />
         <Route path='/bookinginformation' element={<BookingInformation />} />
         <Route path='/dashboardtherapists' element={<DashboardTherapists />} />
         <Route path='/contactsupport' element={<ContactSupport />} />
         <Route path='/manageaccount' element={<ManageAccount />} />
         <Route path='/payment' element={<Payment />} />
         <Route path='/resechedulesession' element={<RescheduleSession />} />
         <Route path='/rescheduleconfirmpayment' element={<ReschedulePayment />} />
         <Route path='/cancelpayment' element={<CancelPayment />} />
         <Route path='/faq' element={<FAQ />} /> 
         <Route path='/therapistafterlogindashboard' element={<TherapistAfterLoginDashboard />} /> 
         <Route exact path="/" element={<Home />} />
        </Routes>
  </Router>
     <Footer />
      </>
    );
  }else{
    return (
      <>
      <Header />
      <Router>
      <Routes>
        <Route exact path="/" element={<Home />} />    
        <Route path='/contactus' element={<ContactUs />} /> 
         <Route path='/pages/:id' element={<Pages />} />  
       <Route path='/howitworks' element={<HowItWorks />} /> 
       <Route path='/aboutus' element={<AboutUs />} />
        <Route path='/therapists' element={<Therapists />} />
       <Route path='/philanthropy' element={<Philanthropy />} />
        <Route path='/registersignup' element={<Register />} />
      <Route path='/login' element={<Login />} /> 
       <Route path='/userregistersignup' element={<Enduserregistersignup />} />
       <Route path='/resetpassword' element={<Resetpassword />} /> 
       <Route path='/faq' element={<FAQ />} /> 
       <Route path='/therapistbylanguage' element={<TherapistbyLanguage/>} /> 
       <Route path='/generatepassword/' element={<TherapistRegisterSignup  />} />
       <Route path='/forgotpassword' element={<ForgotPassword />} />
      </Routes>
      </Router>
      <Footer />

      </>
    );
  }
 
}

export default App;
