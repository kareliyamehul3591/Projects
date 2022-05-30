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

// Get note_info against id
router.get('/viewnote?:id',(req, res)=>{
    const query = `SELECT * FROM note_info  WHERE id=$1`;
    const values = [req.query.id];
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

//update term info 
router.put('/updatenote?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE note_info SET note=$1 WHERE id=$2`;
     
    const values = [req.body.note,req.query.id];
    pool.query(query, values).then(result=>{
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

//add office-address in office_add_info
router.post('/add-address', (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO office_add_info(address)
            VALUES ($1)`; 
        console.log(query);
        const values = [req.body.address];
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

// Get address_info against id
router.get('/viewaddress?:id',(req, res)=>{
    const query = `SELECT * FROM office_add_info  WHERE id=$1`;
    const values = [req.query.id];
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

//update address info 
router.put('/update-address?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE office_add_info SET name=$1,address=$2 WHERE id=$3`;
     
    const values = [req.body.name,req.body.address,req.query.id];
    pool.query(query, values).then(result=>{
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

//add register-address in register_add_info
router.post('/add-register', (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO register_add_info(address)
            VALUES ($1)`; 
        console.log(query);
        const values = [req.body.address];
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

// Get register_add_info against id
router.get('/viewregister?:id',(req, res)=>{
    const query = `SELECT * FROM register_add_info  WHERE id=$1`;
    const values = [req.query.id];
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

//update register address info 
router.put('/update-register?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE register_add_info SET address=$1 WHERE id=$2`;
     
    const values = [req.body.address,req.query.id];
    pool.query(query, values).then(result=>{
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
//add term & condition in term_info
router.post('/add-term', (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO term_info(term)
            VALUES ($1)`; 
        console.log(query);
        const values = [req.body.term];
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

//show all details of lead_info
router.get('/fetchterm', (req, res)=>{
    const query = `SELECT * from term_info ORDER BY id DESC`;
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

//update term info 
router.put('/updateterm?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE term_info SET term=$1 WHERE id=$2`;
     
    const values = [req.body.term,req.query.id];
    pool.query(query, values).then(result=>{
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

//delete term_info against id
router.delete('/deleteterm/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM term_info WHERE id=$1`
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

//add registeration in registeration_info
router.post('/add-registeration', (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO registeration_info(register)
            VALUES ($1)`; 
        console.log(query);
        const values = [req.body.register];
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

// Get registeration_info against id
router.get('/registeration_view?:id',(req, res)=>{
    const query = `SELECT * FROM registeration_info  WHERE id=$1`;
    const values = [req.query.id];
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


//update registeration_info info 
router.put('/registeration-update?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE registeration_info SET register=$1 WHERE id=$2`;
     
    const values = [req.body.register,req.query.id];
    pool.query(query, values).then(result=>{
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

// Get logo against id
router.get('/logo-view?:id',(req, res)=>{
    const query = `SELECT * FROM logo_info  WHERE id=$1`;
    const values = [req.query.id];
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

//update logo info 
router.put('/logo-change?:id', upload.single('logo_form'),(req, res)=>{
    console.log(req.query);
    const query = `UPDATE logo_info SET logo=$1 WHERE id=$2`;
     
    const values = [req.file.path,req.query.id];
    pool.query(query, values).then(result=>{
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

//add form16

router.post('/upload-logo', upload.single('logo_form'),(req, res) => {
    console.log(req.body);
    // console.log(req.file);
    
    const query = `INSERT INTO logo_info(logo)
         VALUES ($1)`;

    const values = [req.file.path];
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
module.exports = router;