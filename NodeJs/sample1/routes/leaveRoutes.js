///leave///
const express = require('express');
const router =express.Router();
const pool = require('../middlewares/pool');
const jwt = require('jsonwebtoken');
const nodemailer = require('nodemailer');
const Cryptr = require('cryptr');
const cryptr = new Cryptr('secretKey');
const checkAuth = require('../middlewares/checkAuth');
const config = require('../config/config');



//add holiday in calender
router.post('/addholiday', (req, res)=>{
    console.log(req.body);
        const query = "INSERT INTO holidays(date,name) VALUES ($1,$2)"; 
        console.log(query);
        const values = [req.body.date,req.body.name];
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

//show holiday
router.get('/showholidayAll', (req, res)=>{
    const query = `SELECT id,name,date::text from holidays`;
    pool.query(query).then(result=>{
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

    //delete holiday against id
router.delete('/holiday/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM holidays WHERE id=$1`
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


//update holiday calender
router.put('/updatecalender?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE holidays SET name=$1,date=$2 WHERE id=$3`;
    const values = [req.body.name,req.body.date,
        req.query.id];
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

//add leave

router.post('/addleave', checkAuth, (req, res)=>{
    console.log(req.body);
        const query = "INSERT INTO leave_appli(from_date,to_date,type,reason,emp_id) VALUES ($1,$2,$3,$4,$5)"; 
        
        const values = [req.body.fromDate,req.body.toDate,req.body.type,req.body.reason, req.userData.employeeId];
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

// Get all apply leave detals 
router.get('/showleave',checkAuth, (req, res)=>{
    console.log(req.userData);
    const query = `SELECT  l.*,l.from_date::text,l.to_date::text FROM leave_appli l where emp_id=$1
    ORDER BY id DESC`; 
    const values = [req.userData.employeeId];
    pool.query(query,values).then(result=>{
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

///show my Leaves Details
router.get('/viewLeaveDetails?:username',(req, res)=>{
    const query = `SELECT e.id, e.emp_code, sl_taken, cl_taken, sl_entitled-sl_taken AS sl_balance, cl_entitled-cl_taken AS cl_balance
                    FROM emp e JOIN leave l ON l.emp_id=e.id WHERE username=$1`;
    const values = [req.query.username];
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

     //show All emp Leave Appli  FOR FINACNCE PERSON ONLY CAN SEE
        router.get('/lookLeaveAppliAll', (req, res)=>{
            const query = `SELECT e.id AS eid, e.emp_code, e.first_name,la.status, e.last_name, d.name AS desgn, la.id, la.type, la.from_date::text, la.to_date::text, la.total_days, la.reason
            FROM emp e LEFT JOIN designation d ON e.designation_id=d.id
            JOIN leave_appli la ON la.emp_id=e.id
            WHERE la.status='Applied' OR la.status='Accepted' `;
            pool.query(query).then(result=>{
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

            //show All emp Leave Appli  FOR HR PERSON ONLY CAN SEE
        router.get('/LeaveAppliAlllook', (req, res)=>{
            
            const query = `SELECT e.id AS eid, e.emp_code, e.first_name,la.status, e.last_name,d.id AS dname, d.name AS desgn, la.id, la.type, la.from_date::text, la.to_date::text, la.total_days, la.reason
            FROM emp e LEFT JOIN designation d ON e.designation_id=d.id
            JOIN leave_appli la ON la.emp_id=e.id
            WHERE d.id!=2 AND (la.status='Applied' OR la.status='Accepted') `;

            
            pool.query(query).then(result=>{
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

     //reject leave
  router.put('/rejectLeave?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE leave_appli SET status=$1 WHERE id=$2`;
    const values = [req.body.status,
        req.query.id];
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


//accept emp leave
router.post('/accept',checkAuth, (req, res)=>{
    const query = `CALL accept_leaves_appli ($1,$2,$3,$4)`;
    const values = [req.body.id, req.body.type, req.body.totalDays, req.body.eid];
    console.log(values);
    pool.query(query, values).then(result=>{
        res.status(200).json({
            message:"Leave Accepted successfully",
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

//show all emp Leave Details
router.get('/catchLeaveDetailsAll', (req, res)=>{
    const query = `SELECT e.id, e.emp_code, e.first_name, e.last_name, d.name AS desgn, sl_taken, cl_taken, sl_entitled-sl_taken AS sl_balance, cl_entitled-cl_taken AS cl_balance
                    FROM emp e JOIN leave l ON l.emp_id=e.id
                    JOIN designation d ON e.designation_id=d.id`;
    pool.query(query).then(result=>{
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

    //get all my_attend
router.get('/checkattend',checkAuth, (req, res)=>{
    console.log(req.userData);
    const query = `SELECT * FROM timesheet
    WHERE emp_id = $1 AND date::text=TO_CHAR (CURRENT_DATE, 'YYYY-MM-DD') AND status = '0'`; 
    const values = [req.userData.employeeId];
    
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
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});

//add attendance
router.post('/addAttend',checkAuth, (req, res)=>{
    console.log(req.body);
        const query = "INSERT INTO timesheet(check_in,date,attendance,status,emp_id) VALUES ($1,$2,'P','1',$3)"; 
        
        const values = [req.body.checkIn,req.body.date,req.userData.employeeId];
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


//check attend status exist or not today
router.get('/fetchvalue', checkAuth, (req, res)=>{
    console.log(req.userData);
    const query = `SELECT * FROM timesheet
                    WHERE emp_id = $1 AND date=CURRENT_DATE AND status='0'`; 
    const values = [req.userData.employeeId];
    
    pool.query(query, values).then(result=>{
        console.log(result.rows.length);
        if(result.rows.length==1)
        {
        res.status(200).json({
            message: "already exist",
            result: result.rows
        });
    }
    else{
        res.status(200).json({
            message: "not",
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
//


//check attend status exist or not today
// router.get('/fetchvalue?:emp_id', checkAuth, (req, res)=>{
//     console.log(req.userData);
//     const query = `select * FROM timesheet
//     where date::text=TO_CHAR (CURRENT_DATE, 'YYYY-DD-MM')AND emp_id = $1 AND status='1'
//     `; 
//     const values = [req.query.emp_id];
//     console.log(query);
//     pool.query(query, values).then(result=>{
//         console.log(result.rows.length);
//         if(result.rows.length==1)
//         {
//         res.status(200).json({
//             message: "already exist",
//             result: result.rows
//         });
//     }
//     else{
//         res.status(200).json({
//             message: "not",
//             result: result.rows
//         });
//     }
//     }).catch(err=>{
//         res.status(500).json({
//             message: "Server error",
//             error:err
//         });
//     });
// });


//get all my_attend
router.get('/showAllattend',(req, res)=>{
    console.log(req.userData);
    const query = `SELECT e.emp_code, e.first_name::text || ' '::text || e.last_name::text AS fullname,a.date::text,a.check_in,a.check_out,a.attendance,a.worked_hours,a.activity,a.emp_id,a.id
    FROM timesheet a, emp e
    WHERE a.emp_id=e.id ORDER BY id DESC`; 
    // const query = `SELECT e.emp_code, e.first_name::text || ' '::text || e.last_name::text AS fullname,a.* 
    // FROM attendance a, emp e
    // WHERE a.emp_id=e.id`; 
   // const values = [req.userData.employeeId];
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


//delete timesheet

router.delete('/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM timesheet WHERE id=$1`
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

// Update chekout attendance
// router.put('/updateAttend?:date',checkAuth, (req, res)=>{
//     console.log(req.query);
    
//     const query = `UPDATE timesheet SET activity=$1,check_out=$2,status='0'
//                     WHERE  emp_id=$3 AND date = $4`
//     const values = [req.body.activity, 
//                     req.body.checkOut,
//                     req.userData.employeeId, 
//                     req.query.date];
//     pool.query(query, values).then(result=>{
//         console.log(query, values);
//         res.status(200).json({
//             message:"Updated successfully",  
//             result: result.rows
//         });
//     }).catch(err=>{
//         res.status(500).json({
//             message: "Server error",
//             error:err
//         });
//     });
// });

router.put('/updateAttend?:date',checkAuth, (req, res)=>{
    //const query = `call check_out_timeshheet($1,$2,$3,$4)`;
    const query = `call check_out_timeshheet($1,$2,$3,$4::timestamp)`;
   const values = [req.userData.employeeId,req.query.date,req.body.activity,req.body.checkOut];
   
    console.log(values);
    pool.query(query, values).then(result=>{
        res.status(200).json({
            message:"Updated successfully",
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


//get all my_attend
router.get('/ShowAttend',checkAuth,(req, res)=>{
    console.log(req.userData);
    const query = `SELECT date::text,check_in::time,check_out::time,worked_hours,activity,attendance FROM timesheet
                    WHERE emp_id = $1`; 
    const values = [req.userData.employeeId];
    pool.query(query, values).then(result=>{
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


//get  my_attend  aginst id
router.get('/getattend?:id',(req, res)=>{
    console.log(req.userData);
    const query = `SELECT date::text,check_in::time,check_out::time FROM timesheet
                    WHERE id = $1`; 
    const values = [req.query.id];
    pool.query(query, values).then(result=>{
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

//update employee attendance accrd to id
router.put('/editempAtd?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE timesheet SET check_in=$1,check_out=$2,date=$3 WHERE id=$4`
    const values = [req.body.checkIn, 
                    req.body.checkOut,
                    req.body.date,
                    req.query.id
                    ];
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

//show all emp Leave Details
router.get('/formshow16', (req, res)=>{
    const query = `SELECT e.id AS eid, e.emp_code,e.first_name,e.last_name,fr.id,d.name AS desgn,fr.form16_pdf_path
    FROM emp e LEFT JOIN designation d ON e.designation_id=d.id
    JOIN form16 fr ON fr.emp_id=e.id`;
    pool.query(query).then(result=>{
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

    //show form16
    router.get('/getform16?:year',checkAuth, (req, res)=>{
        console.log(req.userData);
        const query = `SELECT * from form16 where emp_id=$1 AND year=$2`; 
        const values = [req.userData.employeeId,req.query.year];
        pool.query(query,values).then(result=>{
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

    //show form16
    router.get('/yearform16',checkAuth, (req, res)=>{
        console.log(req.userData);
        const query = `SELECT * from form16 where emp_id=$1`; 
        const values = [req.userData.employeeId];
        pool.query(query,values).then(result=>{
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
    
//show emp details of profile
router.get('/profileview', checkAuth,(req, res)=>{
    console.log(req.query);
    const query =  `SELECT e.*,d.name AS desname FROM emp e,designation d
    WHERE  e.designation_id=d.id AND e.id=$1`;
    const values = [req.userData.employeeId];
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
module.exports = router;
