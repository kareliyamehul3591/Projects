const { createschedule, createschedule_time, schedulelist, show_schedule, deleteschedule, editschedule, updateschedule, deleteschedule_time, delete_time, updateschedule_time, updateselect} = require('../../models/schedule.service');

module.exports = {
    schedulelist: (req, res) => {
        const id = req.session.auth_data.id;
        schedulelist(id, (error, result) => {
            if (!error) {
                res.locals = { title: 'Schedule List' };
                res.render('therapist/schedule/schedule_list', { 'data': result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
            }
        });
    },
    show_schedules:(req, res) => {
        const id = req.params.id;
        show_schedule(id, (error, result) => {
                if (!error) {
                return res.json({
                    success: 1,
                    message: "data get successfuly",
                    data: result
                });
            }
        });
    },
    createschedule: (req, res) => {
        const body = req.body;
        console.log(body);
        body.therapist_id = req.session.auth_data.id;
        body.unique_id = req.session.auth_data.unique_id;
        
        /*  if(body.start_time == "" && body.end_time == "")
        {   */
           // body.schedule_time = JSON.stringify(body.schedule_time);
           /*   var s_time = body.schedule_time;
            body.schedule_time = body.schedule_time;
            body.len= s_time.length;
            console.log(body.len);  */
         /* }else{
            const arr = body.start_time;
            var data = [];
            for (let i = 0; i < arr.length; i++) {
                data[i] = body.start_time[i]+" to "+body.end_time[i];
                
            }
            console.log(data);

            body.schedule_time = JSON.stringify(data);
        }    */
        
        
        createschedule(body, (error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
               
                if(body.start_time == "" && body.end_time == "")
                {
                 const schedulelist_asd = body.schedule_time;
                 schedulelist_asd.forEach(rec => {
                    var data = {"schedule_id":result.insertId,"schedule_time":rec};
                    createschedule_time(data, (error, result) => {
                        if (error) {
                            console.log(error);
                            return res.json({
                                success: 0,
                                message: "Database connection errror"
                            });
                        }
                    });
                });
                }else{
                const arr = body.start_time;
                var data = [];
                for (let i = 0; i < arr.length; i++) {
                    data[i] = body.start_time[i]+" to "+body.end_time[i];
                    
                }
                 body.schedule_time = data;
                 const schedulelist_asd = body.schedule_time;
                 schedulelist_asd.forEach(rec => {
                    var data = {"schedule_id":result.insertId,"schedule_time":rec};
                    createschedule_time(data, (error, result) => {
                        if (error) {
                            console.log(error);
                            return res.json({
                                success: 0,
                                message: "Database connection errror"
                            });
                        }
                    });
                });

            }
                const id = req.session.auth_data.id;                   
                schedulelist(id, (error, result) => {
                    if (!error) {
                        return res.json({
                            success: 1,
                            message: "Schedule Create Successfully",
                            data: result
                        });
                    }
                }); 
            }
        });
    },

    deleteschedule: (req, res) => {
        const id = req.params.id;
        deleteschedule(id, (error, result) => {
            console.log(id);
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
                     deleteschedule_time(id, (error, result) => {
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
                            const id = req.session.auth_data.id;
                            schedulelist(id, (error, result) => {
                                if (!error) {
                                    return res.json({
                                        success: 1,
                                        message: "Schedule deleted successfully",
                                        data: result
                                    });
                                }
                            });
                        }
                    }
                });
                }
            }
        });
    },
    delete_schedule_time: (req, res) => {
        const body = req.body;
        delete_time(body.schedule_time_id, (error, result) => {
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
                    show_schedule(body.schedule_id, (error, result) => {
                        if (!error) {
                            return res.json({
                                success: 1,
                                message: "Schedule_time deleted successfully",
                                data: result
                             });
                        }
                    }); 
                }
            }
        });
    },
    editschedule: (req, res) => {
        const id = req.params.id;
        editschedule(id, (error, result) => {
            console.log(id);
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
                    console.log(result);
                 /*    var array = "";
                    result.forEach(val => {
                        array += val.schedule + ",";
                    });
                    result.schedule_time = array;
                    console.log( array);
                    console.log( "array");
                    result.schedule_date = result.schedule_date; */
                    return res.json({
                        success: 1,
                        data: result
                    });
                }
            }
        });
    },
    updateschedule: (req, res) => {
        const body = req.body;
        console.log(body);
        updateschedule(body, (error, result) => {
            if(error)
            {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            }else{
                if(body.start_time == "" && body.end_time == "")
                {  
                    body.schedule_time_update.forEach(rec => {
                        var data = {"schedule_id": body.update_id,"schedule_time":rec};
                        updateselect(data, (error, result) => {
                            if(!result)
                            {
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
                }else{
                    var data = [];
                    for (let i = 0; i < body.start_time.length; i++) {
                        data[i] = body.start_time[i]+" to "+body.end_time[i];
                    }
                    body.schedule_time_update = data;
                    body.schedule_time_update.forEach(rec => {
                        var data = {"schedule_id": body.update_id,"schedule_time":rec};
                        updateselect(data, (error, result) => {
                            if(!result)
                            {
                                console.log(data);
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
        });
    }
};