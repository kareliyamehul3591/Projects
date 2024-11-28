const { add_appointment , appointment_list , appointment_info_by_id, appointments_by_id, update_schedule_status} = require('../../models/appointment.service');

module.exports = {
    add_appointment: (req, res) => {
        const body = req.body;
        body.schedule_time = body.schedule_time;
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
            add_appointment(body, (error, result) => {
                if (error) {
                    console.log(error);
                    return res.json({
                        success: 0,
                        message: "Database connection errror"
                    });
                } else {
                    if (result != "") {
                        body.apt_id = result.insertId;
                        console.log(body.apt_id);
                        console.log("appointment_lastid");
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
                                        message: "appointment add successfully"
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
        } else {
            return res.json({
                success: 0,
                message: "Please enter a value greater than or equal to " + new Date().toISOString().slice(0, 10)
            });
        }
    },
    appointment_info :(req, res) => {
        const body = req.body;
        console.log(body);
        let firstDate = new Date(new Date()),
        secondDate = new Date(new Date(body.session_date)),
        timeDifference = Math.abs(secondDate.getTime() - firstDate.getTime());

        console.log(firstDate);
        console.log(secondDate);
        console.log(timeDifference);
        let differentDays = Math.ceil(timeDifference / (1000 * 3600 * 24));

        console.log(differentDays);
        
        appointment_list(body, (error, result) =>{
                if (error) {
                    console.log(error);
                    return res.json({
                        success: 0,
                        message: "Database connection errror"
                    });
                } else {
                   
                    
                    if (result != "") {
                        return res.json({
                            success: 1,
                            data:result,
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
     appointmentbyid:(req, res) => {
         const body = req.body;
         console.log(body);
         
        appointment_info_by_id(body, (error, result) =>{
            console.log(result);
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                var data = [];
                var array = [];
                
                
                    result.forEach(rec => {
                    const options = { weekday: 'short', day: 'numeric', month: 'short' };
                    const date = new Date(rec.session_date).toLocaleDateString('en-EN', options);
            

                    var date_ob = new Date();
                    var second_date = new Date(rec.session_date); 
                    var day = ("0" + date_ob.getDate()).slice(-2);
                    var second_day = ("0" + second_date.getDate()).slice(-2);
                    var month = ("0" + (date_ob.getMonth() + 1)).slice(-2);
                    var second_month = ("0" + (second_date.getMonth() + 1)).slice(-2);
                     var year = date_ob.getFullYear();                   
                     var second_year = second_date.getFullYear();                   
                    var dates = year + "-" + month + "-" + day;                    
                    var second_dates = second_year + "-" + second_month + "-" + second_day;                                      
                    console.log(second_dates);  
                     var start_time = rec.session_start_time; 
                            
                    var hours = date_ob.getHours();                   
                                  
                     var minutes = date_ob.getMinutes();                    
                                   
                     var seconds = date_ob.getSeconds();                    
                     
                     var dateTime =  hours + ":" + minutes + ":" + seconds; 
                     var second_dateTime = start_time; 
                console.log(dateTime);
                var d = new Date(second_dates +" "+second_dateTime)
                 console.log(d);
                 let firstDate = new Date(new Date()),
                 secondDate = new Date(d);
                 let dy = (new Date(secondDate)) - (new Date(firstDate));
                 console.log(firstDate); 
                 console.log(secondDate);
                 console.log(dy);
                 let weekdays     = Math.floor(dy/1000/60/60/24/7);
                 let days         = Math.floor(dy/1000/60/60/24 - weekdays*7);
                 let pr_hours        = Math.floor(dy/1000/60/60    - weekdays*7*24            - days*24);
                 let pr_minutes      = Math.floor(dy/1000/60       - weekdays*7*24*60         - days*24*60         - pr_hours*60);
                 let pr_seconds      = Math.floor(dy/1000          - weekdays*7*24*60*60      - days*24*60*60      - pr_hours*60*60      - pr_minutes*60);
                 let milliseconds = Math.floor(dy               - weekdays*7*24*60*60*1000 - days*24*60*60*1000 - pr_hours*60*60*1000 - pr_minutes*60*1000 - pr_seconds*1000);
                 let t = {};
                 ['weekdays', 'days', 'pr_hours', 'pr_minutes', 'pr_seconds', 'milliseconds'].forEach(q=>{ if (eval(q)>0) { t[q] = eval(q); } });
                 console.log(t);
                 const pr_days = days+weekdays*7;
                 console.log(pr_days) ;
 

                /* function tConvert (time) {
                    // Check correct time format and split into components
                     time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];
                  
                    if (time.length > 1) { // If time format correct
                      time = time.slice (1);  // Remove full string match value
                      time[5] = +time[0] < 12 ? ' AM' : ' PM'; // Set AM/PM
                      time[0] = +time[0] % 12 || 12; // Adjust hours
                    }
                    return time.join (''); // return adjusted time or original string
                  }
                  const session_schedule_time = tConvert(rec.session_start_time) + ' to ' + tConvert(rec.session_end_time) ;  */
                 function convertFrom24To12Format(time) {
                     
                    let hour = (time.split(':'))[0]
                    let min = (time.split(':'))[1]
                    let part = hour > 12 ? 'PM' : 'AM';

                    min = (min + '').length == 1 ? `0${min}` : min;
                    hour = hour > 12 ? hour - 12 : hour;
                    hour = (hour + '').length == 1 ? `0${hour}` : hour;
                    if(hour == "00")
                    {
                        hour = "12";
                    }
                    return (`${hour}:${min} ${part}`)
                }


                const session_schedule_time = convertFrom24To12Format(rec.session_start_time) + ' to ' + convertFrom24To12Format(rec.session_end_time) ;
                  /*   timeDifference = Math.abs(secondDate.getTime() - firstDate.getTime());
                    console.log(firstDate); 
                    console.log(secondDate);
                    console.log(timeDifference);
                    let differentDays = Math.ceil((timeDifference / (1000 * 3600 * 24))-1);
                    
                    var hours = Math.ceil(timeDifference / (1000 * 3600 * differentDays)) ;
                    console.log(hours);
                    // calculate (and subtract) whole minutes
                    var minutes = Math.ceil(timeDifference / (1000 * 60 * differentDays));
                    
                    console.log(minutes); */
                    rec.profile_image = (rec.profile_image) ? process.env.AAP_URL+"images/"+rec.profile_image : process.env.AAP_URL+"images/image_not_found.png";
                    array.push({ "date": date, "session_date": rec.session_date,
                    "id": rec.id,
                    "user_id": rec.user_id,
                    "therapist_id": rec.therapist_id,
                    "payment_id": rec.payment_id,
                    "schedule_time": rec.schedule_time,
                    "session_time": session_schedule_time,
                    "session_start_time": rec.session_start_time,
                    "session_end_time": rec.session_end_time,
                    "session_date": rec.session_date,
                    "session_price": rec.session_price,
                    "status": rec.status,
                    "therapist_fname": rec.therapist_fname,
                    "therapist_lname": rec.therapist_lname,
                    "role": rec.role,
                    "profile_image": rec.profile_image,
                     "differentDays": pr_days,
                        "hours": pr_hours, 
                        "mins": pr_minutes, 
                 });
                    
                });
                data = array;
                if (result != "") {
                    return res.json({
                        success: 1,
                        data:data
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
    appointments_byid:(req, res) => {
        const body = req.body;
        console.log(body);
        
       appointments_by_id(body, (error, result) =>{
           console.log(result);
           if (error) {
               console.log(error);
               return res.json({
                   success: 0,
                   message: "Database connection errror"
               });
           } else {
               if (result != "") {
                   return res.json({
                       success: 1,
                       data:result
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
    

};