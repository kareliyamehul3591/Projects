const express = require('express');
const router =express.Router();
const pool = require('../middlewares/pool');
const jwt = require('jsonwebtoken');
const nodemailer = require('nodemailer');
const Cryptr = require('cryptr');
const cryptr = new Cryptr('secretKey');
const checkAuth = require('../middlewares/checkAuth');
const config = require('../config/config');
const multer = require('multer');
const fs = require('fs');
const { get } = require('http');

const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, './uploads/');
    },
    filename: function (req, file, cb) {
        cb(null, (Date.now() + file.originalname));
    },
});

const upload = multer({
    storage: storage,
    limits: { fileSize: 1024 * 1024 * 5 }
});

//add form16

router.post('/adduser', upload.single('photo'),(req, res) => {
    //console.log(req.body);
     console.log(req.file);
    
    const query = `INSERT INTO user_register(course_id,c_t_code,institute_id,fullname,father_name,mother_name,mobile_no,phone,
        email,permanent_address,residental_address,college_name,education_details,dob,doa,handicapped,photo,status,role)
         VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19)`;

    const values = [req.body.course,req.body.c_t_code,req.body.institute_id,req.body.fullname,req.body.father_name,
        req.body.mother_name,req.body.mobile_no,req.body.phone,req.body.email,req.body.permanent_address,
        req.body.residental_address,req.body.college_name,req.body.education_details,req.body.dob,
        req.body.doa,req.body.handicapped,req.file.path,0,'user'];
        
    pool.query(query, values).then(result => {
        res.status(200).json({
            message: "Post successfully",
            result: result
        });
    })
        .catch(err => {
            res.status(500).json({
                message: "Server error",
                error: err
            });
        });
});



