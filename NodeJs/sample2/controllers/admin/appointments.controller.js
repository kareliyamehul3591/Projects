const { appointmentslist, can_appointment, upcomingappointmentslist, pastappointmentslist, appointment_by_id, cancel_appointment_emailsend } = require('../../models/appointments.service');
const { get_zoom_link } = require('../../models/zoom.service');
const { email } = require('../email.controller');
var uuid = require('uuid');
const { insertcreatelink } = require('../../models/userconfirmationlink.service');


module.exports = {
    appointmentslist: (req, res) => {
        appointmentslist((error, result) => {
            if (error) {
                console.log(error);
            } else {
                res.locals = { title: 'Appointments List' };
                res.render('admin/appointments/appointments_list', { auth_data: req.session.auth_data, data: result, message: req.flash('message'), status: req.flash('status') });
            }
        });
    },
    appointments_in_calendar: (req, res) => {
        res.locals = { title: 'Appointments List' };
        res.render('admin/appointments/calendar_view', { auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
    },
    cancel_appointment: (req, res) => {
        const id = req.params.id;
        console.log(id);
        
        can_appointment(id, (error, result) => {
            if (error) {
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                
                const id = req.params.id;
                cancel_appointment_emailsend(id, (error, result) => {
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
                        const body = result;
                        console.log(body);
                        const srt = body.session_start_time.split(':');
                        body.str_time = srt[0]+':'+srt[1];
                            const linkdata = {"user_id":body.user_id,"token":uuid.v4(0)};
                            insertcreatelink(linkdata, (error, result) => {
                              console.log(error);
                              if (!error) {
                                
                       function email_user_cancel_Html (data){
                         return `<!doctype html>
                         <html>
                         <head>
                         <meta charset="utf-8">
                         <title>:: ShivYog ::</title>
                         <style>
                             body{box-sizing: border-box; padding: 0; margin: 0;}
                             table{width: 100%; max-width: 750px; font-family: Helvetica; font-size: 16px; margin: 0px auto;}
                         </style>
                             </head>
                         
                         <body>
                             <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 750px; font-family: Helvetica; font-size: 16px; margin: 0px auto;">
                                 <thead><tr><td style="padding: 15px 0;"><img src="https://api.ashoresystems.ws/public/assets/images/brand.jpg"></td></tr></thead>
                                 <tbody><tr><td style="padding: 15px 0; border-top:1px solid #000">
                                     <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.user_fname}</strong>,</p><p style="color: #666; line-height: 26px;">Your scheduled ShivYog Cosmic Therapy appointment on</p><p style="color: #666;  line-height: 26px;">
                                     Date time: <strong style="color: #000;">${data.session_date} ${data.str_time} IST</strong>
                         </p>
                             <p style="color: #666; line-height: 26px;">with therapist <strong style="color: #000;">${data.therapist_fname}</strong> <strong style="color: #000;">${data.therapist_lname}</strong>
                         has been canceled.</p>		
                                     <p style="color: #666; line-height: 26px;">For further questions or assistance please email <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a></p>
                                     </td></tr>
                                     </tbody>
                                 <tfoot>		
                                     <tr><td><p style="color: #666; line-height: 26px;">Thank you,<br/>ShivYog Cosmic Therapy Team<br/><a href="#">www.shivyogcosmictherapy.com</a></p></td></tr>
                         
                                     <tr><td style="padding: 15px 0; border-top:1px solid #000">Please add <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a> to your address book to ensure inbox delivery.</td></tr>
                             </tfoot>
                             </table>
                             
                         </body>
                         </html>
                         
                         `;
                       }


                       function email_therapist_cancel_Html (data){
                        return `<!doctype html>
                        <html>
                        <head>
                        <meta charset="utf-8">
                        <title>:: ShivYog ::</title>
                        <style>
                            body{box-sizing: border-box; padding: 0; margin: 0;}
                            table{width: 100%; max-width: 750px; font-family: Helvetica; font-size: 16px; margin: 0px auto;}
                        </style>
                            </head>
                        
                        <body>
                            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 750px; font-family: Helvetica; font-size: 16px; margin: 0px auto;">
                                <thead><tr><td style="padding: 15px 0;"><img src="https://api.ashoresystems.ws/public/assets/images/brand.jpg"></td></tr></thead>
                                <tbody><tr><td style="padding: 15px 0; border-top:1px solid #000">
                                    <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.therapist_fname}</strong>,</p><p style="color: #666; line-height: 26px;">Your ShivYog Cosmic Therapy appointment with <strong style="color: #000;">${data.user_fname}</strong> <strong style="color: #000;">${data.user_lname}</strong> on</p><p style="color: #666;  line-height: 26px;">
                                    Date time: <strong style="color: #000;">${data.session_date} ${data.str_time} IST</strong>
                        </p>
                            <p style="color: #666; line-height: 26px;">with therapist 
                        has been canceled.</p>		
                                    <p style="color: #666; line-height: 26px;">For further questions or assistance please email <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a></p>
                                    </td></tr>
                                    </tbody>
                                <tfoot>		
                                    <tr><td><p style="color: #666; line-height: 26px;">Thank you,<br/>ShivYog Cosmic Therapy Team<br/><a href="#">www.shivyogcosmictherapy.com</a></p></td></tr>
                        
                                    <tr><td style="padding: 15px 0; border-top:1px solid #000">Please add <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a> to your address book to ensure inbox delivery.</td></tr>
                            </tfoot>
                            </table>
                            
                        </body>
                        </html>                                    
                        `;
                      }


                                var email_data = {
                                  "from": req.session.auth_data.email,
                                  "to": body.user_email,
                                  "subject": "Appointment Cancelled",
                                  "text": "Hello " + body.user_fname + " " + body.user_lname +"",
                                  "html": email_user_cancel_Html(body)
                                };

                                var email_thrapist_data = {
                                    "from": req.session.auth_data.email,
                                    "to": body.therapist_email,
                                    "subject": "Appointment Cancelled",
                                    "text": "Hello " + body.user_fname + " " + body.user_lname +"",
                                    "html": email_therapist_cancel_Html(body)
                                  };
                                
                                email(email_data);

                                email(email_thrapist_data);
                                
                                const data = { date: new Date().toISOString().slice(0, 10) };
                                upcomingappointmentslist(data,(error, result) => {
                                    if (!error) {
                                        
                                        return res.json({
                                            success: 1,
                                            message: "Appointment is canceled",
                                            data: result
                                        });
                                    }
                                }); 
                              }
                            });
                    }
                }
            });
        
                
            }
        });
    },
    upcomingappointmentslist: (req, res) => {
        const data = { date: new Date().toISOString().slice(0, 10) };
        upcomingappointmentslist(data, (error, result) => {
            if (error) {
                console.log(error);
            } else {
                res.locals = { title: 'Appointments List' };
                res.render('admin/appointments/upcoming_appointments_list', { auth_data: req.session.auth_data, data: result, message: req.flash('message'), status: req.flash('status') });
            }
        });
    },
    pastappointmentslist: (req, res) => {
        const data = { date: new Date().toISOString().slice(0, 10) };
        pastappointmentslist(data, (error, result) => {
            if (error) {
                console.log(error);
            } else {
                res.locals = { title: 'Appointments List' };
                res.render('admin/appointments/past_appointments_list', { auth_data: req.session.auth_data, data: result, message: req.flash('message'), status: req.flash('status') });
            }
        });
    },
    appointmentsharelink: (req, res) => {
        const body = req.body;
        console.log(body);
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
                                const stt_time =data.session_time.split('to');
                                body.stt_time = stt_time[0];
                                body.session_date = data.session_date;
                                body.result = result;
                                
                                function email_share_link_Html (data){
                                    return `<!doctype html>
                                    <html>
                                    <head>
                                    <meta charset="utf-8">
                                    <title>:: ShivYog ::</title>
                                    <style>
                                        body{box-sizing: border-box; padding: 0; margin: 0;}
                                        table{width: 100%; max-width: 750px; font-family: Helvetica; font-size: 16px; margin: 0px auto;}
                                    </style>
                                        </head>
                                    
                                    <body>
                                        <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 750px; font-family: Helvetica; font-size: 16px; margin: 0px auto;">
                                            <thead><tr><td style="padding: 15px 0;"><img src="https://api.ashoresystems.ws/public/assets/images/brand.jpg"></td></tr></thead>
                                            <tbody><tr><td style="padding: 15px 0; border-top:1px solid #000">
                                                <p style="color: #666; line-height: 26px;">Namah Shivay!</p><p style="color: #666; line-height: 26px;">
                                                You have received a trainee link to join the upcoming ShivYog Cosmic Therapy session
                                                being provided by <strong style="color: #000;">${data.result.f_name}</strong> associated with the zoom link <strong style="color: #000;">${data.result.l_name}</strong> associated with the zoom link.</p>
                                                <p style="color: #666;  line-height: 26px;">Below are the details of your appointment.</p>
                                                
                                                <p style="color: #666;  line-height: 26px;">
                                                Date time: <strong style="color: #000;">${data.session_date} ${data.stt_time} IST</strong><br/>
                                                Session duration: <strong style="color: #000;">1 hour</strong><br/>
                                    Zoom link:<strong style="color: #000;">
                                    ${data.result.zoom_link}
                                                </strong><br/>
                                                Zoom ID:<strong style="color: #000;">${data.result.zoom_id}</strong><br/>
                                                Zoom password: <strong style="color: #000;">${data.result.zoom_password}</strong>
                                    </p><p style="color: #666; line-height: 26px;">For further questions or assistance please email <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a></p>
                                                </td></tr>
                                                </tbody>
                                            <tfoot>		
                                                <tr><td><p style="color: #666; line-height: 26px;">Thank you,<br/>ShivYog Cosmic Therapy Team<br/><a href="#">www.shivyogcosmictherapy.com</a></p></td></tr>
                                    
                                                <tr><td style="padding: 15px 0; border-top:1px solid #000">Please add <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a> to your address book to ensure inbox delivery.</td></tr>
                                        </tfoot>
                                        </table>
                                        
                                    </body>
                                    </html>                                   
                                    `;
                                  }
                                  console.log(body);
                                var email_data = {
                                    "from": req.session.auth_data.email,
                                    "to": body.email,
                                    "subject": "Zoom link ",
                                    "text": body.email,
                                    "html":email_share_link_Html(body) 
                                };
                                email(email_data);
                                return res.json({
                                    success: 1,
                                    message: "Zoom link send successfully"
                                });
                            }
                        }
                    });
                }
            }
        });
    },
    appointmentjoinlink:  (req, res) => {
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
    },
    transectionlist: (req, res) => {
        appointmentslist((error, result) => {
            if (error) {
                console.log(error);
            } else {
                res.locals = { title: 'Transection List' };
                res.render('admin/transections/transection_list', { auth_data: req.session.auth_data, data: result, message: req.flash('message'), status: req.flash('status') });
            }
        });
    }
};