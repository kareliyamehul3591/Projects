const { cmslist, cmsbyid, image_slider_list , image_slider_list_by_id, success_imagelist, success_image_list_by_id, settings_list, social_media_list } = require('../../models/cms.service');

module.exports = {
    cmslist: (req,res) => {
        const id = req.params.id;
        if(id)
        {
            cmsbyid(id,(error, result) => {
                if(error)
                {
                    console.log(error);
                    return res.json({
                        success: 0,
                        message: "Database connection errror"
                    });
                }else{
                    if (result != "") {
                        return res.json({
                            success: 1,
                            data: result
                        });
                    } else {
                        return res.json({
                            success: 0,
                            message: "Record Not Found"
                        });
                    }
                }
            });
        }else{
            cmslist((error, result) => {
                if(error)
                {
                    console.log(error);
                    return res.json({
                        success: 0,
                        message: "Database connection errror"
                    });
                }else{
                    if (result != "") {
                        return res.json({
                            success: 1,
                            data: result
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
    },
    image_slider_list: (req,res) => {
        const id = req.params.id;
        if(id)
        {
            image_slider_list_by_id(id,(error, result) => {
                if(error)
                {
                    console.log(error);
                    return res.json({
                        success: 0,
                        message: "Database connection errror"
                    });
                }else{
                    if (result != "") {
                        return res.json({
                            success: 1,
                            data: result
                        });
                    } else {
                        return res.json({
                            success: 0,
                            message: "Record Not Found"
                        });
                    }
                }
            });
        }else{
            image_slider_list((error, result) => {
                if(error)
                {
                    console.log(error);
                    return res.json({
                        success: 0,
                        message: "Database connection errror"
                    });
                }else{
                    if (result != "") {
                        return res.json({
                            success: 1,
                            data: result
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
    },
    success_image_list: (req,res) => {
        const id = req.params.id;
        if(id)
        {
            success_image_list_by_id(id,(error, result) => {
                if(error)
                {
                    console.log(error);
                    return res.json({
                        success: 0,
                        message: "Database connection errror"
                    });
                }else{
                    if (result != "") {
                        return res.json({
                            success: 1,
                            data: result
                        });
                    } else {
                        return res.json({
                            success: 0,
                            message: "Record Not Found"
                        });
                    }
                }
            });
        }else{
            success_imagelist((error, result) => {
                if(error)
                {
                    console.log(error);
                    return res.json({
                        success: 0,
                        message: "Database connection errror"
                    });
                }else{
                    if (result != "") {
                        return res.json({
                            success: 1,
                            data: result
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
    },
    settings: (req,res) => {
        settings_list((error, result) => {
            if(error)
            {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            }else{
                if (result != "") {
                    return res.json({
                        success: 1,
                        data: result
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
    social_media: (req,res) => {
        social_media_list((error, result) => {
            if(error)
            {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            }else{
                if (result != "") {
                    return res.json({
                        success: 1,
                        data: result
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

};