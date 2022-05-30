const pool = require('../../../config/database');

module.exports = {
    add_appointment: (data,callback) => {
        pool.query(
            'INSERT INTO `appointments`(`user_id`, `therapist_id`, `payment_id`, `session_start_time`, `session_end_time`, `session_date`, `session_price`, `status`, `transection_id`, `transection_details`, `payment_method`, `payment_status`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)',
            [
                data.user_id,
                data.therapist_id,
                data.payment_id,
                data.session_start_time,
                data.session_end_time,
                data.session_date,
                data.session_price,
                data.status,
                data.transection_id,
                data.transection_details,
                data.payment_method,
    	        data.payment_status
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
    appointment_list:(data,callback) => {
        pool.query(
            'SELECT * FROM `appointments`',
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
    appointment_info_by_id:(data,callback) => {
        pool.query(
            /* 'SELECT * FROM `appointments` WHERE `user_id` = ?', */
            'SELECT appointments.* ,appointments.user_id,u.f_name as user_fname, u.l_name as user_lname ,t.f_name as therapist_fname,t.l_name as therapist_lname,t.role,t.profile_image FROM appointments INNER JOIN user as u ON appointments.user_id = u.id INNER JOIN user as t ON appointments.therapist_id = t.id and u.id = ?; ',
            [ data.user_id
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
    appointments_by_id:(data,callback) => {
        pool.query(
             'SELECT * FROM `appointments` WHERE `id` = ?', 
            //'SELECT appointments.* ,appointments.user_id,u.f_name as user_fname, u.l_name as user_lname ,t.f_name as therapist_fname,t.l_name as therapist_lname,t.role,t.profile_image FROM appointments INNER JOIN user as u ON appointments.user_id = u.id INNER JOIN user as t ON appointments.therapist_id = t.id and id = ?; ',
            [ data.id
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
    re_appointments: (data,callback) => {
        pool.query(
            'UPDATE `appointments` SET  `session_start_time`= ?, `session_end_time`= ?,`session_date`= ?  WHERE `id`= ? ' ,
            [
                data.session_start_time,
                data.session_end_time,
                data.session_date,
                data.id
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
    get_old_schedule_data: (th_id,re_date,callback) => {
       pool.query(
            'SELECT `id` FROM `therapist_schedule` WHERE `therapist_id` = ? AND `schedule_date`= ? ' ,
            [
                th_id,
                re_date
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
    update_old_schedule_time: (result,re_time,callback) => {
        var sql = pool.query(
            'UPDATE `schedule_time` SET `status`= "0" WHERE `schedule_id`= ? AND`schedule`=? ',
            [
                result,
                re_time
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
        console.log(sql);
    },
    
    update_schedule_status: (id,callback) => {
        pool.query(
            'UPDATE `schedule_time` SET  `status`= 1  WHERE `id`= ? ' ,
            [
                id
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
    appointment_by_id: (appointment_id,callback) => {
        pool.query(
            'SELECT * FROM `appointments` WHERE `id` = ?',
            [
                appointment_id
            ],
            (error, result, fields) => {
                if (error) {
                    return callback(error);
                } else {
                    return callback(null, result[0]);
                }
            }
        );
    }

    
}; 