//emp route

const express = require('express');
const router =express.Router();
const pool = require('../middlewares/pool');
const jwt = require('jsonwebtoken');
const nodemailer = require('nodemailer');
const Cryptr = require('cryptr');
const cryptr = new Cryptr('secretKey');
const checkAuth = require('../middlewares/checkAuth');
const config = require('../config/config');


//add emp
router.post('/addEmp1', (req, res)=>{
    console.log(req.body);
    const query = `WITH new_emp AS (INSERT INTO emp(username,password,first_name,middle_name,
        last_name,dob,gender,mobile_no,alternate_mobile_no,personal_email,professional_email
        ,address,aadhaar,pan,qualification,doj,designation_id,location_id) VALUES
        ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18) RETURNING id),
        new_bank AS (INSERT INTO bank(account_no,bank_name,ifsc,emp_id) SELECT $19,$20,$21, id FROM new_emp)
        INSERT INTO leave(sl_entitled,cl_entitled,emp_id) SELECT $22,$23, id FROM new_emp`; 

                    
    
    const values = [req.body.userName,req.body.password,
        req.body.firstName,req.body.middleName,req.body.lastName,req.body.dob,
        req.body.gender,req.body.mobileNo,req.body.alternateMobileNo,
        req.body.personalEmail,req.body.professionalEmail,req.body.address,
        req.body.aadhaar,req.body.pan,req.body.qualification,
        req.body.doj,req.body.designationId,req.body.locationId,
        req.body.accountNo,
        req.body.bankName,req.body.ifsc,req.body.slEntitled,req.body.clEntitled];
        pool.query(query, values).then(async(result)=>{
            res.status(200).json({
                message:"Post successfully",
                result: result
            });
        })
    .catch(err=>{
        res.status(500).json({ 
            message: "Server error",
            error:err
        });
    });
});

