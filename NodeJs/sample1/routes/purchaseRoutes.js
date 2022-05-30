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

//add quotation in customer_info
router.post('/addquotation', (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO quotation_info(pr_no,vendor,sale_date,quantity,amount,remark)
            VALUES ($1,$2,$3,$4,$5,$6)`; 
        console.log(query);
        const values = [req.body.prNo,req.body.vendor,req.body.saleDate,req.body.quantity,req.body.amount,req.body.remark];
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

//show all details of quotation
router.get('/fetchquotation', (req, res)=>{
    const query = `SELECT * from quotation_info ORDER BY id DESC`;
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

//update quotation details
router.put('/updatequotation?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE quotation_info SET vendor=$1,sale_date=$2,quantity=$3,amount=$4,
    remark=$5 WHERE id=$6`;
    const values = [req.body.vendor,req.body.sale_date,req.body.quantity,req.body.amount,req.body.remark,
        req.query.id];
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

//delete quoatation against id
router.delete('/deletequotation/:id', (req, res)=>{
    console.log(req.params.id);
    const query = `DELETE FROM quotation_info WHERE id=$1`
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

//add material receipt
router.post('/materialadd', upload.single('form_recpt'),(req, res) => {
    console.log(req.body);
    // console.log(req.file);
    if(!req.file) {
        var query = `INSERT INTO material_rec_info(po_number,vendor,purchase_date,amount,remark)
            VALUES ($1,$2,$3,$4,$5)`; 
       
        var values = [req.body.po_number,req.body.vendor,req.body.purchase_date,req.body.amount,req.body.remark];
    }else{
        var query = `INSERT INTO material_rec_info(po_number,vendor,purchase_date,amount,remark,upload_receipt)
            VALUES ($1,$2,$3,$4,$5,$6)`; 
    
    var values = [req.body.po_number,req.body.vendor,req.body.purchase_date,req.body.amount,req.body.remark,req.file.path];
    }
    //const values = [req.body.emp_id, req.body.year, req.file.path];
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



//show all details of material receipt
router.get('/fetchmaterial', (req, res)=>{
    const query = `SELECT * from material_rec_info ORDER BY id DESC`;
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

 //delete materal receipt against id
router.delete('/deletematerial/:id', (req, res)=>{
    console.log(req.params.id);
    const query = `DELETE FROM material_rec_info WHERE id=$1`
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

//update quotation details
router.put('/materialupdate?:id', upload.single('form_recpt1'),(req, res)=>{
    console.log(req.query);
    console.log(req.body.vendor1);
    console.log(req.file);
    
    if(!req.file) {
        var query = `UPDATE material_rec_info SET vendor=$1,purchase_date=$2,amount=$3,remark=$4 WHERE id=$5`;
      var values = [req.body.vendor,req.body.purchase_date,req.body.amount,req.body.remark,req.query.id];
    }else{
        var query = `UPDATE material_rec_info SET vendor=$1,purchase_date=$2,amount=$3,remark=$4,upload_receipt=$5 WHERE id=$6`;
        var values = [req.body.vendor,req.body.purchase_date,req.body.amount,req.body.remark,req.file.path,req.query.id];
    }   
   
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


//add invoice receipt
router.post('/invoiceadd', upload.single('form_invoice'),(req, res) => {
    console.log(req.body);
    //console.log(req.file.path);
    if(!req.file) {
        var query = `INSERT INTO purchase_invoice_info(po_number,vendor,invoice_date,amount,remark)
        VALUES ($1,$2,$3,$4,$5)`; 
       
        var values = [req.body.po_number,req.body.vendor,req.body.invoice_date,req.body.amount,req.body.remark];
    }else{
        var query = `INSERT INTO purchase_invoice_info(po_number,vendor,invoice_date,amount,remark,upload_receipt)
        VALUES ($1,$2,$3,$4,$5,$6)`; 
       
        var values = [req.body.po_number,req.body.vendor,req.body.invoice_date,req.body.amount,req.body.remark,req.file.path];
    }
    //const values = [req.body.emp_id, req.body.year, req.file.path];
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

//show all details of purchase invoice
router.get('/fetchinvoice', (req, res)=>{
    const query = `SELECT * from purchase_invoice_info ORDER BY id DESC`;
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

  //delete purchase invoice against id
router.delete('/deleteinvoice/:id', (req, res)=>{
    console.log(req.params.id);
    const query = `DELETE FROM purchase_invoice_info WHERE id=$1`
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

//update purchase invoice
router.put('/invoiceupdate?:id', upload.single('form_invoice1'),(req, res)=>{
    console.log(req.query);
    console.log(req.body.vendor1);
    console.log(req.file);
    
    if(!req.file) {
        var query = `UPDATE purchase_invoice_info SET vendor=$1,invoice_date=$2,amount=$3,remark=$4 WHERE id=$5`;
      var values = [req.body.vendor,req.body.invoice_date,req.body.amount,req.body.remark,req.query.id];
    }else{
        var query = `UPDATE purchase_invoice_info SET vendor=$1,invoice_date=$2,amount=$3,remark=$4,upload_receipt=$5 WHERE id=$6`;
        var values = [req.body.vendor,req.body.invoice_date,req.body.amount,req.body.remark,req.file.path,req.query.id];
    }   
   
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

//add purchase request in purchase_req_info
router.post('/add-purchase-req', (req, res)=>{
    console.log(req.body.purchase_request_info);
    const query = `INSERT INTO purchase_req_info(date,request_generated_by,request_generated,request_approved,description,total)
        VALUES ($1,$2,$3,$4,$5,$6)RETURNING id`;
    const values = [req.body.date,req.body.request_generated_by,req.body.request_generated,req.body.request_approved,req.body.description,req.body.total];
    pool.query(query, values).then(result=>{
        console.log(result.rows[0].id);
        if(result.rows.length==1){
            console.log('success')
            let purchaseValues='';
            req.body.purchase_request_info
            .forEach(function(item)
            {
                purchaseValues+=`('${item.purchase_description}','${item.vendor_name}','${item.quantity}','${item.unit_price}',
                '${item.gst}','${item.amount}','${result.rows[0].id}','${req.body.total}'),`;
            });
            //to remove cooma from end of string
            purchaseValues=purchaseValues.replace(/,\s*$/,"");
        
            const uquery = `INSERT INTO purchase_req_details(purchase_description,vendor_name,quantity,unit_price,gst,amount,purchase_no,total)
                    VALUES ${purchaseValues}`; 
                console.log(uquery);
            pool.query(uquery).then(result=>{
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

        }
    }).catch(err=>{
        res.status(500).json({
            message: "Server error",
            error:err
        });
    });
});

// Get purchase_req_details against id
router.get('/get_purchase_info?:id',(req, res)=>{
    console.log(req.query.id)
    const query = `SELECT * FROM purchase_req_details where purchase_no=${req.query.id}`;
   
        pool.query(query).then(result=>{
            console.log(query)
            //console.log(result)
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

// Get purchase_req_info against id
router.get('/view_purchase_details?:id',(req, res)=>{
    console.log(req.query.id)
    const query = `SELECT * FROM purchase_req_info where id=${req.query.id}`;
    //const queryt = `SELECT * FROM invoice_details  WHERE invoice_no=${req.query.id}`;
    //const values = [req.query.id];
        pool.query(query).then(result=>{
            console.log(query)
            //console.log(result)
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

//update purchase request form
router.put('/update_purchase_req?:id', (req, res)=>{
    const query = `UPDATE purchase_req_info SET date=$1,request_generated_by=$2,request_generated=$3,request_approved=$4,
    description=$5,total=$6 WHERE id=$7`;
    const values = [req.body.date,req.body.request_generated_by,req.body.request_generated,req.body.request_approved,
        req.body.description,req.body.total,req.query.id];

    pool.query(query, values).then(result=>{
        console.log('update')
        console.log(req.query.id)
        const dquery = `DELETE FROM purchase_req_details WHERE purchase_no=${req.query.id}`;
        pool.query(dquery).then(result=>{
            console.log('delete')
            console.log(req.query.id)
            let invoiceValues='';
             req.body.purchase_request_info
             .forEach(function(item)
             {
                 invoiceValues+=`('${item.purchase_description}','${item.vendor_name}','${item.quantity}','${item.unit_price}',
                 '${item.gst}','${item.amount}','${req.body.total}','${req.query.id}'),`;
             });
             //to remove cooma from end of string
            invoiceValues=invoiceValues.replace(/,\s*$/,"");
            const uquery = `INSERT INTO purchase_req_details(purchase_description,vendor_name,quantity,unit_price,gst,amount,total,purchase_no)
            VALUES ${invoiceValues}`; 
             console.log(uquery)
        pool.query(uquery).then(result=>{
            console.log("insert");
            console.log(req.query.id);
            if( result.rowCount ){                                        
                res.status(200).json({
                    message: 'Post successfully'
                })                                            
            }else{
                res.status(200).json({
                    message: 'error'
                })
            }
        })
        })
    })  .catch(err=>{
            res.status(500).json({
                message: "Server error",
                error:err
            });
        });
});

//add purchase order in purchase_order info & details
router.post('/addpurchase_order', (req, res)=>{
    //console.log(req.body);
    //add invoice_info
    const query = `INSERT INTO purchase_order_info(vendor_name,payment_term,delivery_date,
        purchase_category,sub_total,discount,net_amt,sgst_tax_per,sgst_tax_amt,cgst_tax_per,cgst_tax_amt,
        igst_tax_per,igst_tax_amt,shipping_amt,other_amt,grand_total)
        VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16)RETURNING id`; 
    const values = [req.body.vendor_name,req.body.term_of_payment,req.body.delivery_date,req.body.purchase_category,
        req.body.sub_total,req.body.discount,req.body.net_amount,req.body.sgst_tax_per,req.body.sgst_tax_amt,req.body.cgst_tax_per,
        req.body.cgst_tax_amt,req.body.igst_tax_per,req.body.igst_tax_amt,req.body.shipping_amt,req.body.other_amt,req.body.grand_total];
    
    pool.query(query,values).then(result=>{
        console.log(result.rows[0].id);
        //add invoice details
        console.log('hi');
        if(result.rows.length==1){
            console.log('success')
            let purchaseValues='';
            req.body.material_info
            .forEach(function(item)
            {
                purchaseValues+=`('${item.name_of_material}','${item.description}','${item.quantity}','${item.unit_rate}',
                '${item.amount}','${result.rows[0].id}'),`;
            });
            //to remove cooma from end of string
            purchaseValues=purchaseValues.replace(/,\s*$/,"");
        
            const uquery = `INSERT INTO purchase_order_details(material_name,description,quantity,unit_price,amount,purchase_order_no)
                    VALUES ${purchaseValues}`; 
                console.log(uquery);
            pool.query(uquery).then(result=>{
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

        }
    })
    .catch(err=>{
        console.log(err)
        res.status(500).json({ 
            
            message: "Server error",
            
            error:err
        });
    });

});

//show all details of purchase_order info
router.get('/fetch_purchase_order_info', (req, res)=>{
    const query = `SELECT * from purchase_order_info ORDER BY id DESC`;
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

// Get purchase_order_info against id
router.get('/details_purchase_order_info?:id',(req, res)=>{
    console.log(req.query.id)
    const query = `SELECT * from purchase_order_info
   where id=${req.query.id}`;
    //const queryt = `SELECT * FROM invoice_details  WHERE invoice_no=${req.query.id}`;
    //const values = [req.query.id];
        pool.query(query).then(result=>{
            console.log(query)
            //console.log(result)
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

// Get purchase_order_info against id
router.get('/purchase_order_details?:id',(req, res)=>{
    console.log(req.query.id)
    const query = `SELECT * from purchase_order_details
   where purchase_order_no=${req.query.id}`;
    //const queryt = `SELECT * FROM invoice_details  WHERE invoice_no=${req.query.id}`;
    //const values = [req.query.id];
        pool.query(query).then(result=>{
            console.log(query)
            //console.log(result)
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

//update purchase order form
router.put('/update_purchase_order?:id', (req, res)=>{
    console.log(req.query.id)
    const query = `UPDATE purchase_order_info SET vendor_name=$1,payment_term=$2,delivery_date=$3,purchase_category=$4,
    sub_total=$5,discount=$6,net_amt=$7,sgst_tax_per=$8,sgst_tax_amt=$9,cgst_tax_per=$10,cgst_tax_amt=$11,igst_tax_per=$12,
    igst_tax_amt=$13,shipping_amt=$14,other_amt=$15,grand_total=$16 WHERE id=$17`;
    const values = [req.body.vendor_name,req.body.payment_term,req.body.delivery_date,req.body.purchase_category,
        req.body.sub_total,req.body.discount,req.body.net_amt,req.body.sgst_tax_per,req.body.sgst_tax_amt,req.body.cgst_tax_per,
        req.body.cgst_tax_amt,req.body.igst_tax_per,req.body.igst_tax_amt,req.body.shipping_amt,req.body.other_amt,req.body.grand_total,
        req.query.id];

    pool.query(query, values).then(result=>{
        console.log('update')
        console.log(req.query.id)
        const dquery = `DELETE FROM purchase_order_details WHERE purchase_order_no=${req.query.id}`;
        pool.query(dquery).then(result=>{
            console.log('delete')
            console.log(req.query.id)
            let purchaseValues='';
             req.body.material_info
             .forEach(function(item)
             {
                purchaseValues+=`('${item.material_name}','${item.description}','${item.quantity}','${item.unit_price}',
                 '${item.amount}','${req.query.id}'),`;
             });
             //to remove cooma from end of string
             purchaseValues=purchaseValues.replace(/,\s*$/,"");
            const uquery = `INSERT INTO purchase_order_details(material_name,description,quantity,unit_price,amount,purchase_order_no)
            VALUES ${purchaseValues}`; 
             console.log(uquery)
        pool.query(uquery).then(result=>{
            console.log("insert");
            console.log(req.query.id);
            if( result.rowCount ){                                        
                res.status(200).json({
                    message: 'Post successfully'
                })                                            
            }else{
                res.status(200).json({
                    message: 'error'
                })
            }
        })
        })
    })  .catch(err=>{
            res.status(500).json({
                message: "Server error",
                error:err
            });
        });
});

//delete purchase order against id
router.delete('/delete_purchase_order/:id', (req, res)=>{
    console.log(req.params.id);
    const query = `DELETE FROM purchase_order_info WHERE id=$1`
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