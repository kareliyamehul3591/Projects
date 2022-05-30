const { imagesliderlist, insert_image, deletesliderimage, edit_sliderimage, edit_images, updatesliderimage } = require('../../models/story_image_slider.service');
var fs = require('fs');
module.exports = {
    slider_list: (req, res) => {

        imagesliderlist((error, result) => {
            if (!error) {
                var msg_alert = null;
                if (req.session.sessionFlash) {
                    msg_alert = req.session.sessionFlash;
                    delete req.session.sessionFlash;
                }
                res.locals = { title: 'Slider List' };
                res.render('admin/story_image_slider/image_slider_list', { data: result, msg_alert: msg_alert, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
            }
        });
    },
    create_slider_list: (req, res) => {
        res.locals = { title: 'Slider Create' };
        res.render('admin/story_image_slider/create_image_slider', { auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
    },
    insert_slider_image: (req, res) => {

        const body = req.body;

       body.images = req.file.filename;
        body.unique_id = req.session.auth_data.unique_id;
        console.log(body);
        insert_image(body, (error, result) => {
            if (error) {
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/slider_list');
            } else {
                req.session.sessionFlash = {
                    type: 'success',
                    message: 'Slider Create successfully'
                }
                res.redirect('/admin/slider_list');
            }
        });
    },
    delete_slider_image: (req, res) => {
        const id = req.params.id;
        edit_sliderimage(id,(error, result) => {
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
        deletesliderimage(id, (error, result) => {
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
                    imagesliderlist((error, result) => {
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
    edit_slider_image: (req, res) => {
        const id = req.params.id;
        edit_sliderimage(id, (error, result) => {
            if (error) {
                console.log(error);
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/slider_list');
            } else {
                if (!result) {
                    req.session.sessionFlash = {
                        type: 'error',
                        message: 'Record Not Found'
                    }
                    res.redirect('/admin/slider_list');
                } else {
                    res.locals = { title: 'Slider Edit' };
                    res.render('admin/story_image_slider/update_image_slider', { data: result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
                }
            }
        });
    },
    update_slider_image: (req, res) => {
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
        updatesliderimage(body, (error, result) => {
            if (error) {
                console.log(error);
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/slider_list');
            } else {
                if (!result) {
                    req.session.sessionFlash = {
                        type: 'error',
                        message: 'Record Not Found'
                    }
                    res.redirect('/admin/slider_list');
                } else {
                    req.session.sessionFlash = {
                        type: 'success',
                        message: 'Slider updated successfully'
                    }
                    // req.flash('msg_alert',);
                    res.redirect('/admin/slider_list');
                }
            }
        });
    },
    viewcontent_slider_image: (req, res) => {
        const id = req.params.id;
        edit_sliderimage(id, (error, result) => {
            if (error) {
                res.redirect('/admin/slider_list');
            } else {
                if (!result) {
                    res.redirect('/admin/slider_list');
                } else {
                    res.send(result.content);
                }
            }
        });
    }
};