//add employee details through excel sheet 
router.post('/add_excel_emp', (req, res)=>{
   
   console.log(req.body);
  
   let empValues='';
   req.body
   .forEach(function(item)
   {//for dob
    var serial=`${item.dob}`;
   
    var utc_days  = Math.floor(serial - 25569);
    var utc_value = utc_days * 86400;                                        
var date_info = new Date(utc_value * 1000);
var fractional_day = serial - Math.floor(serial) + 0.0000001;

var total_seconds = Math.floor(86400 * fractional_day);

var seconds = total_seconds % 60;

total_seconds -= seconds;
var hours = Math.floor(total_seconds / (60 * 60));
var minutes = Math.floor(total_seconds / 60) % 60;
const dob= (date_info.getFullYear(),date_info.getMonth(), date_info.getDate()+1);
console.log("k");
 var d=(date_info.getFullYear()+'-'+(date_info.getMonth()+1) +'-'+date_info.getDate())

 console.log(d);
    //for dob

    //for doj
    var doj=`${item.doj}`;
   
    var utc_days  = Math.floor(doj - 25569);
    var utc_value = utc_days * 86400;                                        
var date_info = new Date(utc_value * 1000);
var fractional_day = doj - Math.floor(doj) + 0.0000001;

var total_seconds = Math.floor(86400 * fractional_day);

var seconds = total_seconds % 60;

total_seconds -= seconds;
var hours = Math.floor(total_seconds / (60 * 60));
var minutes = Math.floor(total_seconds / 60) % 60;
const doj1= (date_info.getFullYear(),date_info.getMonth(), date_info.getDate()+1);
console.log("k");
 var d1=(date_info.getFullYear()+'-'+(date_info.getMonth()+1) +'-'+date_info.getDate())
console.log('j');
console.log(d1);
    //for doj
       empValues+=`('${item.username}','${item.password}','${item.first_name}','${item.middle_name}','${item.last_name}','${item.gender}',
       '${d}','${item.mobile_no}','${item.alternate_mobile_no}','${item.personal_email}','${item.professional_email}','${item.address}',
       '${item.aadhaar}','${item.pan}','${item.qualification}','${d1}','${item.designation_id}','${item.location_id}'),`;
   });
   
   //to remove cooma from end of string
   empValues=empValues.replace(/,\s*$/,"");

   const query = `INSERT INTO emp(username,password,first_name,middle_name,last_name,gender,dob,mobile_no,alternate_mobile_no,
    personal_email,professional_email,address,aadhaar,pan,qualification,doj,designation_id,location_id)
           VALUES ${empValues}`; 
           
       console.log(query);
   pool.query(query).then(result=>{
       console.log(result);
       if( result.rowCount ){                                        
           res.status(200).json({
               message: 'Post successfully'
           })                                            
       }else{
           res.status(200).json({
               message: 'error'
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


// Update emp
router.put('/update?:id', (req, res)=>{
    // console.log(req.body);
      // const query = `CALL pro_update_emp($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20,$21,$22,$23)`;
           const query = `CALL update_emp($1,$2::text,$3::text,$4::text,$5::text,$6::date,$7::text,$8::text,$9::text,$10::text,
            $11::text,$12::text,$13::text,$14::text,$15::date,$16,$17,$18,$19::text,$20::text,$21,$22
            )`;
          
              const values = [req.query.id, req.body.empCode, req.body.firstName, 
                req.body.lastName, req.body.gender, req.body.dob, req.body.mobileNo,
                req.body.alternateMobileNo, req.body.personalEmail, req.body.professionalEmail,
                req.body.address, req.body.aadhaar, req.body.pan, req.body.qualification, 
                req.body.doj, req.body.designationId, req.body.locationId, 
                req.body.accountNo, req.body.ifsc, req.body.bankName, req.body.slEntitled, req.body.clEntitled
          ];
      pool.query(query, values).then(result=>{
         console.log(req.query);
         console.log(res.result);
          res.status(200).json({
              message:"Update successfully",
              result: result
          });
      })
      .catch(err=>{
          res.status(500).json({ 
              message: "Server error",
              error:err
          });
      });
  });

  
// Delete emp
router.delete('/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM emp WHERE id=$1`
    const values = [req.params.id];
    console.log(res.result);
    pool.query(query,values).then(result=>{
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
});

//get username
//demo
router.post('/usercheck', (req, res)=>{
    console.log(req.body);
    const query =  `SELECT * FROM emp 
    WHERE username=$1`;
    const values = [req.body.username];
console.log(res.result);
pool.query(query, values).then(result=>{
    console.log(result.rows.length);
    if(result.rows.length==1)
    {
    res.status(200).json({
        message: "already",
        result: result.rows
    });
}
else{
    res.status(200).json({
        message: "not",
        result: result.rows
    });
}
})
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});


//get all emp_details
router.get('/allEmp', (req, res)=>{
    console.log(req.userData);
    const query = `select id,emp_code from emp
    where id NOT IN
    (select emp_id from form16 where status='created')`; 
    //const values = [req.userData.companyId];
    
    pool.query(query).then(result=>{
        res.status(200).json({
            message: "fetch",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});

//get all emp_details
router.get('/Empallview', (req, res)=>{
    console.log(req.userData);
    const query = `SELECT e.*,d.name AS desname FROM emp e,designation d
    WHERE  e.designation_id=d.id`; 
    //const values = [req.userData.companyId];
    pool.query(query).then(result=>{
        res.status(200).json({
            message: "fetch",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});


//get all emp_details
router.get('/getallemp', (req, res)=>{
    console.log(req.userData);
    const query = `SELECT id,emp_code from emp where id not in (select emp_id from salary)`; 
    //const values = [req.userData.companyId];
    pool.query(query).then(result=>{
        res.status(200).json({
            message: "fetch",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});


// Get all designation for admin
router.get('/design', (req, res)=>{
    console.log(req.userData);
    const query = `SELECT id,name FROM designation`; 
    //const values = [req.userData.companyId];
    pool.query(query).then(result=>{
        res.status(200).json({
            message: "fetch",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});

// Get all designation for hr
router.get('/hrdesign', (req, res)=>{
    console.log(req.userData);
    const query = `select * from designation where id NOT IN (1,2);`; 
    //const values = [req.userData.companyId];
    pool.query(query).then(result=>{
        res.status(200).json({
            message: "fetch",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});


// Get all location
router.get('/location', (req, res)=>{
    console.log(req.userData);
    const query = `SELECT id,type,name FROM location`; 
    //const values = [req.userData.companyId];
    pool.query(query).then(result=>{
        res.status(200).json({
            message: "fetch",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});

//demo
router.get('/fetchemp?:id', (req, res)=>{
    console.log(req.query);
    const query =  `SELECT e.id,e.dob::text,first_name,middle_name,emp_code,last_name,gender,mobile_no,alternate_mobile_no,designation_id,location_id,
    personal_email,professional_email,address,aadhaar,pan,qualification,doj::text,d.id,b.account_no,b.ifsc,b.bank_name,lv.sl_entitled,lv.cl_entitled
   
    FROM emp e left join designation d on  e.designation_id=d.id
Left join location l on  e.location_id=l.id 
Left join bank b on b.emp_id=e.id 
Left join leave lv on  lv.emp_id=e.id 
    WHERE e.id=$1`;
    const values = [req.query.id];
console.log(res.result);
    pool.query(query, values).then(result=>{
        res.status(200).json({
            message:"show successfully1",
            result: result.rows
        });
    })
    .catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});

// Delete emp
router.delete('/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM emp WHERE id=$1`
    const values = [req.params.id];
    console.log(res.result);
    pool.query(query,values).then(result=>{
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
});

//view imp name
router.get('/vieEmpName?:id', (req, res)=>{
    const query = `SELECT first_name, last_name FROM emp WHERE id=$1`;
    const values = [req.query.id];
        pool.query(query, values).then(result=>{
            res.status(200).json({
                message: "Record fetched",
                result: result.rows
            });
        }).catch(err=>{
            res.status(500).json({
                message: "Server error",
                error:err
            });
        });
    });

    //update login
router.put('/changepassword',checkAuth, (req, res)=>{
    console.log(req);
    const query = `SELECT username, designation_id,first_name,last_name, id FROM emp
                    WHERE id=$1 AND password=$2`;
    const values = [req.userData.employeeId, req.body.oldpassword];
    pool.query(query, values).then(result=>{
                                // console.log(result.rows);
                                if(result.rows.length==1){
                                    const uquery = `UPDATE emp SET password=$1 WHERE id=$2`;
                                    const uvalues = [req.body.newpassword,req.userData.employeeId];
                                    pool.query(uquery, uvalues).then(result=>{
                                        console.log(result);
                                        if( result.rowCount ){                                        
                                            res.status(200).json({
                                                message: 'password changed successfully'
                                            })                                            
                                        }else{
                                            res.status(200).json({
                                                message: 'unable to update password.. Try again'
                                            })
                                        }
                                    }).catch(err=>{
                                        console.log(err);
                                        res.status(500).json({
                                            message: "Server error",
                                            error:err
                                        })
                                    })                          

                                }else{
                                    res.status(200).json({
                                        message: 'old password not match'
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



//get all questions
router.get('/allquestion', (req, res)=>{
    console.log(req.userData);
    const query = `select * from question`; 
    //const values = [req.userData.companyId];
    
    pool.query(query).then(result=>{
        res.status(200).json({
            message: "fetch",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});


//add location
router.post('/addLocation', checkAuth, (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO location(name)
         VALUES ($1)`; 
        
        const values = [req.body.name];
        pool.query(query, values).then(result=>{
            res.status(200).json({
                message:"Post successfully",
                result: result
            });
        })
    .catch(err=>{
        res.status(500).json({ 
            message: "Server error",
            error:err
        });
    });
});

//get all location
router.get('/showlocation', (req, res)=>{
    console.log(req.userData);
    const query = `select * from location`; 
  
    pool.query(query).then(result=>{
        res.status(200).json({
            message: "fetch",
            result: result.rows
        });
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});


    //delete location against id
    router.delete('/del_location/:id', (req, res)=>{
        console.log(req.query);
        const query = `DELETE FROM location WHERE id=$1`
        const values = [req.params.id];
        console.log(res.result);
        pool.query(query,values).then(result=>{
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
    });
    
    
    //update location 
    router.put('/location-update?:id', (req, res)=>{
        console.log(req.query);
        const query = `UPDATE location SET name=$1 WHERE id=$2`;
        const values = [req.body.name,req.query.id];
        console.log(query);
        console.log(values);
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

    //get all location
router.get('/get_sales_person', (req, res)=>{
    console.log(req.userData);
    const query = `select * from emp where designation_id=4`; 
  
    pool.query(query).then(result=>{
        res.status(200).json({
            message: "fetch",
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
