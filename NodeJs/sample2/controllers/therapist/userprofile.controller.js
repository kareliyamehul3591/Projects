const { myprofile } = require('../../models/therapistprofile.service');
const { updatebyquery, edit_profile_pending_image } = require('../../models/user.service');
var fs = require('fs');
module.exports = {
    myprofile: (req, res) => {
        myprofile(req.session.auth_data.id, (error, result) => {
            if (error) {
                console.log(error);
            } else {
                var msg_alert = null;
                if (req.session.sessionFlash) {
                    msg_alert = req.session.sessionFlash;
                    delete req.session.sessionFlash;
                }
                res.locals = { title: 'My Profile' };
                res.render('therapist/profile/my_profile', { data: result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status'), msg_alert: msg_alert });
            }
        });
    },
    updateprofile: (req, res) => {
        const body = req.body;
        console.log(body);
        body.user_id = req.session.auth_data.id;
        if (req.file) {
            const data = { "filename": req.file.filename, "id": body.user_id};
            edit_profile_pending_image(data, (error, result) => {
                if (error) {
                    return res.json({
                        success: 0,
                        message: "Image Not Upload"
                    });
                } else {
                   /*  if (req.session.auth_data.profile_image) {
                        if (fs.existsSync('images/' + req.session.auth_data.profile_image)) {
                            fs.unlink('images/' + req.session.auth_data.profile_image, error => {
                            });
                        }
                    } */
                    //req.session.auth_data.profile_image = req.file.filename;
                    req.session.save( function(err) {
                        console.log(err);
                        req.session.reload( function (err) {
                            console.log(err);
                            req.session.auth_data;
                        });
                    });

                }
            });
        }
        req.session.auth_data.mobile = body.mobile;
        req.session.auth_data.country_code = body.country_code;
        var query = "UPDATE `user` SET `mobile`='"+body.mobile+"',`country_code`='"+body.country_code+"' WHERE id ="+req.session.auth_data.id;
        updatebyquery(query, (error, result) => {
            if (error) {
                console.log(error);
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {

                req.session.save( function(err) {
                    console.log(err);
                    req.session.reload( function (err) {
                        console.log(err);
                        req.session.auth_data;
                    });
                });

                req.session.sessionFlash = {
                    type: 'success',
                    message: 'Profile Update Successfully'
                }
                return res.json({
                    success: 1,
                    message: "Profile Update Successfully"
                });
            }
        });
    }
};