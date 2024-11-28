
const pool =  require("../../../config/database");

module.exports = {
    check_info: (data,callback) => {
        pool.query(
            'SELECT COUNT(`id`) as total_row FROM `zoom` WHERE `therapist_id` = ? and `session_date` = ? and `session_time` = ?',
            [
                data.therapist_id,
                data.session_date,
                data.session_time
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    console.log(result);
                    return callback(null,result[0]);
                }
            }
        );
    },
    insertdata:(data,callback) =>{
        pool.query(
            'INSERT INTO `zoom`( `therapist_id`, `session_date`, `session_time`, `zoom_link`, `zoom_id`, `zoom_password`) VALUES (?,?,?,?,?,?)',
            [
            
                data.therapist_id,
                data.session_date,
                data.session_time,
                data.zoom_link,
                data.zoom_id,
                data.zoom_password     
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
    update_zoom_data: (data,callback) => {
        pool.query(
            'UPDATE `zoom` SET `zoom_link`= ?, `zoom_id`= ?, `zoom_password`= ? WHERE therapist_id = ? AND `session_date`= ? AND `session_time` = ?',
            [
                data.zoom_link,
                data.zoom_id,
                data.zoom_password,    
                data.therapist_id,
                data.session_date,
                data.session_time
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
    select_zoom_data: (data,callback) => {
        pool.query(
            //'SELECT *  FROM `zoom` WHERE therapist_id = ?',
            'SELECT COUNT(z.id) as total_row ,z.zoom_link, z.zoom_id, z.zoom_password, u.f_name, u.l_name, u.email as therapist_email, ti.therapist_role as designation, uu.f_name as user_name, uu.l_name as user_l_name FROM `zoom` z, user u, therapist_info ti, appointments as a JOIN user uu ON a.user_id = uu.id WHERE u.id = z.therapist_id AND ti.user_id = z.therapist_id AND z.therapist_id= ? AND z.session_date= ? AND z.session_time = ?',
            [    
                data.therapist_id,
                data.session_date,
                data.session_time
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
    get_link: (data,callback) => {
        pool.query(
            'SELECT `zoom_link` FROM `zoom` WHERE `therapist_id` = ? and `session_date` = ? and `session_time` = ?',
            [
                data.therapist_id,
                data.session_date,
                data.session_time
            ],
            (error,result,fields) => {
                if(error)
                {
                    return callback(error);
                }else{
                    console.log(result);
                    return callback(null,result);
                }
            }
        );
    },
    get_zoom_link: (data,callback) => {
        pool.query(
            'SELECT * FROM `zoom` WHERE `therapist_id` = ? and `session_date` = ? and `session_time` = ?',
            [
                data.therapist_id,
                data.session_date,
                data.session_time
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