//salary///////
const express = require('express');
const router = express.Router();
const pool = require('../middlewares/pool');
const jwt = require('jsonwebtoken');
const nodemailer = require('nodemailer');
const Cryptr = require('cryptr');
const cryptr = new Cryptr('secretKey');
const checkAuth = require('../middlewares/checkAuth');
const config = require('../config/config');
const multer = require('multer');
const fs = require('fs');

const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, './uploads/');
    },
    filename: function (req, file, cb) {
        cb(null, (Date.now() + file.originalname));
    },
});

// const fileFilter =(req, file, cb)=>{
//     if(file.mimetype === 'image/jpeg' || 'image/png'){
//         cb(null, true);}
//     else{
//         cb(new Error('file must be in the jpeg or png format format'), false)
//     }
// };

const upload = multer({
    storage: storage,
    limits: { fileSize: 1024 * 1024 * 5 }
});

//Get Month
router.get('/getmonth', checkAuth, (req, res) => {
    console.log(req.userData);
    const query = `SELECT DISTINCT month, to_char(to_timestamp(to_char(month, '999'), 'MM'), 'Mon') FROM salary_slip WHERE emp_id=$1`;
    const values = [req.userData.employeeId];
    pool.query(query, values).then(result => {
        res.status(200).json({
            message: "Record fetched",
            result: result.rows
        });
    }).catch(err => {
        res.status(500).json({
            message: "Server error",
            error: err
        });
    });
});

//Get Year
router.get('/getyear', checkAuth, (req, res) => {
    console.log(req.userData);
    const query = `SELECT DISTINCT year FROM salary_slip WHERE emp_id=$1`;
    const values = [req.userData.employeeId];
    pool.query(query, values).then(result => {
        res.status(200).json({
            message: "Record fetched",
            result: result.rows
        });
    }).catch(err => {
        res.status(500).json({
            message: "Server error",
            error: err
        });
    });
});

//SHOW ALL ADD SALARY DETAILS

router.get('/showSalaryDetailsAll', (req, res) => {
    const query = `SELECT e.id, e.emp_code, e.first_name, e.last_name, d.name AS desgn,s.id AS sid,
    s.basic+s.hra+s.conveyance+s.cea+s.lta+s.medical+s.attire+s.special+s.other AS gross_salary
    FROM emp e JOIN designation d ON e.designation_id=d.id
    JOIN salary s ON s.emp_id=e.id
    `;
    pool.query(query).then(result => {
        res.status(200).json({
            message: "Record fetched",
            result: result.rows
        });
    }).catch(err => {
        res.status(500).json({
            message: "Server error",
            error: err
        });
    });
});

