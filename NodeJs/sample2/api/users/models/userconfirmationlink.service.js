const pool = require("../../../config/database");

module.exports = {
    insertcreatelink: (data,callback) => {
        pool.query(
            'INSERT INTO `user_confirmation_link`(`user_id`, `token`) VALUES (?,?)',
            [
                data.user_id,
                data.token
            ],
            (error, result) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    },
    getdatabytoken: (token,callback) => {
        pool.query(
            'SELECT * FROM `user_confirmation_link` WHERE `token` = ?',
            [
                token 
            ],
            (error, result) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result[0]);
                }
            }
        );
    },
    deletetoken: (token,callback) => {
        pool.query(
            'DELETE FROM `user_confirmation_link` WHERE `token` = ?',
            [
                token 
            ],
            (error, result) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result);
                }
            }
        );
    }
};