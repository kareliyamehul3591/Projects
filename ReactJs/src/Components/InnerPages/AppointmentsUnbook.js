import React from 'react'
import user1 from '../../assets/images/user1.png';
import date_time from '../../assets/images/date_time.svg';

const AppointmentsUnbook = () => {
  return (
    <div><section>
    <div class="container section-padding">



        <div class="accordion custom-accordion accordion2 " id="accordionExample">
            <div class="card active">
                <div class="card-header" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Upcoming Appointments
                        </button>
                    </h2>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="therapy_box h-auto">
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

                                        </div>


                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <a href="#" class="btn gradient-btn">Begin A Session</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="therapy_box h-auto">
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

                                        </div>


                                        <div class="row align-items-center">
                                            <div class="col-12">
                                                <a href="#" class="btn gradient-btn">Begin A Session</a>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Past Appointments
                        </button>
                    </h2>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="therapy_box h-auto">
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

                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="therapy_box h-auto">
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

                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Manage your availability
                        </button>
                    </h2>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-5">
                                    <div class="session-scroller">
                                        <span class="arrow left-arrow"><i class="fa fa-angle-left"></i></span>
                                        <div class="session-dates" id="foo">
                                            <a href="javascript:void(0)" class="active">Mon, 21 Feb</a>
                                            <a href="javascript:void(0)">Tue, 22 Feb</a>
                                            <a href="javascript:void(0)">Wed, 23 Feb</a>
                                            <a href="javascript:void(0)">Thu, 24 Feb</a>
                                            <a href="javascript:void(0)">Fri, 25 Feb</a>
                                            <a href="javascript:void(0)">Sat, 26 Feb</a>
                                            <a href="javascript:void(0)">Sun, 27 Feb</a>
                                            <a href="javascript:void(0)">Mon, 28 Feb</a>
                                            <a href="javascript:void(0)">Tue, 01 Mar</a>
                                            <a href="javascript:void(0)">Wed, 02 Mar</a>
                                            <a href="javascript:void(0)">Thu, 03 Mar</a>
                                            <a href="javascript:void(0)">Fri, 04 Mar</a>
                                            <a href="javascript:void(0)">Sat, 05 Mar</a>
                                            <a href="javascript:void(0)">Sun, 06 Mar</a>
                                        </div>
                                        <span class="arrow right-arrow"><i class="fa fa-angle-right"></i></span>
                                    </div>

                                    <div class="time-boxes">
                                        <span class="time-period"><span>10:00 - 11:00 AM</span></span>
                                        <span class="time-period"><span>11:00 - 12:00 PM</span></span>
                                        <span class="time-period"><span>12:00 - 13:00 PM</span></span>
                                        <span class="time-period"><span>13:00 - 14:00 PM</span></span>
                                        <span class="time-period"><span>14:00 - 15:00 PM</span></span>
                                        <span class="time-period"><span>15:00 - 16:00 PM</span></span>
                                        <span class="time-period"><span>16:00 - 17:00 PM</span></span>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="row justify-content-center mb-5">
                            <div class="col-auto mb-3">
                                <input type="button" value="Add New Availability of Slots" class="btn gradient-btn px-5" />
                            </div>
                            <div class="col-auto mb-3">
                                <input type="button" value="Update the Available Slots" class="btn pink-border-btn px-5" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="therapy_box h-auto">
                                    <div class="top py-5">
                                        <h4 class="text-center">Calender</h4>

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

export default AppointmentsUnbook;