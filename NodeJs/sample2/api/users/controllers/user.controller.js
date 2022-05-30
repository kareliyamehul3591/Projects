const { genSaltSync, hashSync, compareSync } = require('bcrypt');
const { create, updatepassword, getuseremailbyid, getdatabyid, updateuser, getdataprofile, email_check } = require('../models/user.service');
var uuid = require('uuid');
const { email } = require('../../../controllers/email.controller');
const { insertcreatelink, getdatabytoken, deletetoken } = require('../models/userconfirmationlink.service');

module.exports = {
    ragistrationemail: (req, res) => {
        const body = req.body;
        body.status= "2";
        body.role = "end_user";
        body.unique_id = uuid.v4();
        console.log(body);
        create(body, (error, result) => {
        console.log(result);

            if (error) {
                console.log(error);
                return res.status(500).json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                const linkdata = { "user_id": result.insertId, "token": uuid.v4(0) };
                insertcreatelink(linkdata, (error, result) => {
                    console.log(error);
                    if (!error) {
                        function emailHtml (data){
                            return `<!doctype html>
                            <html>
                            <head>
                            <meta charset="utf-8">
                            <title>:: ShivYog ::</title>
                            <style>
                              body{box-sizing: border-box; padding: 0; margin: 0;}
                              table{width: 100%; max-width: 700px; font-family: Helvetica; font-size: 16px; margin: 0px auto;}
                            </style>
                              </head>
                            
                            <body>
                              <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 700px; font-family: Helvetica; font-size: 16px; margin: 0px auto;">
                                <thead><tr><td style="padding: 15px 0;"><img src="https://api.ashoresystems.ws/public/assets/images/brand.jpg"></td></tr></thead>
                                <tbody><tr><td style="padding: 15px 0; border-top:1px solid #000">
                                  <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.f_name}</strong>,</p><p style="color: #666;">Welcome to ShivYog Cosmic Therapy Platform.</p><p style="color: #666;  line-height: 26px;">
                            To activate your account, please click the link below and follow the steps as directed.
                            <a href="${data.url}" target="_blank" rel="noopener noreferrer" style="color: #000; text-decoration: underline;">Generate Password</a></p><p style="color: #666; line-height: 26px;">
                            The link will expire within 5 days.<br/>
                            For further questions or assistance please email <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a></p>
                                  </td></tr>
                                  </tbody>
                                <tfoot>		
                                  <tr><td><p style="color: #666; line-height: 26px;">Thank you,<br/>ShivYog Cosmic Therapy Team<br/><a href="#">www.shivyogcosmictherapy.com</a></p></td></tr>
                            
                                  <tr><td style="padding: 15px 0; border-top:1px solid #000">Please add <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a> to your address book to ensure inbox delivery.</td></tr>
                              </tfoot>
                              </table>
                              
                            </body>
                            </html>
                            `;
                          }
                           body.url = process.env.AAP_URL + "generatepassword/" + linkdata.token ;
                        var email_data = {
                            "from": "demo@gmail.com",
                            "to": body.email,
                            "subject": "User Ragistration email",
                            "text": "Hello " + body.email,
                            "html": emailHtml(body)
                        };
                        email(email_data);
                    }
                });
                return res.status(200).json({
                    success: 1,
                    message: "Mali Send successfully"
                });
            }
        });
    },
    getdatabytoken: (req, res) => {
        const token = req.params.token;
        if (token) {
            getdatabytoken(token, (error, result) => {
                if (error) {
                    console.log(error);
                    return res.json({
                        success: 0,
                        message: "Database connection errror"
                    });
                } else {
                    if (result) {
                        if (new Date().getTime() < new Date(new Date(result.created_at).setHours(new Date(result.created_at).getHours() + 1)).getTime()) {
                            getuseremailbyid(result.user_id, (error, result) => {
                                if (error) {
                                    return res.json({
                                        success: 0,
                                        message: "Database connection errror"
                                    });
                                } else {
                                    result.token = token
                                    return res.json({
                                        success: 1,
                                        data: result
                                    });
                                }
                            });
                        } else {
                            return res.json({
                                success: 0,
                                message: "Sorry, your Token have expired. Please try again."
                            });
                        }
                    } else {
                        return res.json({
                            success: 0,
                            message: "This Token is Invalid"
                        });
                    }
                }
            });
        } else {
            return res.json({
                success: 0,
                message: "Token is required"
            });
        }
    },
    generatepassword: (req, res) => {
        const body = req.body;
        const salt = genSaltSync(10);
        body.password = hashSync(body.password, salt);
        console.log(body);
        getdatabytoken(body.token, (error, result) => {
            if (result) {
                if (result.user_id == body.user_id) {
                    updatepassword(body, (error, result) => {
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
                                    message: "Record Not Found"
                                });
                            } else {
                                deletetoken(body.token, (error, result) => {
                                    if (error) {
                                        return res.json({
                                            success: 0,
                                            message: "Database connection errror"
                                        });
                                    }
                                });
                                return res.json({
                                    success: 1,
                                    message: "Password Generator successfully"
                                });
                            }
                        }
                    });
                } else {
                    return res.json({
                        success: 0,
                        message: "Invalid User"
                    });
                }
            }else{
                return res.json({
                    success: 0,
                    message: "This Token is Invalid"
                });  
            }

        });
    },
	resetpassword: (req, res) => {
		const body = req.body;
		getdatabyid(body.user_id,(error, result) => {
			 if (error) {
                            console.log(error);
                            return res.json({
                                success: 0,
                                message: "Database connection errror"
                            });
                        } else {
                            if (result == "") {
                                return res.json({
                                    success: 0,
                                    message: "Record Not Found"
                                });
                            } else {
								const results = compareSync(body.old_password, result.password);
								if (results) {
									const salt = genSaltSync(10);
									body.password = hashSync(body.new_password, salt);
									updatepassword(body,(error, result) => {
										 if (error) {
											console.log(error);
											return res.json({
												success: 0,
												message: "Database connection errror"
											});
										}else{
											 if (!result) {
												return res.json({
													success: 0,
													message: "Something has Wrong"
												});
											} else {
												return res.json({
													success: 1,
													message: "Password Updated Successfully"
												});
											}
										}
									});
								} else {
									return res.json({
										success: 0,
										message: "Please Enter Correct Old Password"
									});
								}
                            }
                        }
		});

	},
    updateprofile: (req, res) => {
		const body = req.body;
        console.log(body);
        updateuser(body,(error, result1) => {
                    if (error) {
                        console.log(error);
                        return res.json({
                            success: 0,
                            message: "Database connection errror"
                        });
                    }else{
                        if (!result1) {
                            return res.json({
                                success: 0,
                                message: "Something has Wrong"
                            });
                        } else {
                            getdataprofile(body.user_id,(error, result) => {
                                if (!error) {
                                    return res.json({
                                        success: 1,
                                        message: "User Profile updated successfully",
                                        data: result
                                    });
                                }
                            });
                        }
                    }
                });          
    },
    email_check: (req, res) => {
        const body = req.body;
        console.log(body);
        email_check(body.email, (error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            }else{
                if(result.count_email<1){
                    return res.json({
                        success: 1,
                        message: "Email Is Valid ",
                        data: result.count_email
                    });
                }else{
                    return res.json({
                        success: 0,
                        message: "Email Is Aliready Exist ",
                        data: result.count_email
                    });
                }
            } 
        });
        
      }
};