//add salary
router.post('/addsalary', (req, res) => {
    console.log(req.body);
    const query = `INSERT INTO salary(emp_id,basic,hra,conveyance,cea,lta,medical,attire,
            special,other,gross_salary,pt,epf,net_salary)
         VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14)`;

    const values = [req.body.empId, req.body.basicPay, req.body.hra, req.body.conveyance, req.body.cea,
    req.body.lta, req.body.medical, req.body.attire, req.body.special, req.body.other,
    req.body.grossalary, req.body.pt, req.body.epf, req.body.netsalary];
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


//show salary accrd to id
router.get('/ViewSalary?:id', (req, res) => {
    console.log(req.query);
    const query = `SELECT id, emp_id, basic, hra, conveyance, cea, lta, medical, attire, special
    , other,gross_salary,pt, esic, epf, tds
    , basic+hra+conveyance+cea+lta+medical+attire+special+other gross_salary
    FROM  salary WHERE id=$1`;
    const values = [req.query.id];
    console.log(res.result);
    pool.query(query, values).then(result => {
        res.status(200).json({
            message: "show successfully1",
            result: result.rows
        });
    })
        .catch(err => {
            res.status(500).json({
                message: "Server error",
                error: err
            });
        });
});

//upadat salary according to id 
router.put('/updatesalary?:id', (req, res) => {
    console.log(req.query);
    const query = `UPDATE salary
    SET basic=$1,hra=$2,conveyance=$3,cea=$4,lta=$5,medical=$6, attire=$7, special=$8, other=$9, pt=$10
    , esic=$11, epf=$12, tds=$13, advance=$14 WHERE id=$15`;
    const values = [req.body.basic,
    req.body.hra,
    req.body.conveyance,
    req.body.cea,
    req.body.lta,
    req.body.medical,
    req.body.attire,
    req.body.special,
    req.body.other,
    req.body.pt,
    req.body.esic,
    req.body.epf,
    req.body.tds,
    req.body.advance,
    req.query.id
    ];
    pool.query(query, values).then(result => {
        console.log(query, values);
        console.log(req.query);
        console.log(res.result);
        res.status(200).json({
            message: "Updated successfully",
            result: result.rows
        });
    }).catch(err => {
        res.status(500).json({
            message: "Server error",
            error: err
        });
    });
});

//delete salary
router.delete('/:sid', (req, res) => {
    console.log(req.query);
    const query = `DELETE FROM  salary WHERE id=$1`;
    const values = [req.params.sid];
    pool.query(query, values).then(result => {
        res.status(200).json({
            message: "Deleted successfully",
            result: result.rows
        });
    }).catch(err => {
        res.status(500).json({
            message: "Server error",
            error: err
        });
    });
});

//show salary history
router.get('/showsalhistory', (req, res) => {
    console.log(req.query);
    const query = `SELECT e.id, e.emp_code, e.first_name, e.last_name, d.name AS desgn, ss.gross_salary
    ,month AS month_num,to_char(to_timestamp(to_char(month, '999'), 'MM'), 'Mon') AS month,year
	,ss.id AS eid, ss.status
    FROM emp e JOIN designation d ON e.designation_id=d.id
    LEFT JOIN salary_slip ss ON ss.emp_id=e.id
	
	ORDER BY ss.month DESC, ss.year, e.id`;

    console.log(res.result);
    pool.query(query).then(result => {
        res.status(200).json({
            message: "show successfully1",
            result: result.rows
        });
    })
        .catch(err => {
            res.status(500).json({
                message: "Server error",
                error: err
            });
        });
});

//create salary sheet
router.post('/madeSalaryslip', (req, res) => {
    //console.log('hi');
    console.log(req.body);
    const query = "SELECT month,year FROM salary_slip WHERE month=$1 AND year=$2";
    values = [req.body.month, req.body.year];
    console.log(query);
    console.log(values)
    pool.query(query, values).then(result => {
        if (result.rows.length > 0) {
            console.log('hi')
            res.status(200).json({
                message: "data already exists"
            });
        }
        else {
            console.log('hyy')
            const query = "call generate_salary_slip($1,$2)";
            const values = [req.body.month,req.body.year];
            pool.query(query, values).then(result => {
                res.status(200).json({
                    message: "Post successfully",
                    result: result
                });
            })
        }
    }).catch(err => {
        res.status(500).json({
            message: "Server error",
            error: err
        });
    });
});

//show salary history
router.get('/getform', (req, res) => {
    console.log(req.query);
    const query = `select * from form16`;
	 console.log(res.result);
    pool.query(query).then(result => {
        res.status(200).json({
            message: "show successfully1",
            result: result.rows
        });
    })
        .catch(err => {
            res.status(500).json({
                message: "Server error",
                error: err
            });
        });
});
// router.put('/profile',upload.single('profile'), (req, res)=>{    
//     pool.query(`SELECT profile_pic_path FROM retailer
//                 WHERE id= ${req.body.id}`).
//                 then(async(path)=>{
//                     if(path.rows[0].profile_pic_path!==null){
//                         fs.unlink(path.rows[0].profile_pic_path, function (err) {
//                             if (err) throw err;
//                         });
//                     }
//                     const query = `UPDATE retailer SET profile_pic_path=$1 
//                                     WHERE id=$2`;
//                     const values = [req.file.path, req.body.id];
//                     result = await pool.query(query, values);
//                             res.status(200).json({
//                                 result: "Image uploaded successfully",
//                         })
//                 }).
//                 catch(err=>{
//                     console.log(err)
//                         res.status(500).json({
//                             message: "Server error",
//                             error:err
//                     })      
//                 });
// });

//add form16

router.post('/form16add', upload.single('form_16'),(req, res) => {
    console.log(req.body);
    // console.log(req.file);
    
    const query = `INSERT INTO form16(emp_id, year, form16_pdf_path,status)
         VALUES ($1,$2,$3,'created')`;

    const values = [req.body.emp_id, req.body.year, req.file.path];
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


//post month year for create salary slip
router.post('/getsalslip', (req, res)=>{
    console.log(req.query);
    const query =  `select e.emp_code, e.first_name, e.last_name,d.name AS desgn,ss.cl,ss.lwp,ss.payble_days,
    ss.basic, ss.hra, ss.conveyance, ss.cea, ss.lta
    , ss.medical, ss.attire, ss.special, ss.other, ss.gross_salary, ss.pt, ss.esic, ss.epf, ss.tds
    , ss.advance, ss.total_deduction, ss.net_salary,
    ss.month,ss.year from emp e 
    JOIN designation d ON e.designation_id=d.id
    LEFT JOIN salary_slip ss ON ss.emp_id=e.id
    where month=$1 AND year=$2`;
    const values = [req.body.month,req.body.year];

console.log(res.result);
    pool.query(query,values).then(result=>{
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

//show salary slip to paeticular person

router.post('/salaryslipshow',checkAuth, (req, res)=>{
    console.log(req.userData);
    const query = `select e.emp_code, e.first_name, e.last_name,d.name AS desgn,ss.cl,ss.sl,ss.lwp,ss.payble_days,
    ss.basic, ss.hra, ss.conveyance, ss.cea, ss.lta
    , ss.medical, ss.attire, ss.special, ss.other, ss.gross_salary, ss.pt, ss.esic, ss.epf, ss.tds
    , ss.advance, ss.total_deduction, ss.net_salary,
    ss.month,to_char(to_timestamp(to_char(month,'999'),'MM'),'Mon')As month_name,ss.year,ss.holidays,ss.weekly_off from emp e 
    JOIN designation d ON e.designation_id=d.id
    LEFT JOIN salary_slip ss ON ss.emp_id=e.id
    where e.id=$1 AND month=$2 AND year=$3`; 
    const values = [req.userData.employeeId,req.body.month,req.body.year];
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

//Get Month
router.get('/looksalary_slip', (req, res) => {
    console.log(req.userData);
    const query = `SELECT to_char(to_timestamp(to_char(month,'999'),'MM'),'Mon')
    As month_name,month,year,count(*) from salary_slip group by month,year order by month Desc,year Desc
    `;
    // const values = [req.userData.employeeId];
    pool.query(query).then(result => {
        res.status(200).json({
            message: "Record fetched",
            result: result.rows
        });
    }).catch(err => {
        res.status(500).json({
            message: "Server error",
            error: err
        });
    });
});

//Get Month
router.get('/value_salarysheet', (req, res) => {
    console.log(req.userData);
    const query = `select to_char(to_timestamp(to_char(month,'999'),'MM'),'Mon')
    As month_name,month,year,sum(net_salary) from salary_slip group by month,year order by month Desc,year Desc
    `;
    // const values = [req.userData.employeeId];
    pool.query(query).then(result => {
        res.status(200).json({
            message: "Record fetched",
            result: result.rows
        });
    }).catch(err => {
        res.status(500).json({
            message: "Server error",
            error: err
        });
    });
});

//show create salary slip accrd to id
router.post('/Salaryslipview', (req, res)=>{
    console.log(req.query);
    const query =  `select e.emp_code, e.first_name, e.last_name,d.name AS desgn,ss.cl,ss.sl,ss.lwp,ss.payble_days,
    ss.basic, ss.hra, ss.conveyance, ss.cea, ss.lta
    , ss.medical, ss.attire, ss.special, ss.other, ss.gross_salary, ss.pt, ss.esic, ss.epf, ss.tds
    , ss.advance, ss.total_deduction, ss.net_salary,
    ss.month,to_char(to_timestamp(to_char(month,'999'),'MM'),'Mon')As month_name,ss.year,ss.holidays,ss.weekly_off from emp e 
    JOIN designation d ON e.designation_id=d.id
    LEFT JOIN salary_slip ss ON ss.emp_id=e.id
    where month=$1 AND year=$2`;
    const values = [req.body.month,req.body.year];
console.log(res.result);
    pool.query(query,values).then(result=>{
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

//add employee details through excel sheet 
router.post('/add_excel_fitment', (req, res)=>{
   
    console.log(req.body);
   
    let salaryValues='';
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

    //for doj
        salaryValues+=`('${item.emp_id}','${item.basic}','${item.hra}','${item.conveyance}','${item.cea}','${item.lta}','${item.medical}',
        '${item.attire}','${item.special}','${item.other}','${item.gross_salary}','${item.pt}',
        '${item.epf}','${item.total_deduction}','${item.net_salary}'),`;
    });
    
    //to remove cooma from end of string
    salaryValues=salaryValues.replace(/,\s*$/,"");
 
    const query = `INSERT INTO salary(emp_id,basic,hra,conveyance,cea,lta,medical,attire,special,other,
        gross_salary,pt,epf,total_deduction,net_salary)
            VALUES ${salaryValues}`; 
            
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
 
module.exports = router;

