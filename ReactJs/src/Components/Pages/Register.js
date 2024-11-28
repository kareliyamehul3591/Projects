import React from 'react'
import { Link } from 'react-router-dom';
import works2 from '../../assets/images/works2.jpg';

const Register = () => {
  return (
    <div><section class="breadcrumb-box">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-12 col-sm-auto">
                <h1>REGISTER</h1>
            </div>
            <div class="col-12 col-sm">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-sm-end">
                        <li class="breadcrumb-item"><Link to={`/`}>Home</Link></li>
                        <li class="breadcrumb-item active" aria-current="page">REGISTER</li>
                    </ol>
                </nav>
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
                            <h3 class="title">Create Your Account</h3>
                        <div class="form-row">
            <div class="form-group input-box col-sm-12">
                <label for="email">Email</label>
                <input type="text" class="form-control" name="" id="email" placeholder="Your email here" />
            </div>
            <div class="form-group input-box col-sm-6">
                <label for="password">Password</label>
                <div class="position-relative">
                    <input type="password" class="form-control" value="abcd@1234" name="" id="password" placeholder="Your password here" />
                    <i class="fa fa-eye input-icon"></i>
                </div>
            </div>
            <div class="form-group input-box col-sm-6">
                <label for="corfirm_password">Re-Enter Password</label>
                <div class="position-relative">
                    <input type="password" class="form-control" value="abcd@1234" name="" id="corfirm_password" placeholder="Re-Enter Your password here" />
                    <i class="fa fa-eye input-icon"></i>
                </div>
            </div>
            <div class="form-group col-sm-12">
                <input type="button" value="Signup" class="btn gradient-btn px-5" />
            </div>
            <p class="weight-600 col-12 mb-0">Already have an account? <Link to={`/login`} class="color-txt">Login</Link> </p>
        </div>
                            </div>
                        </div>
                  
                    </form>
                </div>

            </div>
            <div class="col-12 col-md-12 col-lg-10 col-xl-4 text-center">
                
            <img src={works2} class="radius-shadow-img img-fluid mb-4" alt="" />

            </div>

        </div>
    </div>
</section></div>
  )
}

export default Register;