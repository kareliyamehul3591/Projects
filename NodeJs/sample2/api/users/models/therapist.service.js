const pool =  require("../../../config/database");

module.exports = {
    therapistlist: callback => {
        pool.query(
            'SELECT * FROM `user` u,`therapist_info` t WHERE u.id = t.user_id ORDER BY `f_name` ASC',
            [],
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
    therapistbyid: (therapist_id,callback) => {
        pool.query(
            'SELECT * FROM `user` u,`therapist_info` t WHERE u.id = t.user_id and u.id = ?',
            [
                therapist_id
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