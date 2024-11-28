const { userlist} = require('../../models/user.service');
const { updatebyquery, edit_profile_image } = require('../../models/user.service');

module.exports = {
    myprofile: (req, res) => {
                res.locals = { title: 'My Profile' };
                res.render('admin/admin/admin_profile/admin_profile', {auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
    },
    updateprofile: (req, res) => {      
        const body = req.body;
         //console.log(body);       
        body.user_id = req.session.auth_data.id;
        if (req.file) {
            const data = { "filename": req.file.filename, "id": body.user_id };
            edit_profile_image(data, (error, result) => {
                if (error) {
                    return res.json({
                        success: 0,
                        message: "Image Not Upload"
                    });
                } else {
                    /* if (req.session.auth_data.profile_image) {
                        if (fs.existsSync('images/' + req.session.auth_data.profile_image)) {
                            fs.unlink('images/' + req.session.auth_data.profile_image, error => {
                            });
                        }
                    } */
                     req.session.auth_data.profile_image = req.file.filename;
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
    },
    adminlist: (req, res) => {
        // res.redirect('/login');
        userlist("admin", (error, result) => {
          if (!error) {
            res.locals = { title: 'Admin List' };
            res.render('admin/admin/admin_list', { 'user_data': result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
          }
        });
        //  console.log(user_data);
    
        //res.render('admin/user/user_list');
      }
};