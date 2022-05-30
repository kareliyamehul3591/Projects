//const { therapistlist, createtherapist, deletetherapist, edittherapist, updatetherapist } = require('../../models/therapist.service');
const { userlist, email_check, createuser, deleteuser, edituser, updateuser, edit_profile_image, therapist_list } = require('../../models/user.service');
const { insertcreatelink } = require('../../models/userconfirmationlink.service');
const { myprofile, therapistinfoinsert, updateprofile, deleteprofile } = require('../../models/therapistprofile.service');
var uuid = require('uuid');
var fs = require('fs');
const { genSaltSync, hashSync, compareSync } = require('bcrypt');
const { email } = require('../email.controller');
module.exports = {
    therapistlist: (req, res) => {
        const body = req.body;  
        therapist_list("therapist", (error, result) => {
            console.log(result);
            if (!error) {
                res.locals = { title: 'Therapist List' };
                res.render('admin/therapist/therapist_list', { 'therapistlist': result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
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
        
      },
    createtherapist: (req, res) => {
        const body = req.body;
        console.log(body);
        body.language = JSON.stringify(body.language);
        body.experience =JSON.stringify([{'0':'ShivYog therapy Experience , ' + body.create_therapy_experience + ', Yrs'},{"0":'ShivYog cosmic therapy Experience , ' + body.create_cosmic_therapy_experience + ', Yrs'}]) ;
       
        body.expertise = JSON.stringify(body.expertise);
        body.status= "2";
        body.unique_id = uuid.v4(0);
        body.role = 'therapist';
        createuser(body, (error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                body.user_id = result.insertId;
                if (req.file) {
                    const data = { "filename": req.file.filename, "id": body.user_id };
                    console.log(data);
                    edit_profile_image(data, (error, result) => {
                        if (error) {
                            return res.json({
                                success: 0,
                                message: "Image Not Upload"
                            });
                        }
                    });
                }
                const linkdata = { "user_id": body.user_id, "token": uuid.v4(0) };
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
                            "from": req.session.auth_data.email,
                            "to": body.email,
                            "subject": "Therapist Create",
                            "text": "Hello " + body.f_name + " " + body.l_name,
                            "html": emailHtml(body)
                        };
                        email(email_data);
                    }
                });
                therapistinfoinsert(body, (error, result) => {
                    if (error) {
                        console.log(error);
                        return res.json({
                            success: 0,
                            message: "Database connection errror"
                        });
                    }
                });

                userlist("therapist", (error, result) => {
                    if (!error) {
                        return res.json({
                            success: 1,
                            message: "Therapist Create Successfully",
                            data: result
                        });
                    }
                });
            }
        });
    },
    deletetherapist: (req, res) => {
        const id = req.params.id;
        edituser(id,(error, result) => {
            if(!error)
            {
                if (result.profile_image != "") {
                    if (fs.existsSync('images/' + result.profile_image)) {
                        fs.unlink('images/' + result.profile_image, error => {
                        });
                    }
                }
            }
        });
        deleteprofile(id,(error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            }
        });
        deleteuser(id, (error, result) => {
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
                    userlist("therapist", (error, result) => {
                        if (!error) {
                            return res.json({
                                success: 1,
                                message: "Therapist deleted successfully",
                                data: result
                            });
                        }
                    });
                }
            }
        });
    },
    edittherapist: (req, res) => {
        const id = req.params.id;
        edituser(id, (error, result) => {
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
                    var data = [];
                    data.push(result);

                    myprofile(id, (error, result) => {
                        if (error) {
                            console.log(error);
                            return res.json({
                                success: 0,
                                message: "Database connection errror"
                            });
                        } else {
                            console.log(result);
                            result.language = (result.language != "") ? JSON.parse(result.language) : result.language;
                            result.experience = (result.experience != "") ? JSON.parse(result.experience) : result.experience;
                            result.expertise = (result.expertise != "") ? JSON.parse(result.expertise) : result.expertise;
                            data.push(result);
                            
                            return res.json({
                                success: 1,
                                data: data
                            });
                        }
                    });

                }
            }
        });
    },
    updatetherapist: (req, res) => {
        const body = req.body;
        body.language = JSON.stringify(body.language);
        body.experience =JSON.stringify([{'0':'ShivYog therapy Experience , ' + body.create_therapy_experience + ', Yrs'},{"0":'ShivYog cosmic therapy Experience , ' + body.create_cosmic_therapy_experience + ', Yrs'}]) ;
        body.expertise = JSON.stringify(body.expertise);
        console.log(body);
        if (req.file) {
            const data = { "filename": req.file.filename, "id": body.update_id };
            console.log(data);
            edit_profile_image(data, (error, result) => {
                if (error) {
                    return res.json({
                        success: 0,
                        message: "Image Not Upload"
                    });
                } else {
                    if (body.old_image_name) {
                        if (fs.existsSync('images/' + body.old_image_name)) {
                            fs.unlink('images/' + body.old_image_name, error => {
                            });
                        }
                    }
                }
            });
        }
        updateprofile(body, (error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            }
        });
         updateuser(body, (error, result) => {
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
                    userlist("therapist", (error, result) => {
                        if (!error) {
                            return res.json({
                                success: 1,
                                message: "Therapist updated successfully",
                                data: result
                            });
                        }
                    });
                }
            }
        }); 
    },
    resend_therapist_email: (req, res) => {
        const body = req.body;
        edituser(body.user_id, (error, edituser) => {
          if (error) {
            return res.json({
              success: 0,
              message: "Database connection errror"
            });
          } else {
            if (!edituser) {
              return res.json({
                success: 0,
                message: "Record Not Found"
              });
            } else {
              const linkdata = {"user_id":body.user_id,"token":uuid.v4(0)};
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
                      edituser.url = process.env.AAP_URL + "generatepassword/" + linkdata.token ;
                  var email_data = {
                    "from": req.session.auth_data.email,
                    "to": edituser.email,
                    "subject": "Therapist Create",
                    "text": "Hello " + edituser.f_name + " " + edituser.l_name,
                    "html": emailHtml(edituser)
                  };
                  email(email_data);
                  return res.json({
                    success: 1,
                    message: "Send Email Successfully"
                  });
                }
              });
            }
          }
        });
    }
};