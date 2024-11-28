const express = require('express');
const router =express.Router();
const pool = require('../middlewares/pool');
const jwt = require('jsonwebtoken');
const nodemailer = require('nodemailer');
const Cryptr = require('cryptr');
const cryptr = new Cryptr('secretKey');
const checkAuth = require('../middlewares/checkAuth');
const config = require('../config/config');

// Login

// Login
router.post('/checkLogin', (req, res)=>{
    console.log('hi');
     console.log(req.body)
    const query = `SELECT username, designation_id,first_name,last_name, id FROM emp
                    WHERE username=$1 AND password=$2`;
    const values = [req.body.username, req.body.password];
    pool.query(query, values).then(result=>{
                                console.log(result.rows);
                                if(result.rows.length==1){
                                    const token = jwt.sign({
                                        username:result.rows[0].username,
                                        designationId: result.rows[0].designation_id,
                                        employeeId: result.rows[0].id
                                      },
                                     config.JWTsecretKey,
                                      {
                                        expiresIn : "5h"
                                      });
                                    res.status(200).json({
                                        message:'Login successfully',
                                        token: token,
                                        designation: result.rows[0].designation_id,
                                        username:result.rows[0].username,
                                        first_name:result.rows[0].first_name,
                                        last_name:result.rows[0].last_name,
                                        emp_id:result.rows[0].id
                                       // res: result.rows[0]
                                    })
                                }
                                else{
                                    res.status(200).json({
                                        message: 'Auth failed'
                                    })
                                }
                            }).catch(err=>{
                                console.log(err);
                                res.status(500).json({
                                    message: "Server error",
                                    error:err
                                })
                            })
});

// forgetpassword


// Forget password
router.post('/forgetpass', async(req, res)=>{
    // console.log(req.body);
    const query = "SELECT id from emp WHERE personal_email=$1"
    const values = [req.body.personalEmail]
    pool.query(query, values).then(result=>{
        console.log(result);
        if(result.rows.length==1){
            var transporter = nodemailer.createTransport({
                host: 'smtp.gmail.com',
                port: 465,
                secure: true, // use SSL
                auth: {
                        user: 'omsraksoftech@gmail.com',
                        pass: 'Oms@rak123' 
                },
                tls: {
                    rejectUnauthorized: false
                }

                
            });
            return{transporter:transporter, userData: result}
        }
        else{
            res.status(200).json({message: 'Auth failed'});
        }
    }).then(result=>{
        // console.log(result);
        if(result){
            console.log(result.userData.rows[0])
            const encryptedUserId= cryptr.encrypt(result.userData.rows[0].id);
            //const encryptedUserId1= cryptr.decrypt(encryptedUserId);
            console.log('hee');
            console.log(encryptedUserId);
            //console.log(encryptedUserId);
            const mailOptions = {
                from: 'abhijeetchikane616@gmail.com', // sender address
                to:req.body.personalEmail, // list of receivers
                subject: 'Change Password', // Subject line
                text: `http://localhost:4200/#/change-pass/${encryptedUserId}`// plain text body
               // text: `http://localhost:4200/#/change-pass`// plain text body
               
            };
            console.log(mailOptions);
            result.transporter.sendMail(mailOptions, function (err, info) {
                if (err) {
                    console.log(err);
                    res.json({
                        message: 'Email transfer failed',
                        error: err
                    });
                }
                else {
                    res.status(200).json({
                        message: 'Email sent',
                        info: info
                    });
                }
            });
        }
    }).catch(err=>{
        console.log(err);
        res.status(500).json({
            message: "Server error",
            error:err
        })
    })
});

//update forget-password
router.put('/update-pass', (req, res)=>{
    console.log(req.query);
    const eid= cryptr.decrypt(req.body.eid);
    
    console.log(eid);

    const query = `UPDATE emp SET password=$1 WHERE id=$2`;
    const values = [req.body.password,eid
        ];
        // console.log('hoo');
        // console.log(values)
    pool.query(query, values).then(result=>{
        console.log(query, values);
        console.log(req.query);
       console.log(res.result);
        res.status(200).json({
            message:"Updated successfully",  
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});




module.exports = router;

