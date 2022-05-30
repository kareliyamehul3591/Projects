const pool =  require("../../../config/database");

module.exports = {
    create: (data,callback) => {
        pool.query(
            'INSERT INTO `user`(`email`, `role`, `unique_id`) VALUES (?,?,?)',
            [
                data.email,
                data.role,
                data.unique_id
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
    update_pass_country_state: (data,callback) => {
        pool.query(
            'UPDATE `user` SET `password`= ?, `country`=?, `state`=? WHERE id = ?',
            [
                data.password,
                data.user_id,
                data.country,
                data.state,
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