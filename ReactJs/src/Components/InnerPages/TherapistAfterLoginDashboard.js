import React from 'react'
import user1 from '../../assets/images/user1.png'; 
import date_time from '../../assets/images/date_time.svg';
import user1lg from '../../assets/images/user1-lg.png';


const TherapistAfterLoginDashboard = () => {
  return (
    <div><section>
    <div class="container section-padding">

       
        <div class="form-row">
            <div class="col-12 col-sm-12 col-lg-6">
                <div class="dashboard-column">
                    <div class="top-color-box">
                        <div class="align-items-center row">
                            <h3 class="col mb-0 title">Upcoming Appointments</h3>
                            <div class="col-auto"><a href="appointments-unbook" class="color-txt weight-600">View All</a></div>
                        </div>
                    </div>
                
                    <div class="therapy_box">
                    <div class="top">
                        
                        <div class="row">
                            <div class="user-box col">
                                <img src={user1} alt="" class="user-img" />
                                <div>
                                <p class="name">Michael Kloskowski</p>
                                
                                <div class="icon-box">
                                    <span class="icon"><img src={date_time} alt="" /></span>
                                    <p class="mb-0">
                                    23 Feb 2022 | 7-8 AM IST
                                    </p>
                                </div>
                                </div>
                            </div>
                            <div class="col-auto mb-0">
                                <div class="d-flex flex-column mb-0">
                                    <span class="">Your Appointment is in</span>
                                    <span class="badge hour-badge float-right">2d. 16h. & 45m.</span>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="row align-items-center">
                        <div class="col-auto">
                                <a href="#" class="btn gradient-btn">Begin A Session</a>
                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="therapy_box">
                    <div class="top">
                        
                        <div class="row">
                            <div class="user-box col">
                                <img src={user1} alt="" class="user-img" />
                                <div>
                                <p class="name">Michael Kloskowski</p>
                                
                                <div class="icon-box">
                                    <span class="icon"><img src={date_time} alt="" /></span>
                                    <p class="mb-0">
                                    23 Feb 2022 | 7-8 AM IST
                                    </p>
                                </div>
                                </div>
                            </div>
                            <div class="col-auto mb-0">
                                <div class="d-flex flex-column mb-0">
                                    <span class="">Your Appointment is in</span>
                                    <span class="badge hour-badge float-right">2d. 16h. & 45m.</span>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        <div class="row align-items-center">
                        <div class="col-auto">
                                <a href="#" class="btn gradient-btn">Begin A Session</a>
                            </div>
                        </div>

                    </div>
                </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-lg-6">
                <div class="dashboard-column">
                    <div class="top-color-box">
                        <div class="align-items-center row">
                            <h3 class="col mb-0 title">My Profile</h3>
                            <div class="col-auto"><a href="manage-account.php" class="color-txt weight-600">View All</a></div>
                        </div>
                    </div>
                <div class="therapy_box">
                    <div class="top">
                        <div class="row">
                            <div class="user-box col">
                                <img src={user1lg} alt="" class="user-img" />
                                <div>
                                <p class="name">Sankalp Batra</p>
                                
                                <p class="color-txt mb-0">
                                    SHIV YOG MASTER THERAPIST
</p>
                                </div><div class="col-auto ml-auto">
                                <div class="d-flex align-items-center">
                                    <span class="badge hour-badge float-right">$70/hr</span>
                                </div>
                            </div>
                            </div>
                            
                        </div>
                        
                        
                        

                    </div>
                </div>
                
                
                </div>
            </div>

            </div>



        </div>
    
</section></div>
  )
}

export default TherapistAfterLoginDashboard