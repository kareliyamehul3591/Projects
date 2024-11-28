const { getdata, deletetoken, insertcreatelink } = require("../models/userconfirmationlink.service");
const { login } = require("../models/login.service");
const { edituser, changepassword, resetpassword } = require('../models/user.service');
const { genSaltSync, hashSync, compareSync } = require('bcrypt');
const { email } = require('./email.controller');
var uuid = require('uuid');

module.exports = {
    login: (req, res) => {
        const body = req.body;
        if (body.email != "" && body.password != "") {
            login(body, (error, result) => {
                if (error) {
                    res.locals = { title: 'Login' };
                    console.log(error);
                    console.log('login fail');
                    req.flash('message', 'Database connection errror !!');
                    req.flash('status', 'danger');
                    res.render('login/login', { message: req.flash('message'), status: req.flash('status') });
                } else {
                    if (!result) {
                        console.log('login fail');
                        res.locals = { title: 'Login' };
                        req.flash('message', 'Username or password Incorrect !! !!');
                        req.flash('status', 'danger');
                        res.render('login/login', { message: req.flash('message'), status: req.flash('status') });
                    } else {
                        if (result.role != "end_user") {
                            const results = compareSync(body.password, result.password);
                            if (results) {
                                result.password = undefined;
                                req.session.loggedin = true;
                                req.session.auth_data = result;
                                console.log('login sucess');
                                console.log(req.session);
                                req.flash('message', 'login Sucessfully!..');
                                if (result.role == 'admin') {
                                    res.redirect('/admin');
                                } else {
                                    res.redirect('/therapist');
                                }

                            } else {
                                res.locals = { title: 'Login' };
                                console.log('login fail');
                                req.flash('message', 'Username or password Incorrect !!');
                                req.flash('status', 'danger');
                                res.render('login/login', { message: req.flash('message'), status: req.flash('status') });
                            }
                        } else {
                            res.locals = { title: 'Login' };
                            console.log('login fail');
                            req.flash('message', 'Username or password Incorrect !!');
                            req.flash('status', 'danger');
                            res.render('login/login', { message: req.flash('message'), status: req.flash('status') });
                        }
                    }
                }
            });
        } else {
            console.log('login fail');
            res.locals = { title: 'Login' };
            req.flash('message', 'Username or password Incorrect !! !!');
            req.flash('status', 'danger');
            res.render('login/login', { message: req.flash('message'), status: req.flash('status') });
        }

        /*if (body.email == "admin@gmail.com" && body.password == "admin") {

            req.session.loggedin = true;
            req.session.auth_data = 'admin@gmail.com';
            console.log('login sucess');
            console.log(req.session);
            req.flash('message', 'login Sucessfully!..');
            res.redirect('/');

        } else {
            console.log('login fail');
            req.flash('message', 'Username or password Incorrect !!');
            req.flash('status', 'danger');
            res.render('login/login', { message: req.flash('message'), status: req.flash('status') });

            //res.redirect('/login');
            //res.send('Incorrect Username and/or Password!');
        }*/
    },
    setpassword: (req, res) => {
        const token = req.params.token;
        getdata(token, (error, result) => {
            if (error) {
                console.log(error);
            } else {
                if (result) {
                    /*  console.log("now :"+new Date()+" time :"+new Date().getTime());
                     console.log("now :"+new Date(result.created_at)+" time :"+new Date(new Date(result.created_at).setMinutes (new Date(result.created_at).getMinutes () + 2))); */
                    /*  if(new Date().getTime() < new Date(new Date(result.created_at).setHours(new Date(result.created_at).getHours() + 2)).getTime()) */
                    if (new Date().getTime() < new Date(new Date(result.created_at).setHours(new Date(result.created_at).getHours() + 1)).getTime()) {
                        edituser(result.user_id, (error, result) => {
                            result.token = token;
                            console.log(result);
                            if (result.role == "end_user") {
                                res.locals = { title: 'Generate Password' };
                                res.render('login/set_password_user', { "data": result });
                            } else {
                                res.locals = { title: 'Generate Password' };
                                res.render('login/set_password', { "data": result });
                            }
                        });
                    } else {
                        res.locals = { title: 'Token Expired' };
                        res.render('login/token_expired', { "data": result, "message": "Sorry, your Token have expired. Please try again." });
                    }
                } else {
                    res.locals = { title: 'Invalid Token' };
                    res.render('login/token_expired', { "data": result, "message": "This Token is Invalid" });
                }
            }
        });
    },
    changepassword: (req, res) => {
        const body = req.body;
        if (body.conpassword == body.password) {
            const salt = genSaltSync(10);
            body.password = hashSync(body.password, salt);
            body.status = "1";
            console.log(body);
            getdata(body.token, (error, result) => {
                if (result) {
                    if (result.user_id == body.id) {
                        edituser(result.user_id, (error, result) => {
                            req.session.changepassword_user_role = result.role;
                        });
                        changepassword(body, (error, result) => {
                            if (error) {
                                console.log(error);
                                return res.json({
                                    success: 0,
                                    message: "Database connection errror"
                                });
                            } else {
                                if (!result) {
                                    return res.json({
                                        success: 0,
                                        message: "Record Not Found"
                                    });
                                } else {
                                    deletetoken(body.token, (error, result) => {
                                        if (error) {
                                            console.log(error)
                                        }
                                    });
                                    return res.json({
                                        success: 1,
                                        message: "Password Generator successfully"
                                    });
                                }
                            }
                        });
                    } else {
                        return res.json({
                            success: 0,
                            message: "Invalid User"
                        });
                    }
                } else {
                    return res.json({
                        success: 0,
                        message: "Invalid Token"
                    });
                }

            });
        }
    },
    forgotpassword: (req, res) => {
        res.locals = { title: 'Forgot Password' };
        res.render('login/forgot_password', { message: req.flash('message'), status: req.flash('status') });
    },
    forgotpasswordemail: (req, res) => {
        const body = req.body;

        login(body, (error, result) => {
            if (error) {

                console.log(error);
                res.locals = { title: 'Forgot Password' };
                req.flash('message', 'Database connection errror !!');
                req.flash('status', 'danger');
                res.render('login/forgot_password', { message: req.flash('message'), status: req.flash('status') });
            } else {
                if (result) {
                    body.user_data = result;
                    const linkdata = { "user_id": result.id, "token": uuid.v4(0) };
                    insertcreatelink(linkdata, (error, result) => {
                        console.log(error);
                        if (!error) {
                            function emailHtml (data){
                                return `<!doctype html>
                                <html>
                                <head>
                                <meta charset="utf-8">
                                <title>:: ShivYog ::</title>
                                <style>
                                    body{box-sizing: border-box; padding: 0; margin: 0;}
                                    table{width: 100%; max-width: 700px; font-family: Helvetica; font-size: 16px; margin: 0px auto;}
                                </style>
                                    </head>
                                
                                <body>
                                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 700px; font-family: Helvetica; font-size: 16px; margin: 0px auto;">
                                        <thead><tr><td style="padding: 15px 0;"><img src="https://api.ashoresystems.ws/public/assets/images/brand.jpg"></td></tr></thead>
                                        <tbody><tr><td style="padding: 15px 0; border-top:1px solid #000">
                                            <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.user_data.f_name}</strong>,</p><p style="color: #666;  line-height: 26px;">
                                Please click on the link below to reset your password.
                                <a href="${data.url}" style="color: #000; text-decoration: underline;"> Reset Password </a></p><p style="color: #666; line-height: 26px;">The link will expire within 5 days.<br/>
                                For further questions or assistance please email <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a></p>
                                            </td></tr>
                                            </tbody>
                                        <tfoot>		
                                            <tr><td><p style="color: #666; line-height: 26px;">Thank you,<br/>ShivYog Cosmic Therapy Team<br/><a href="#">www.shivyogcosmictherapy.com</a></p></td></tr>
                                
                                            <tr><td style="padding: 15px 0; border-top:1px solid #000">Please add <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a> to your address book to ensure inbox delivery.</td></tr>
                                    </tfoot>
                                    </table>
                                    
                                </body>
                                </html>
                                `;
                              }
                               body.url = process.env.AAP_URL + "generatepassword/" + linkdata.token ;
                            var email_data = {
                                "from": "demo@gmail.com",
                                "to": body.email,
                                "subject": "Forgot Password",
                                "text": "Hello " + body.email,
                                "html": emailHtml(body)
                            };
                            email(email_data);
                            res.locals = { title: 'Forgot Password' };
                            req.flash('message', 'Email send Sucessfully');
                            req.flash('status', 'success');
                            res.render('login/forgot_password', { message: req.flash('message'), status: req.flash('status') });
                        }
                    });
                } else {
                    res.locals = { title: 'Forgot Password' };
                    req.flash('message', 'Email does not exist !!');
                    req.flash('status', 'danger');
                    res.render('login/forgot_password', { message: req.flash('message'), status: req.flash('status') });
                }
            }
        });
    },
    resetpassword: (req, res) => {
        const body = req.body;
        body.email = req.session.auth_data.email;
        login(body, (error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                if (result != "") {
                    const results = compareSync(body.current_password, result.password);
                    if (results) {
                        console.log(results);
                        const salt = genSaltSync(10);
                        body.password = hashSync(body.new_password, salt);
                        body.id = req.session.auth_data.id;
                        resetpassword(body, (error, result) => {
                            if (error) {
                                return res.json({
                                    success: 0,
                                    message: "Database connection errror"
                                });
                            } else {
                                return res.json({
                                    success: 1,
                                    message: "Password Reset Password Sucessfully"
                                });
                            }
                        });
                    } else {
                        return res.json({
                            success: 0,
                            message: "Current Password is Incorrect"
                        });
                    }
                } else {
                    return res.json({
                        success: 0,
                        message: "Record Not Found"
                    });
                }
            }
        });
    }
};