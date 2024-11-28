const { getprofilerequest, edituser, updatebyquery } = require('../../models/user.service');
const { email } = require('../email.controller');
var fs = require('fs');

module.exports = {
    profilerequest: (req, res) => {
        getprofilerequest((error, result) => {
            res.locals = { title: 'Manage Profile Image Request' };
            res.render('admin/profilerequest/profilerequest', { 'user_data': result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
        });
    },
    profilerequestaccept: (req, res) => {
        const body = req.body;
        edituser(body.user_id, (error, result) => {
            if (!error) {
                if (result != "") {
                    body.email = result.email;
                    body.old_image = result.profile_image;
                    var query = 'UPDATE `user` SET `profile_image`= "' + result.pending_image + '",`pending_image`= null,`pending_image_status`= "1" WHERE id = "' + body.user_id + '"';
                    updatebyquery(query, (error, result) => {
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
                                    message: "Something went wrong please try again"
                                });
                            } else {
                                getprofilerequest((error, result) => {
                                    if (body.old_image) {
                                        if (fs.existsSync('images/' + body.old_image)) {
                                            fs.unlink('images/' +body.old_image, error => {
                                            });
                                        }
                                    }
                                    if (!error) {
                                        var email_data = {
                                            "from": req.session.auth_data.email,
                                            "to": body.email,
                                            "subject": "Profile Image Request Accept",
                                            "text": "Hello " + body.f_name + " " + body.l_name,
                                            "html": 'Profile Image Request Accept successfully..!!'
                                          };
                                          email(email_data);
                                        return res.json({
                                            success: 1,
                                            message: "Profile Image Request Accept successfully",
                                            data: result
                                        });
                                    }
                                });
                            }
                        }
                    });
                } else {
                    return res.json({
                        success: 0,
                        message: "Record Not Found"
                    });
                }
            }
        });
    },
    profilerequestcancel: (req, res) => {
        const body = req.body;
        edituser(body.user_id, (error, result) => {
            if (!error) {
                if (result != "") {
                    body.email = result.email;
                    body.old_image = result.pending_image;
                    var query = 'UPDATE `user` SET `pending_image`= null , `pending_image_status`= "2" WHERE id = "' + body.user_id + '"';
                    updatebyquery(query, (error, result) => {
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
                                    message: "Something went wrong please try again"
                                });
                            } else {
                                getprofilerequest((error, result) => {
                                    console.log(body);
                                    if (!error) {
                                        if (body.old_image) {
                                            if (fs.existsSync('images/' + body.old_image)) {
                                                fs.unlink('images/' +body.old_image, error => {
                                                });
                                            }
                                        }
                                        var email_data = {
                                            "from": req.session.auth_data.email,
                                            "to": body.email,
                                            "subject": "Profile Image Request Cancel",
                                            "text": "Hello " + body.f_name + " " + body.l_name,
                                            "html": 'Profile Image Request Cancel..!!'
                                          };
                                          email(email_data);
                                        return res.json({
                                            success: 1,
                                            message: "Profile Image Request Cancel successfully",
                                            data: result
                                        });
                                    }
                                });
                            }
                        }
                    });
                } else {
                    return res.json({
                        success: 0,
                        message: "Record Not Found"
                    });
                }
            }
        });
    }
}