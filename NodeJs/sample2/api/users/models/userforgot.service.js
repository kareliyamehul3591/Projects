const pool =  require("../../../config/database");

module.exports = {
   getuserid : (user_id,callback) => {
        pool.query(
            'SELECT  `id`,`email` FROM `user` WHERE id = ?',
            [
                user_id
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result[0]);
                }
            }
        );
    },updatepassword: (data,callback) => {
        pool.query(
            'UPDATE `user` SET `password`= ? WHERE id = ?',
            [
                data.password,
                data.user_id
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
    userlogin: (email,callback) => {
        pool.query(
            'SELECT * FROM `user` WHERE `email`= ? and role="end_user"',
            [
                email
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result[0]);
                }
            }
        );
    },
	getdatabyid: (user_id,callback) => {
        pool.query(
            'SELECT * FROM `user` WHERE id = ?',
            [
                user_id
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result[0]);
                }
            }
        );
    },
    getuseremailbyid: (user_id,callback) => {
        pool.query(
            'SELECT  `id`,`email`,`unique_id` FROM `user` WHERE id = ?',
            [
                user_id
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    return callback(null,result[0]);
                }
            }
        );
    }
};