const pool =  require("../../../config/database");

module.exports = {
    schedulelist: (therapist_id,callback) => {
        pool.query(
            'SELECT * FROM `therapist_schedule` WHERE `therapist_id` = ?',
            [
                therapist_id
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
    getschedulebydate: (data,callback) => {
        pool.query(
            'SELECT therapist_schedule.*, therapist_schedule.id as therapist_schedule_id, therapist_schedule.status as therapist_schedule_status, schedule_time.* FROM `therapist_schedule` JOIN `schedule_time` ON (therapist_schedule.id = schedule_time.schedule_id) WHERE schedule_time.status = 0 and therapist_schedule.therapist_id = ? and therapist_schedule.schedule_date BETWEEN ? and ?',
            [
                data.therapist_id,
                data.date,
                data.date
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
    }
};