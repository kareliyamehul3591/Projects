const express = require('express');
const router =express.Router();
const pool = require('../middlewares/pool');
const jwt = require('jsonwebtoken');
const nodemailer = require('nodemailer');
const Cryptr = require('cryptr');
const cryptr = new Cryptr('secretKey');
const checkAuth = require('../middlewares/checkAuth');
const config = require('../config/config');

//add lead in calender
router.post('/addlead', (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO lead_info(assign_lead,fname,lname,phone_no,mobile_no,company,email,website,lead_source,
            follow_up,lead_status,industry,rating,annual_revenue,employee_no,street,city,state,postal_code,country,
            description)
            VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17,$18,$19,$20,$21)`; 
        console.log(query);
        const values = [req.body.asignLead,req.body.firstName,req.body.lastName,req.body.phoneNo,req.body.mobileNo,req.body.company,
            req.body.email,req.body.website,req.body.leadSource,req.body.followUp,req.body.leadStatus,req.body.industry,
            req.body.rating,req.body.annualRevenue,req.body.employeeNo,req.body.street,req.body.city,
            req.body.state,req.body.zip,req.body.country,req.body.description];
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
router.get('/fetchlead', (req, res)=>{
    const query = `SELECT * from lead_info ORDER BY id DESC`;
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

    //show all details of lead_info
router.get('/viewsalesemp', (req, res)=>{
    const query = `select first_name,last_name from emp where designation_id='4'`;
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

    //delete lead_info against id
router.delete('/deletelead/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM lead_info WHERE id=$1`
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

// Get lead_info against id
router.get('/viewlead?:id',(req, res)=>{
    const query = `SELECT * FROM lead_info  WHERE id=$1`;
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

//update lead info 
router.put('/updateLead?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE lead_info SET assign_lead=$1,fname=$2,lname=$3,phone_no=$4,mobile_no=$5,company=$6,website=$7,lead_source=$8,
    industry=$9,rating=$10,email=$11,follow_up=$12,lead_status=$13,annual_revenue=$14,employee_no=$15,street=$16,city=$17,state=$18,
    postal_code=$19,country=$20,description=$21 WHERE id=$22`;
    console.log(query)
    const values = [req.body.assignLead,req.body.firstName,req.body.lastName,req.body.phoneNo,req.body.mobileNo,req.body.company,req.body.website,
        req.body.leadSource,req.body.industry,req.body.rating,req.body.email,req.body.followUp,req.body.leadStatus,
        req.body.annualRevenue,req.body.employeeNo,req.body.street,req.body.city,req.body.state,req.body.zip,req.body.country,
        req.body.description,req.query.id];
        console.log(values)
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

//show status
router.get('/getstatus?:status',(req, res)=>{
    const query = `SELECT * from lead_info WHERE lead_status=$1`;
                     
    const values = [req.query.status];
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


    //add lead in calender
router.post('/addorder', (req, res)=>{
    console.log(req.body);
    let date_obj=new Date();
    let month = date_obj.getMonth() + 1;
    
    let invoice_no=(date_obj.getFullYear()+"/0"+month+"/");
        console.log(invoice_no)
    const query = `INSERT INTO order_info(order_name,order_number,order_type,amount,close_date,current_stage,milestone_date,lead_source,delivery_stage,
            description,customer_address)
            VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11)`; 
        console.log(query);
        const values = [req.body.orderName,invoice_no+req.body.orderNumber,req.body.orderType,req.body.amount,req.body.closeDate,
            req.body.currentStage,req.body.milestoneDate,req.body.leadSource,req.body.deliveryStage,req.body.description,req.body.customerAddress
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

//show all details of order
router.get('/fetchorder', (req, res)=>{
    const query = `SELECT * from order_info ORDER BY id DESC`;
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
    
    //delete order_info against id
router.delete('/deleteorder/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM order_info WHERE id=$1`
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

// Get order_info against id
router.get('/vieworder?:id',(req, res)=>{
    const query = `SELECT * FROM order_info  WHERE id=$1`;
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

// Get order_info against customer order no
router.get('/order_no_view?:id',(req, res)=>{
    const query = `SELECT * FROM order_info  WHERE order_number=$1`;
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

//update order info
router.put('/updateOrder?:id', (req, res)=>{
    console.log(req.query);
    
    const query = `UPDATE order_info SET order_name=$1,order_number=$2,order_type=$3,amount=$4,
    close_date=$5,current_stage=$6,lead_source=$7,delivery_stage=$8,description=$9 WHERE id=$10`;
    const values = [req.body.orderName,req.body.orderNumber,req.body.orderType,req.body.amount,req.body.closeDate,
        req.body.currentStage,req.body.leadSource,req.body.deliveryStage,req.body.description,req.query.id];
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

 //add vendor info
 router.post('/addvendor', (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO vendor_info(vendor_name,vendor_address,contact_person,contact_number,email,landline,vendor_code,gst,
            bank_name,account_number,product_category,ifsc_code)
            VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12)`; 
        console.log(query);
        const values = [req.body.vendorName,req.body.vendoraAddress,req.body.contactPerson,req.body.contactNumber,req.body.email,
            req.body.landline,req.body.vendorCode,req.body.gst,req.body.bankName,req.body.accountNumber,req.body.productCategory,
            req.body.ifscCode];
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

//show all details of order
router.get('/fetchvendor', (req, res)=>{
    const query = `SELECT * from vendor_info ORDER BY id DESC`;
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

        //delete vendors against id
router.delete('/deletevendor/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM vendor_info WHERE id=$1`
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

// Get lead_info against id
router.get('/viewvendor?:id',(req, res)=>{
    const query = `SELECT * FROM vendor_info  WHERE id=$1`;
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

//update holiday calender
router.put('/updatevendor?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE vendor_info SET vendor_name=$1,vendor_address=$2,contact_person=$3,contact_number=$4,
    email=$5,landline=$6,vendor_code=$7,gst=$8,bank_name=$9,account_number=$10,product_category=$11,ifsc_code=$12 WHERE id=$13`;
    const values = [req.body.vendorName,req.body.vendorAddress,req.body.contactPerson,req.body.contactNumber,req.body.email,
        req.body.landline,req.body.vendorCode,req.body.gst,req.body.bankName,req.body.accountNumber,req.body.productCategory,
        req.body.ifscCode,req.query.id];
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

 //add milestone info
 router.post('/addvendor', (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO milestone_info(order_type,vendor_address,contact_person,contact_number,email,landline,vendor_code,gst,
            bank_name,account_number,product_category,ifsc_code)
            VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12)`; 
        console.log(query);
        const values = [req.body.vendorName,req.body.vendoraAddress,req.body.contactPerson,req.body.contactNumber,req.body.email,
            req.body.landline,req.body.vendorCode,req.body.gst,req.body.bankName,req.body.accountNumber,req.body.productCategory,
            req.body.ifscCode];
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

//add invoice in invoice_info
router.post('/addinvoice', (req, res)=>{
    //console.log(req.body);
    //add invoice_info
    const query = `INSERT INTO invoice_info(order_no,customer_name,customer_address,
        due_date,order_type,payment_term,invoice_date,remark,description,total)
        VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10)RETURNING id`; 
        const values = [req.body.order_number,req.body.order_name,req.body.customer_address,req.body.due_date,
            req.body.order_type,req.body.term_of_payment,req.body.invoice_date,req.body.remark,req.body.description,req.body.total];
    //console.log(query);
    //console.log(values);
    //console.log('hi');
    pool.query(query,values).then(result=>{
        console.log(result.rows[0].id);
        //add invoice details
        console.log('hi');
        if(result.rows.length==1){
            console.log('success')
            let invoiceValues='';
            req.body.order_invoice_info
            .forEach(function(item)
            {
                invoiceValues+=`('${item.invoice_description}','${item.quantity}','${item.unit_price}','${item.gst}',
                '${item.invoice_amount}','${result.rows[0].id}','${req.body.total}'),`;
            });
            //to remove cooma from end of string
            invoiceValues=invoiceValues.replace(/,\s*$/,"");
        
            const uquery = `INSERT INTO invoice_details(invoice_description,quantity,unit_price,gst,invoice_amount,invoice_no,total)
                    VALUES ${invoiceValues}`; 
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

// Get invoice_info against id
router.get('/info_invoice_details?:id',(req, res)=>{
    console.log(req.query.id)
    const query = `SELECT * from invoice_info
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

// Get invoice_info against id
router.get('/details_invoice_info?:id',(req, res)=>{
    console.log(req.query.id)
    const query = `SELECT * from invoice_details
   where invoice_no=${req.query.id}`;
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

//show all details of invoice
router.get('/fetch_invoice', (req, res)=>{
    const query = `SELECT * from invoice_info ORDER BY id DESC`;
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

//delete vendors against id
router.delete('/delete_invoice/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM invoice_info WHERE id=$1`
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

// Get invoice_info against id
router.get('/view_invoice?:id',(req, res)=>{
    const query = `SELECT * FROM invoice_info  WHERE id=$1`;
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



// Get invoice_info against invoice no
router.get('/get_invoice_order?:id',(req, res)=>{
    console.log('h')
    const query = `SELECT invoice_info.order_no,invoice_date,invoice_description,
    customer_name,customer_address,due_date,order_type,payment_term,remark,description,
    invoice_details.total,invoice_description,quantity,unit_price,gst,invoice_amount
    FROM invoice_info
    INNER JOIN invoice_details ON invoice_info.id = invoice_details.invoice_no where invoice_info.id=${req.query.id}`;
    //const values = [req.query.id];
    console.log(query)
        pool.query(query).then(result=>{
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

//update invoice form
router.put('/update_invoice?:id', (req, res)=>{
    //console.log(req.query);
    //console.log(req.body.order_invoice_info);
    //update invoice_info
    const query = `UPDATE invoice_info SET order_no=$1,customer_name=$2,customer_address=$3,due_date=$4,
        order_type=$5,payment_term=$6,invoice_date=$7,remark=$8,description=$9,total=$10 WHERE id=$11`;
    const values = [req.body.order_no,req.body.customer_name,req.body.customer_address,req.body.due_date,
        req.body.order_type,req.body.payment_term,req.body.invoice_date,req.body.remark,req.body.description,
        req.body.total,req.query.id];

    pool.query(query, values).then(result=>{
        console.log('update')
        console.log(req.query.id)
        const dquery = `DELETE FROM invoice_details WHERE invoice_no=${req.query.id}`;
        pool.query(dquery).then(result=>{
            console.log('delete')
            console.log(req.query.id)
            let invoiceValues='';
             req.body.order_invoice_info
             .forEach(function(item)
             {
                 invoiceValues+=`('${item.invoice_description}','${item.quantity}','${item.unit_price}','${item.gst}',
                 '${item.invoice_amount}','${req.body.total}','${req.query.id}'),`;
             });
             //to remove cooma from end of string
            invoiceValues=invoiceValues.replace(/,\s*$/,"");
            const uquery = `INSERT INTO invoice_details(invoice_description,quantity,unit_price,gst,invoice_amount,total,invoice_no)
            VALUES ${invoiceValues}`; 
        
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

//add customer in customer_info
router.post('/addcustomer', (req, res)=>{
    console.log(req.body);
        const query = `INSERT INTO customer_info(lead_id,created_by,title,first_name,contact_number_1,contact_number_2,
            email,website,birth_date,anniversary_date,mailing_address,mailing_street,city,state,zip_code,country,description)
            VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11,$12,$13,$14,$15,$16,$17)`; 
        console.log(query);
        const values = [req.body.leadId,req.body.createdBy,req.body.title,req.body.firstName+" "+req.body.lastName,req.body.contactNumber1,
            req.body.contactNumber2,req.body.email,req.body.website,req.body.birthDate,req.body.anniversaryDate,req.body.mailingAddress,req.body.mailingStreet,
            req.body.city,req.body.state,req.body.zipCode,req.body.country,req.body.description];
            console.log(values);
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

//show all details of customer
router.get('/fetchcustomer', (req, res)=>{
    const query = `SELECT * from customer_info ORDER BY id DESC`;
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

    //delete customers against id
router.delete('/deletecustomer/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM customer_info WHERE id=$1`
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

// Get customer_info against id
router.get('/viewcustomer?:id',(req, res)=>{
    const query = `SELECT * FROM customer_info  WHERE id=$1`;
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

//// Get customer_info against lead_id
router.get('/view_lead_customer?:lead_id',(req, res)=>{
    console.log('hiii')
    const query = `SELECT * FROM customer_info  WHERE lead_id=$1`;
    const values = [req.query.lead_id];
    console.log(query);
    console.log(values)
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

//get customer details against customer name
router.get('/customer_addres_view?:id',(req, res)=>{
    const query = `SELECT * FROM customer_info  WHERE first_name=$1`;
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

//show all customer
router.get('/getcustomer', (req, res)=>{
    const query = `SELECT  * from customer_info ORDER BY id DESC`;
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

//add milestone in milestone_info
router.post('/addmilestone', (req, res)=>{
    console.log(req.body);
    //loop
    let date_obj=new Date();
    let invoice_no=(date_obj.getDate()+"-"+date_obj.getMonth()+"-"+date_obj.getFullYear()+"-"+date_obj.getHours()+"-"+date_obj.getMinutes()+"-"+date_obj.getSeconds());
    console.log(invoice_no)
    let milestonValues='';
    req.body.milstoneDetails
    .forEach(function(item)
    {
        milestonValues+=`('${invoice_no}','${req.body.order_type}','${item.milestone_name}','${item.description}',${(item.send_alert != '') ? item.send_alert : false}
        ),`;
    });
    //to remove cooma from end of string
    milestonValues=milestonValues.replace(/,\s*$/,"");

        const query = `INSERT INTO milestone_info(invoice_no,order_type,milestone_name,description,send_alert)
            VALUES ${milestonValues}`; 
        console.log(query);
        pool.query(query).then(result=>{
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

//show all milestone details
router.get('/getmilestone', (req, res)=>{
    const query = `select order_type from milestone_info group by order_type ` ;
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

// Get milestone_info against order_type
router.get('/viewmilestone?:id',(req, res)=>{
    const query = `SELECT * FROM milestone_info WHERE order_type=$1`;
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

// Get milestone_info against order_type
router.get('/get_milestone_det?:id',(req, res)=>{
    const query = `SELECT milestone_name FROM milestone_info WHERE order_type=$1`;
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

//delete milestone_info against id
router.delete('/deletemilestone/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM milestone_info WHERE id=$1`
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

//delete milestone_info against order type
router.delete('/milestone_delete_ordertype/:order_type', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM milestone_info WHERE order_type=$1`
    const values = [req.params.order_type];
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

//update milestone info
router.put('/editmilestone?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE milestone_info SET milestone_name=$1,description=$2,send_alert=$3 WHERE id=$4`;
    const values = [req.body.milestone_name,req.body.description,req.body.send_alert,req.query.id];
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



//show all purchase request details
router.get('/fetch_purchase_req', (req, res)=>{
    const query = `SELECT  * from purchase_req_info ORDER BY id DESC`;
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

    



//delete purchase_req_info against id
router.delete('/delete_purchase/:id', (req, res)=>{
    console.log(req.query);
    const query = `DELETE FROM purchase_req_info WHERE id=$1`
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

//update customer info
router.put('/update_customer?:id', (req, res)=>{
    console.log(req.query);
    const query = `UPDATE customer_info SET title=$1,first_name=$2,contact_number_1=$3,
    contact_number_2=$4,email=$5,website=$6,birth_date=$7,anniversary_date=$8,mailing_address=$9,mailing_street=$10,city=$11,state=$12,
    zip_code=$13,country=$14,description=$15 WHERE id=$16`;
    const values = [req.body.title,req.body.firstName,req.body.contactNumber1,req.body.contactNumber2,
        req.body.email,req.body.website,req.body.birthDate,req.body.anniversaryDate,req.body.mailingAddress,req.body.mailingStreet,req.body.city,
        req.body.state,req.body.zipCode,req.body.country,req.body.description,req.query.id];
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


 
module.exports = router;