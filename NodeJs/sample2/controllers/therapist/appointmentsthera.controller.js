const { appointments_thera_list,appointments_thera_list_by_date,past_appointments_thera_list_by_date  } = require('../../models/appointment_thera.service');
const { appointment_by_id } = require('../../models/appointments.service');
const { get_zoom_link } = require('../../models/zoom.service');

module.exports = {
    theraappointmentslist: (req,res) => {
        const thrapist_id = req.session.auth_data.id;
        appointments_thera_list(thrapist_id,(error, result) => {
            if (error) {
                console.log(error);
            }else{             
                res.locals = { title: 'Appointments List' };
                res.render('therapist/appointments/appointments_list',{auth_data:req.session.auth_data,data:result,message:req.flash('message'),status:req.flash('status')});
            }
        });
    },
    theraupcomingappointmentslist: (req,res) => {
        const thrapist_id = req.session.auth_data.id;
        const data = {therapist_id:thrapist_id,date:new Date().toISOString().slice(0, 10)};
        appointments_thera_list_by_date(data,(error, result) => {
            if (error) {
                console.log(error);
            }else{          
                res.locals = { title: 'Upcoming Appointments List' };
                res.render('therapist/appointments/upcoming_appointments_list',{auth_data:req.session.auth_data,data:result,message:req.flash('message'),status:req.flash('status')});
            }
        });
    },
    therapastappointmentslist: (req,res) => {
        const thrapist_id = req.session.auth_data.id;
        const data = {therapist_id:thrapist_id,date:new Date().toISOString().slice(0, 10)};
        past_appointments_thera_list_by_date(data,(error, result) => {
            if (error) {
                console.log(error);
            }else{        
                res.locals = { title: 'Past Appointments List' };
                res.render('therapist/appointments/past_appointments_list',{auth_data:req.session.auth_data,data:result,message:req.flash('message'),status:req.flash('status')});
            }
        });
    },
    appointmentjoinlink: (req,res) => {
        const body = req.body;
        appointment_by_id(body.appointment_id, (error, result) => {
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
                    function convertFrom24To12Format(time) {
                        let hour = (time.split(':'))[0]
                        let min = (time.split(':'))[1]
                        let part = hour > 12 ? 'pm' : 'am';

                        min = (min + '').length == 1 ? `0${min}` : min;
                        hour = hour > 12 ? hour - 12 : hour;
                        hour = (hour + '').length == 1 ? `0${hour}` : hour;

                        return (`${hour}:${min} ${part}`)
                    }

                    console.log(convertFrom24To12Format(result.session_start_time));
                    var data = { therapist_id: result.therapist_id, session_date: result.session_date, session_time: convertFrom24To12Format(result.session_start_time) + ' to ' + convertFrom24To12Format(result.session_end_time) };
                    console.log(data);
                    get_zoom_link(data, (error, result) => {
                        if (!error) {
                            if (!result) {
                                return res.json({
                                    success: 0,
                                    message: "Record Not Found"
                                });
                            } else {
                                return res.json({
                                    success: 1,
                                    data: result
                                });
                            }
                        }
                    });
                }
            }
        });
    }
};