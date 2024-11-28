
const { getdata, deletetoken } = require("../models/userconfirmationlink.service");
const { login } = require("../models/login.service");
const { edituser, superadminchangepassword } = require('../models/user.service');
const { genSaltSync, hashSync, compareSync } = require('bcrypt');

module.exports = {
    login: (req, res) => {
        const body = req.body;
        if(body.email != "" && body.password != "")
        {
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
                }
            }
        });
    }else{
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
                            if(result.role == "end_user")
                            {
                                res.locals = { title: 'Generate Password' };
                                res.render('login/set_password_user', { "data": result });   
                            }else{ 
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
    superadminchangepassword: (req, res) => {
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
                        superadminchangepassword(body, (error, result) => {
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
    }
};