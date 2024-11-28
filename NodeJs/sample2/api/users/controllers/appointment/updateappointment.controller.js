const { re_appointments, update_schedule_status, get_old_schedule_data, update_old_schedule_time} = require('../../models/appointment.service');
module.exports = {
    reschedule_appointment: (req, res) => {
    const body = req.body;
    console.log(body);
     function convertTimeFrom12To24(timeStr) {
        var colon = timeStr.indexOf(':');
        var hours = timeStr.substr(0, colon),
            minutes = timeStr.substr(colon + 1, 2),
            meridian = timeStr.substr(colon + 4, 2).toUpperCase();


        var hoursInt = parseInt(hours, 10),
            offset = meridian == 'PM' ? 12 : 0;

        if (hoursInt === 12) {
            hoursInt = offset;
        } else {
            hoursInt += offset;
        }
        return hoursInt + ":" + minutes;
    } 
    if (new Date(new Date().toISOString().slice(0, 10)).getTime() <= new Date(body.session_date).getTime()) {
        var session_time = body.session_time.split(" to ");
        body.session_start_time = convertTimeFrom12To24(session_time[0]);
        body.session_end_time = convertTimeFrom12To24(session_time[1]); 
        body.status = "1";
        re_appointments(body,(error, result) => {
            if (error) {
               console.log(error);
               return res.json({
                   success: 0,
                   message: "Database connection errror"
               });
           }else{
                if (!result) {
                   return res.json({
                       success: 0,
                       message: "Something has Wrong"
                   });
               } else {
                   const th_id = body.therapist_id;
                   const re_date = body.upd_reschedule_date;
                   console.log(body);
                get_old_schedule_data(th_id,re_date, (error, result) =>{
                    if (error) {
                        console.log(error);
                        return res.json({
                            success: 0,
                            message: "Database connection errror"
                        });
                    } else {
                        if (result != "") {
                            const re_time = body.upd_reschedule_time;
                            update_old_schedule_time(result.id,re_time,(error, result1) => {
                                if (error) {
                                    console.log(error);
                                    return res.json({
                                        success: 0,
                                        message: "Database connection errror"
                                    });
                                } else {
                                    if (!result1) {
                                        return res.json({
                                            success: 0,
                                            message: "Record Not Found"
                                        });
                                    } else {  
                                        const id = body.schedule_time_id;
                                        update_schedule_status(id, (error, result) => {
                                            if (error) {
                                                console.log(error);
                                                return res.json({
                                                    success: 0,
                                                    message: "Database connection errror"
                                                });
                                            } else {
                                                if (!result) {
                                                    return res.json({
                                                        success: 0,
                                                        message: "Record Not Found"
                                                    });
                                                } else {  
                                                    return res.json({
                                                        success: 1,
                                                        message: "Reschedule appointment successfully"
                                                    }); 
                                                
                                                }
                                                
                                            }
                                        });
                                    }
                                    
                                }
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
           }
       });
    } else {
        return res.json({
            success: 0,
            message: "Please enter a value greater than or equal to " + new Date().toISOString().slice(0, 10)
        });
    } 
}
}