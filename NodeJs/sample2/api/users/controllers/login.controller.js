const { genSaltSync, hashSync, compareSync } = require('bcrypt');
const {  login, create, googlelogin} = require('../models/login.service');
const {userlogin } = require('../models/userforgot.service');


const { sign } = require('jsonwebtoken');
var uuid = require('uuid');
module.exports = {
    login: (req, res) => {
        const body = req.body;
        login(body.email, (error, result) => {
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
                        message: "Invalid email or password"
                    });
                } else {
                    const results = compareSync(body.password, result.password);
                    if (results) {
                        delete result.password;
                        const jsontoken = sign({ result: result }, process.env.JWT_KEY, {
                            expiresIn: "1h"
                        });
                        result.token =jsontoken;
                        return res.json({
                            success: 1,
                            message: "login successfully",
                            data: result
                        });
                    } else {
                        return res.json({
                            success: 0,
                            message: "Invalid email or password"
                        });
                    }
                }
            }
        });
    },
    socialmedialogin: (req, res) => {
        const body = req.body;
        body.role = "end_user";
        body.issocial = "1" ;
        body.unique_id = uuid.v4();
        userlogin(body.email, (error, result) => {
            console.log(body);
            if (error) {
                return res.status(500).json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                
                if(result){
                    googlelogin(body.email, (error, result) => {
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
                                    message: "Invalid email or password"
                                });
                            } else {
                                    /* const jsontoken = sign({ result: result }, process.env.JWT_KEY, {
                                        expiresIn: "1h"
                                    }); */
                                   /*  result.token =jsontoken; */
                                    return res.json({
                                        success: 1,
                                        message: "login successfully",
                                        data: result
                                    });
                                } 
                                
                            }
                    });
            }else{
                create(body, (error, result) => {
                    console.log(result);
                        if (error) {
                            console.log(error);
                            return res.status(500).json({
                                success: 0,
                                message: "Database connection errror"
                            });
                        } else {
                            console.log(body.email);
                            googlelogin(body.email, (error, result) => {
                                if (error) {
                                    console.log(error);
                                    return res.json({
                                        success: 0,
                                        message: "Database connection errror"
                                    });
                                } else {
                                    console.log(result);
                                    if (!result) {
                                        return res.json({
                                            success: 0,
                                            message: "Invalid email or password"
                                        });
                                    } else {
                                             const jsontoken = sign({ result: result }, process.env.JWT_KEY, {
                                                expiresIn: "1h"
                                            });
                                            result.token =jsontoken; 
                                            return res.json({
                                                success: 1,
                                                message: "login successfully",
                                                data: result
                                            });
                                        } 
                                        
                                    }
                            }); 
                        }
                    });
            }
            }
        });
    }    
};