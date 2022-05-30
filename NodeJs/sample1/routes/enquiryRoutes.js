const express = require('express');
const router =express.Router();
const pool = require('../middlewares/pool');
const jwt = require('jsonwebtoken');
const nodemailer = require('nodemailer');
const Cryptr = require('cryptr');
const cryptr = new Cryptr('secretKey');
const checkAuth = require('../middlewares/checkAuth');
const config = require('../config/config');

//show all enquiry type
router.get('/showenquiry-type', (req, res)=>{
    console.log(req.query);
    const query =  `SELECT * FROM enquiry_type`;

console.log(res.result);
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
});

//show all reference
router.get('/show-reference', (req, res)=>{
    console.log(req.query);
    const query =  `SELECT * FROM reference`;

console.log(res.result);
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
});

//show all enquiry type
router.get('/show-state', (req, res)=>{
    console.log(req.query);
    const query =  `SELECT * FROM state`;

console.log(res.result);
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
});

//add internship
router.post('/add-intern', checkAuth, (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO enquiry(emp_id,reference_id,name,mobile_no,email,edu_quali,address,fees,status,
            enquiry_type_id
            )
         VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,'1')`; 
        
        const values = [req.userData.employeeId,req.body.reference,req.body.studName,req.body.contactNo,req.body.email,
            req.body.qualification,req.body.address,req.body.fees,req.body.status];
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

//add project
router.post('/project-add', checkAuth, (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO enquiry(emp_id,reference_id,project_name,name,mobile_no,email,company_name,address,status,
            enquiry_type_id
            )
         VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,2)`; 
        
        const values = [req.userData.employeeId,req.body.reference,req.body.projectName,req.body.custName,req.body.contactNo,req.body.email,
            req.body.companyName,req.body.address,req.body.status];
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

//add RECURITMENT
router.post('/recuritadd', checkAuth, (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO enquiry(emp_id,mobile_no,email,company_name,address,ctc,
            designation,gstin,state_id,po,status,enquiry_type_id)
         VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,3)`; 
        
        const values = [req.userData.employeeId,req.body.contactNo,req.body.email,
            req.body.companyName,req.body.address,req.body.ctc,req.body.design,req.body.gstin,
            req.body.state,req.body.purchaseOrder,req.body.status];
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



//add staffing
router.post('/staffadd', checkAuth, (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO enquiry(emp_id,mobile_no,email,company_name,address,ctc,
            salary_per_month,designation,status,enquiry_type_id)
         VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,4)`; 
        
        const values = [req.userData.employeeId,req.body.contactNo,req.body.email,
            req.body.companyName,req.body.address,req.body.ctc,req.body.salary,req.body.design,
            req.body.status
            ];
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

//show all enquiry table
router.get('/viewenquiry', (req, res)=>{
    console.log(req.query);
    const query =  `select e.*,et.name AS enquiry_name
    from enquiry e,enquiry_type et
    where e.enquiry_type_id=et.id`;

console.log(res.result);
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
});


//show enquiry against id
router.get('/fetchenquiry?:id', (req, res)=>{
    const query = `select e.*,rf.name AS ref_name,st.name AS st_name
    from enquiry e LEFT JOIN state st ON e.state_id=st.id
	LEFT JOIN reference rf ON  e.enquiry_type_id=rf.id
    where  e.id=$1`;
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

    //delete enquiry details
    
router.delete('/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM enquiry WHERE id=$1`
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
module.exports = router;