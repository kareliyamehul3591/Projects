const { get_country_code } = require('../../models/country_code.service');


module.exports = {
    country_codelist:(req,res) => {
        get_country_code((error, result) => {
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
    }
};