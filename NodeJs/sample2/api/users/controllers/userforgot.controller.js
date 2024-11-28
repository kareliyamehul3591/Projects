const { genSaltSync, hashSync } = require('bcrypt');
const {updatepassword, getuseremailbyid, getdatabyid, userlogin } = require('../models/userforgot.service');
var uuid = require('uuid');
const { email } = require('../../../controllers/email.controller');
const { insertcreatelink, getdatabytoken, deletetoken } = require('../models/userconfirmationlink.service');

module.exports = {
    forgetpasswordemail: (req, res) => {
        const body = req.body;
        userlogin(body.email, (error, result) => {
            console.log(body);
            if (error) {
                return res.status(500).json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                
                if(result){
                    body.user_data = result;
                const linkdata = { "user_id": result.id, "token": uuid.v4(0) };
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
                                        <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.user_data.f_name}</strong>,</p><p style="color: #666;  line-height: 26px;">
                            Please click on the link below to reset your password.
                            <a href="${data.url}" style="color: #000; text-decoration: underline;"> Reset Password </a></p><p style="color: #666; line-height: 26px;">The link will expire within 5 days.<br/>
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
                            "subject": "User Resetpassword email",
                            "text": "Hello " + body.email,
                            "html": emailHtml (body)
                        };
                        email(email_data);
                    }
                });
                return res.status(200).json({
                    success: 1,
                    message: "Mail Send successfully"
                });
            }else{
                return res.status(200).json({
                    success: 0,
                    message: "EMail does not Exist"
                });
            }
            }
        });
    },
    getdatabytokens: (req, res) => {
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
    generatepasswords: (req, res) => {
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
	forgotpassword: (req, res) => {
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
							
                            }
                        }
		});

	}

};