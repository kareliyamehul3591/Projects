const {settings_list, editsetting, edit_images, updatessettings } = require('../../models/settings.service');
var fs = require('fs');
module.exports = {
    settings:(req, res) => {

        settings_list((error, result) => {
            if (!error) {
                var msg_alert = null;
                if (req.session.sessionFlash) {
                    msg_alert = req.session.sessionFlash;
                    delete req.session.sessionFlash;
                }
                res.locals = { title: 'Slider List' };
                res.render('admin/settings/view_settings', { data: result, msg_alert: msg_alert, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
            }
        });
    },
    edit_settings: (req, res) => {
        const id = req.params.id;
        editsetting(id, (error, result) => {
            if (error) {
                console.log(error);
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/settings');
            } else {
                if (!result) {
                    req.session.sessionFlash = {
                        type: 'error',
                        message: 'Record Not Found'
                    }
                    res.redirect('/admin/settings');
                } else {
                    res.locals = { title: 'Edit Settings' };
                    res.render('admin/settings/update_settings', { data: result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
                }
            }
        });
    },
    update_settings: (req, res) => {
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
        updatessettings(body, (error, result) => {
            if (error) {
                console.log(error);
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/settings');
            } else {
                if (!result) {
                    req.session.sessionFlash = {
                        type: 'error',
                        message: 'Record Not Found'
                    }
                    res.redirect('/admin/settings');
                } else {
                    req.session.sessionFlash = {
                        type: 'success',
                        message: 'Slider updated successfully'
                    }
                    // req.flash('msg_alert',);
                    res.redirect('/admin/settings');
                }
            }
        });
    },
    viewcontent_settings: (req, res) => {
        const id = req.params.id;
        editsetting(id, (error, result) => {
            if (error) {
                res.redirect('/admin/settings');
            } else {
                if (!result) {
                    res.redirect('/admin/settings');
                } else {
                    res.send(result.footer_text);
                }
            }
        });
    }
};