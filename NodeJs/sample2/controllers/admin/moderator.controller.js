const { moderatorlist, createmoderator, deletemoderator, editmoderator, updatemoderator } = require('../../models/moderator.service');
var uuid = require('uuid');
const { genSaltSync, hashSync, compareSync } = require('bcrypt');

module.exports = {
    moderatorlist: (req, res) => {
        moderatorlist((error, result) => {
            if (!error) {
                res.locals = { title: 'Moderator List' };
                res.render('admin/moderator/moderator_list', { 'data': result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
            }
        });
    },
    createmoderator: (req, res) => {
        const body = req.body;
        const salt = genSaltSync(10);
        body.password = hashSync(body.password, salt);
        body.unique_id = uuid.v4(0);
        body.role = 'moderator';
        createmoderator(body, (error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                moderatorlist((error, result) => {
                    if (!error) {
                        return res.json({
                            success: 1,
                            message: "Moderator Create Successfully",
                            data: result
                        });
                    }
                });
            }
        });
    },
    deletemoderator: (req, res) => {
        const id = req.params.id;
        deletemoderator(id, (error, result) => {
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
                    moderatorlist((error, result) => {
                        if (!error) {
                            return res.json({
                                success: 1,
                                message: "Moderator deleted successfully",
                                data: result
                            });
                        }
                    });
                }
            }
        });
    },
    editmoderator: (req, res) => {
        const id = req.params.id;
        editmoderator(id, (error, result) => {
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
                    return res.json({
                        success: 1,
                        data: result
                    });
                }
            }
        });
    },
    updatemoderator: (req, res) => {
        const body = req.body;
        updatemoderator(body, (error, result) => {
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
                    moderatorlist((error, result) => {
                        if (!error) {
                            return res.json({
                                success: 1,
                                message: "Moderator updated successfully",
                                data: result
                            });
                        }
                    });
                }
            }
        });
    }
};