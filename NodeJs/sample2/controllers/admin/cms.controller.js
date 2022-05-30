const { cmslist, insertcms, deletecms, editcms, updatecms } = require('../../models/cms.service');

module.exports = {
    cmslist: (req, res) => {
        const user_id = req.session.auth_data.id;
        console.log(req.session.sessionFlash);
        cmslist(user_id, (error, result) => {
            if (!error) {
                var msg_alert = null;
                if (req.session.sessionFlash) {
                    msg_alert = req.session.sessionFlash;
                    delete req.session.sessionFlash;
                }
                res.locals = { title: 'CMS List' };
                res.render('admin/cms/cms_list', { data: result, msg_alert: msg_alert, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
            }
        });
    },
    createcms: (req, res) => {
        console.log(req.session.msg_alert);
        res.locals = { title: 'CMS Create' };
        res.render('admin/cms/create_cms', { auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
    },
    insertcms: (req, res) => {
        const body = req.body;
        body.user_id = req.session.auth_data.id;
        body.unique_id = req.session.auth_data.unique_id;
        insertcms(body, (error, result) => {
            if (error) {
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/cms');
            } else {
                req.session.sessionFlash = {
                    type: 'success',
                    message: 'Cms Create successfully'
                }
                res.redirect('/admin/cms');
            }
        });
    },
    deletecms: (req, res) => {
        const id = req.params.id;
        deletecms(id, (error, result) => {
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
                    const user_id = req.session.auth_data.id;
                    cmslist(user_id, (error, result) => {
                        if (!error) {
                            return res.json({
                                success: 1,
                                message: "Cms deleted successfully",
                                data: result
                            });
                        }
                    });

                }
            }
        });
    },
    editcms: (req, res) => {
        const id = req.params.id;
        editcms(id, (error, result) => {
            if (error) {
                console.log(error);
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/cms');
            } else {
                if (!result) {
                    req.session.sessionFlash = {
                        type: 'error',
                        message: 'Record Not Found'
                    }
                    res.redirect('/admin/cms');
                } else {
                    res.locals = { title: 'CMS Edit' };
                    res.render('admin/cms/update_cms', { data: result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
                }
            }
        });
    },
    updatecms: (req, res) => {
        const body = req.body;
        const msg_alert = null;
        updatecms(body, (error, result) => {
            if (error) {
                console.log(error);
                req.session.sessionFlash = {
                    type: 'error',
                    message: 'Database connection errror'
                }
                res.redirect('/admin/cms');
            } else {
                if (!result) {
                    req.session.sessionFlash = {
                        type: 'error',
                        message: 'Record Not Found'
                    }
                    res.redirect('/admin/cms');
                } else {
                    req.session.sessionFlash = {
                        type: 'success',
                        message: 'Cms updated successfully'
                    }
                    // req.flash('msg_alert',);
                    res.redirect('/admin/cms');
                }
            }
        });
    },
    viewcontent: (req, res) => {
        const id = req.params.id;
        editcms(id, (error, result) => {
            if (error) {
                res.redirect('/admin/cms');
            } else {
                if (!result) {
                    res.redirect('/admin/cms');
                } else {
                    res.send(result.content);
                }
            }
        });
    }
};