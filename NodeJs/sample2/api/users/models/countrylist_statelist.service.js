const pool = require('../../../config/database');

module.exports = {
    get_country: (callback) => {
        pool.query(
            'SELECT * FROM `countries`',
            [
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
    get_state: (country_id,callback) => {
       var query =  pool.query(
            'SELECT * FROM `states` WHERE `country_id` = ?',
            [
                country_id
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
        console.log(query);
    }
};