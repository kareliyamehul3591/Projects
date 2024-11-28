const pool =  require("../../../config/database");

module.exports = {
    create: (data,callback) => {
        pool.query(
            'INSERT INTO `user`(`f_name`, `l_name`, `email`, `role`, `unique_id`, `status`) VALUES (?,?,?,?,?,?)',
            [
                data.f_name,
                data.l_name,
                data.email,
                data.role,
                data.unique_id,
                data.status
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
    updatepassword: (data,callback) => {
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
    },
    updateuser: (data,callback) => {
      var pppp =  pool.query(
            'UPDATE `user` SET `f_name`= ?, `l_name`= ? , `mobile` = ?, `country` = ? , `state` = ? WHERE id = ?',
            [
                data.f_name,
                data.l_name,
                data.mobile,
                data.country,
                data.state,
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
            console.log(pppp);
    },
    getdataprofile: (user_id,callback) => {
         pool.query(
            'SELECT u.*,c.name as country_name,s.name as state_name FROM `user` u, `countries` c,`states` s WHERE  c.id = u.country and s.id = u.state and u.id = ?',
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
    email_check: (email, callback) => {
        pool.query(
            'SELECT `email` , COUNT(email) AS count_email FROM `user` where email = ?',
            
            [
                email
            ],
            (error, result) => {
                if (error) {
                    return callback(error);
                } else {
                    return callback(null, result[0]);
                }
            }
        );
    }
};