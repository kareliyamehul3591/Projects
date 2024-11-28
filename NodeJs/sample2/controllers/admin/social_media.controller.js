const { socialmedia_list, insert_socialmedia, deletesocialmedia, edit_socialmedia, edit_images, update_socialmedia } = require('../../models/social_media.service');
var fs = require('fs');
module.exports = {
    social_media_list: (req, res) => {

        socialmedia_list((error, result) => {
            if (!error) {
                var msg_alert = null;
                if (req.session.sessionFlash) {
                    msg_alert = req.session.sessionFlash;
                    delete req.session.sessionFlash;
                }
                res.locals = { title: 'Social Media List' };
                res.render('admin/social_media/view_social_media', { data: result, msg_alert: msg_alert, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
            }
        });
    },
    create_social_media: (req, res) => {
        res.locals = { title: 'Slider Create' };
        res.render('admin/social_media/create_social_media', { auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
    },
    insert_social_media: (req, res) => {

        const body = req.body;

       body.images = req.file.filename;
        console.log(body);
        insert_socialmedia(body, (error, result) => {
            if (error) {
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/social_media');
            } else {
                req.session.sessionFlash = {
                    type: 'success',
                    message: 'Slider Create successfully'
                }
                res.redirect('/admin/social_media');
            }
        });
    },
    delete_social_media: (req, res) => {
        const id = req.params.id;
        edit_socialmedia(id,(error, result) => {
            if(!error)
            {
                if (result.images != "") {
                    if (fs.existsSync('images/' + result.images)) {
                        fs.unlink('images/' + result.images, error => {
                        });
                    }
                }
            }
        });  
        deletesocialmedia(id, (error, result) => {
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
                    /* const user_id = req.session.auth_data.id; */  
                    socialmedia_list((error, result) => {
                        if (!error) {
                            
                            return res.json({
                                success: 1,
                                message: "Slider deleted successfully",
                                data: result
                            });
                        }
                    });

                }
            }
        });
    },
    edit_social_media: (req, res) => {
        const id = req.params.id;
        edit_socialmedia(id, (error, result) => {
            if (error) {
                console.log(error);
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/social_media');
            } else {
                if (!result) {
                    req.session.sessionFlash = {
                        type: 'error',
                        message: 'Record Not Found'
                    }
                    res.redirect('/admin/social_media');
                } else {
                    res.locals = { title: 'Slider Edit' };
                    res.render('admin/social_media/update_social_media', { data: result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
                }
            }
        });
    },
    update_social_media: (req, res) => {
        const body = req.body;
        const msg_alert = null;
        /* body.images = req.file.filename;  */
        if (req.file) {
            const data = { "filename": req.file.filename, "id": body.update_id };
            console.log(data);
            edit_images(data, (error, result) => {
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
        update_socialmedia(body, (error, result) => {
            if (error) {
                console.log(error);
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/social_media');
            } else {
                if (!result) {
                    req.session.sessionFlash = {
                        type: 'error',
                        message: 'Record Not Found'
                    }
                    res.redirect('/admin/social_media');
                } else {
                    req.session.sessionFlash = {
                        type: 'success',
                        message: 'Slider updated successfully'
                    }
                    // req.flash('msg_alert',);
                    res.redirect('/admin/social_media');
                }
            }
        });
    },
    /* viewcontent_slider_image: (req, res) => {
        const id = req.params.id;
        edit_sliderimage(id, (error, result) => {
            if (error) {
                res.redirect('/admin/social_media');
            } else {
                if (!result) {
                    res.redirect('/admin/social_media');
                } else {
                    res.send(result.content);
                }
            }
        });
    } */
};