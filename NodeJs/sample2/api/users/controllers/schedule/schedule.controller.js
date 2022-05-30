const { schedulelist, getschedulebydate } = require("../../models/schedule.service");

module.exports = {
    schedulelist: (req, res) => {
        const therapist_id = req.params.therapist_id;
        schedulelist(therapist_id, (error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                if (result != "") {
                    var data = [];
                    var array = [];
                    result.forEach(rec => {
                        const options = { weekday: 'short', day: 'numeric', month: 'short' };
                        const date = new Date(rec.schedule_date).toLocaleDateString('en-EN', options);
                        array.push({ "id": rec.id, "therapist_id": rec.therapist_id, "date": date, "schedule_date": rec.schedule_date });
                    });
                    data = array;
                    return res.json({
                        success: 1,
                        data: data
                    });
                } else {
                    return res.json({
                        success: 0,
                        message: "Record Not Found"
                    });
                }
            }
        });
    },
    getschedulebydate: (req, res) => {
        getschedulebydate(req.body, (error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                if (result != "") {
                    var array_time = [];
                    result.forEach(rec =>{
                        rec.schedule_time = rec.schedule;
                        array_time.push(rec.schedule_time);
                    }) 
                    console.log(array_time);
                    //array = result.schedule_time;
                    result.schedule_time =  array_time ;
                    return res.json({
                        success: 1,
                        data: result
                    });
                } else {
                    return res.json({
                        success: 0,
                        message: "Record Not Found"
                    });
                }
            }
        });
    }

};