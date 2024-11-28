const { get_country, get_state } = require('../models/countrylist_statelist.service');


module.exports = {
    countrylist:(req,res) => {
        get_country((error, result) => {
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
    statelist:(req,res) => {
        
        const country_id = req.body.country_id; 
        get_state(country_id,(error, result) => {
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
};