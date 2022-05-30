const { updateschedule, updateschedule_time, updateselect} = require('../../models/new_schedule.service');

module.exports = {

    updateschedule: (req, res) => {
        const body = req.body;
        console.log(body);
        console.log("body");
        body.schedule_date_update = body.schedule_date_update;
        body.update_status = body.update_status;
        body.update_id = body.update_id;
        
        updateschedule(body, (error, result) => {
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
                        message: "Record Not Found1"
                    });
                } else {  
                    if(body.start_time == "" && body.end_time == "")
                    {    
                         
                     const schedulelist_asd1 = body.schedule_time_update;
                     schedulelist_asd1.forEach(rec => {
                        var data = {"schedule_id": body.update_id,"schedule_time":rec};
                        updateselect(data, (error, result) => {
                           
                            result.forEach(rec => {
                                var count = rec.count_schedule_time; 
                                if(count == 0){
                                    updateschedule_time(data, (error, result) => {
                                        if (error) {
                                            console.log(error);
                                            return res.json({
                                                success: 0,
                                                message: "Database connection errror"
                                            });
                                        }
                                    }); 
                                }
                            }); 
                            
                           
                        });
                        
                    }); 
                }else{
                    const arr = body.start_time;
                    var data = [];
                    for (let i = 0; i < arr.length; i++) {
                        data[i] = body.start_time[i]+" to "+body.end_time[i];
                    }
                    body.schedule_time_update = data;
                     console.log(body.schedule_time_update);
                     console.log('222');
                     const schedulelist_asd1 = body.schedule_time_update;
                     console.log(schedulelist_asd1);
                     schedulelist_asd1.forEach(rec => {
                     
                        var data = {"schedule_id": body.update_id,"schedule_time":rec};
                        updateselect(data, (error, result) => {
                            result.forEach(rec => {
                                var count = rec.count_schedule_time; 
                                if(count == 0){
                                    updateschedule_time(data, (error, result) => {
                                        if (error) {
                                            console.log(error);
                                            return res.json({
                                                success: 0,
                                                message: "Database connection errror"
                                            });
                                        }
                                    }); 
                                }
                            }); 
                            
                           
                        });
                        
                    }); 
    
                } 
                 const id = req.session.auth_data.id;                   
                schedulelist(id, (error, result) => {
                    if (!error) {
                        return res.json({
                            success: 1,
                            message: "Schedule Update Successfully",
                            data: result
                        });
                    }
                });  
                }
                
            }
        });
    }
};