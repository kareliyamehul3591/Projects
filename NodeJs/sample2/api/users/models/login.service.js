const pool =  require("../../../config/database");

module.exports = {
    login: (email,callback) => {
        pool.query(
            'SELECT u.*,c.name as country_name,s.name as state_name FROM `user` u, `countries` c,`states` s WHERE  c.id = u.country and s.id = u.state and u.email= ? and u.role="end_user"',
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
    create: (data,callback) => {
        pool.query(
            'INSERT INTO `user`(`social`, `issocial`, `f_name`, `l_name`, `email`, `role`, `unique_id`) VALUES (?,?,?,?,?,?,?)',
            [
                data.social,
                data.issocial,
                data.f_name,
                data.l_name,
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
    googlelogin: (email,callback) => {
        pool.query(
            'SELECT * FROM `user` WHERE email= ? and role="end_user"',
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
    }
};