//check admin login
router.post('/admin-check-login',(req,res)=>{
    console.log('hi');
    const query=`SELECT * FROM user_register WHERE email=$1 AND password=$2 AND role=$3`;
    const values=[req.body.email,req.body.password,'admin'];
    console.log(query);
    console.log(values);
    pool.query(query,values).then(result=>{
        console.log(result.rows)
        if(result.rows.length==1)
        {
            const token = jwt.sign({
                email:result.rows[0].email,
               
              },
             config.JWTsecretKey,
              {
                expiresIn : "5h"
              });
            res.status(200).json({
                message:'Login successfully',
                token: token,
                email: result.rows[0].email
                
               // res: result.rows[0]
            })
        }
        else{
            res.status(200).json({
                message: 'Auth failed'
            })
        }
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//add registeration
router.post('/AddRegister',(req,res)=>{
    console.log('j');
    const query=`INSERT INTO user_register (fullname,mobile_no,email,password,role,status) VALUES ($1,$2,$3,$4,$5,$6)`;
    const values=[req.body.fullname,req.body.mobile_no,req.body.email,req.body.password,'user',0];
    console.log(query);
    console.log(values);
    pool.query(query,values).then(result=>{
        res.status(200).json({
            message:"save successfully1",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//check user login
router.post('/check-user-login',(req,res)=>{
    const query=`SELECT * FROM user_register WHERE email=$1 AND password=$2 AND role=$3`;
    const values=[req.body.email,req.body.password,'user'];
    console.log(query);
    console.log(values);
    pool.query(query,values).then(result=>{
        console.log(result.rows);
        if(result.rows.length==1)
        {
            const token = jwt.sign({
                email:result.rows[0].email,
               
              },
             config.JWTsecretKey,
              {
                expiresIn : "5h"
              });
            res.status(200).json({
                message:'Login successfully',
                token: token,
                email: result.rows[0].email
                
               // res: result.rows[0]
            })
        }
        else{
            res.status(200).json({
                message: 'Auth failed'
            })
        }
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//get admin_check_login detilas against userid
router.get('/lookuser_details?:email',(req, res)=>{
    const query = `SELECT * FROM user_register  WHERE email=$1`;
    const values = [req.query.email];
    console.log(query);
    console.log(values);
        pool.query(query, values).then(result=>{
            console.log(res.result)
            res.status(200).json({
                message: "get value",
                result: result.rows
            });
        }).catch(err=>{
            res.status(500).json({
                message: "Server error",
                error:err
            });
        });
});

//check for active user
//get admin_check_login detilas against userid
router.get('/check_details?:email',(req, res)=>{
    const query = `SELECT * FROM user_register  WHERE email=$1 AND status='0'`;
    const values = [req.query.email];
    console.log(query);
    console.log(values);
        pool.query(query, values).then(result=>{
          
            if(result.rows.length==1)
            {
                console.log('not');
                res.status(200).json({
                    message: "not active",
                    result: result.rows
                });
            }
            else{
                console.log('y')
                res.status(200).json({
                    message: "active",
                    result: result.rows
                });
            }
        }).catch(err=>{
            res.status(500).json({
                message: "Server error",
                error:err
            });
        });
});

//check for duplicate email
router.get('/check_email?:id',(req, res)=>{
    console.log(req.query.id);
    const query = `SELECT email FROM user_register  WHERE email=$1`;
    const values = [req.query.id];
    console.log(query);
    console.log(values);
        pool.query(query, values).then(result=>{
            console.log(result.rows.length)
            if(result.rows.length==1)
            {
                res.status(200).json({
                    message:"exist",
                    result: result.rows
                });
            }
        else{
            res.status(200).json({
                message:"not",
                result: result.rows
            });
        }
            
        }).catch(err=>{
            res.status(500).json({
                message: "Server error",
                error:err
            });
        });
});

//fetch user registration
router .get('/fetch-user',(req,res)=>{
    const query=`select * from user_register where role NOT IN ('admin')`;
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"save successfully1",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//get user agianst id
router .get('/get-user/:id',(req,res)=>{
    console.log(req.params.id);
    const values=[req.params.id];
    const query=`SELECT user_register.id,course_id,c_t_code,institute_id,fullname,father_name,mother_name,
    mobile_no,email,phone,permanent_address,residental_address,college_name,education_details,
    dob,doa,handicapped,course.id,name,duration,fees,installment,photo
    FROM user_register
    INNER JOIN course
    ON user_register.course_id = course.id where user_register.id=${values}`;
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"show successfully1",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//edit user against id
router .put('/edituser/:id',(req,res)=>{
    console.log(req.params.id);
    const query=`UPDATE user_register SET course_id=$1 where id=$2`;
    const values=[req.body.course_id,req.params.id];
        
    pool.query(query,values).then(result=>{
        res.status(200).json({
            message:"update successfully",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//change to active user against id
router .put('/active_user/:id',(req,res)=>{
    console.log(req.params.id);
    const query=`UPDATE user_register SET status=$1 where id=$2`;
    const values=[1,req.params.id];
        
    pool.query(query,values).then(result=>{
        res.status(200).json({
            message:"update successfully",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})


//change to active user against id
router .put('/deactive_user/:id',(req,res)=>{
    console.log(req.params.id);
    const query=`UPDATE user_register SET status=$1 where id=$2`;
    const values=[0,req.params.id];
        
    pool.query(query,values).then(result=>{
        res.status(200).json({
            message:"update successfully",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//delete notification
router.delete('/delete_user/:id',(req,res)=>{
    console.log(req.params.id);
    console.log('hi')
    const values=[req.params.id];
    const query=`delete from user_register where id=${values}`;
    
    console.log(query);
    // console.log(values);
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"Deleted successfully",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
})

//add notification
router.post('/AddNotification',(req,res)=>{
    const query=`Insert into notification (title,content,date) Values ($1,$2,$3)`;
    const values=[req.body.title,req.body.content,req.body.date];
    console.log(query);
    console.log(values);
    pool.query(query,values).then(result=>{
        res.status(200).json({
            message:"save successfully",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//add notification
router.post('/AddCourse',(req,res)=>{
    const query=`Insert into course (name,duration,fees,installment) Values ($1,$2,$3,$4)`;
    const values=[req.body.name,req.body.duration,req.body.fees,req.body.installment];
    console.log(query);
    console.log(values);
    pool.query(query,values).then(result=>{
        res.status(200).json({
            message:"save successfully",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//fetch notification
router.get('/fetch-notification',(req,res)=>{
    const query=`Select * from notification`;
    console.log(query);
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"show succesfully",
            result:result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message:"server error",
            error:err,
        })
    });
})

//fetch notification agaonst id
router.get('/get-notification/:id',(req,res)=>{
    console.log(req.params.id)
    const values=[req.params.id];
    const query=`Select * from notification where id=${values}`;

    console.log(query);
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"get succesfully",
            result:result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message:"server error",
            error:err,
        })
    });
})

//edit notification
router.put('/updatenotify/:id', (req, res)=>{
    console.log(req.params.id);
    
    const query = `UPDATE notification SET title=$1,content=$2,date=$3 WHERE id=$4`;
    const values = [req.body.title,req.body.content,req.body.date,req.params.id];
    console.log(query);
    pool.query(query,values).then(result=>{
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

//delete notification
router.delete('/delete_notify/:id',(req,res)=>{
    console.log(req.params.id);
    console.log('hi')
    const values=[req.params.id];
    const query=`delete from notification where id=${values}`;
    
    console.log(query);
    // console.log(values);
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"Deleted successfully",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
})



//add enquiry
router.post('/AddEnquiry',(req,res)=>{
    const query=`Insert into mangal_enquiry (name,email,subject,message) values($1,$2,$3,$4)`;
    const values=[req.body.name,req.body.email,req.body.subject,req.body.message];
    console.log(query);
    console.log(values);
    pool.query(query,values).then(result=>{
        res.status(200).json({
            message:"save successfully",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//fetch enquiry
router .get('/fetch_enquiry',(req,res)=>{
    const query=`Select * from mangal_enquiry`;
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"save successfully",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err,
            
        });
    });
})

//fetch notification
router.get('/fetch-course',(req,res)=>{
    const query=`Select * from course`;
    console.log(query);
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"show succesfully",
            result:result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message:"server error",
            error:err,
        })
    });
})

//fetch course details against course id
router.get('/fetch-course_details/:id',(req,res)=>{
    console.log(req.params.id);
    const values=[req.params.id];
    const query=`Select * from course where id=${values}`;
    console.log(query);
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"show succesfully",
            result:result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message:"server error",
            error:err,
        })
    });
})

//delete course
router.delete('/delete_course/:id',(req,res)=>{
    console.log(req.params.id);
    console.log('hi')
    const values=[req.params.id];
    const query=`delete from course where id=${values}`;
    
    console.log(query);
    // console.log(values);
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"Deleted successfully",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
})

//fetch notification agaonst id
router.get('/get-course/:id',(req,res)=>{
    console.log(req.params.id)
    const values=[req.params.id];
    const query=`Select * from course where id=${values}`;

    console.log(query);
    pool.query(query).then(result=>{
        res.status(200).json({
            message:"get succesfully",
            result:result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message:"server error",
            error:err,
        })
    });
})

//edit notification
router.put('/updatecourse/:id', (req, res)=>{
    console.log(req.params.id);
    
    const query = `UPDATE course SET name=$1,duration=$2,fees=$3,installment=$4 WHERE id=$5`;
    const values = [req.body.name,req.body.duration,req.body.fees,req.body.installment,req.params.id];
    console.log(query);
    pool.query(query,values).then(result=>{
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

//change to active user against id

//update quotation details
router.put('/materialupdate?:id', upload.single('photo'),(req, res)=>{
    console.log(req.query.id);
    console.log(req.body.course_id);
    console.log(req.file);
    
    
        var query = `UPDATE user_register SET course_id=$1,c_t_code=$2,institute_id=$3,fullname=$4,
        father_name=$5,mother_name=$6,mobile_no=$7,email=$8,phone=$9,permanent_address=$10,residental_address=$11,
        college_name=$12,education_details=$13,dob=$14,doa=$15,photo=$16,handicapped=$17 WHERE email=$18`;
      var values = [req.body.course_id,req.body.c_t_code,req.body.institute_id,req.body.fullname,
        req.body.father_name,req.body.mother_name,req.body.mobile_no,req.body.email,req.body.phone,
        req.body.permanent_address,req.body.residental_address,req.body.college_name,req.body.education_details,
        req.body.dob,req.body.doa,req.file.path,req.body.handicapped,req.query.id];
    
   console.log(query)
   console.log(values)
    pool.query(query, values).then(result=>{
        console.log(query, values);
        console.log(req.query);
      
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