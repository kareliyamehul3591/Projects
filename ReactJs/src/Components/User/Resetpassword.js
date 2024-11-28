import React from 'react'
import formsideimg from '../../assets/images/form-side-img.png';


const Resetpassword = () => {
  return (
    <div><section class="breadcrumb-box">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-sm-auto">
                <h1>RESET PASSWORD</h1>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container section-padding">

        <div class="row justify-content-center flex-column-reverse flex-lg-row">

            <div class="col-12 col-md-12 col-lg-10 col-xl-8">
                <div class="form-box">
                    <form action="">
                        <div class="row">
                            <div class="col-12 col-xl-10">
                                <h3 class="title">Reset Your Password</h3>
                                <div class="form-row">

                                    <div class="form-group input-box col-sm-12">
                                        <label for="password">New Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" value="abcd@1234" name="" id="password" placeholder="Enter new password here" />
                                            <i class="fa fa-eye input-icon"></i>
                                        </div>
                                    </div>
                                    <div class="form-group input-box col-sm-12">
                                        <label for="corfirm_password">Re-Enter New Password</label>
                                        <div class="position-relative">
                                            <input type="password" class="form-control" value="abcd@1234" name="" id="corfirm_password" placeholder="Re-Enter new password here" />
                                            <i class="fa fa-eye input-icon"></i>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <input onclick="window.location.href = 'login.php'" type="button" value="Submit" class="btn gradient-btn px-5" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

            </div>
            <div class="col-12 col-md-12 col-lg-10 col-xl-4 text-center">

                <img src={formsideimg} class="radius-shadow-img img-fluid mb-4" alt="" />

            </div>

        </div>
    </div>
</section></div>
  )
}

export default Resetpassword;