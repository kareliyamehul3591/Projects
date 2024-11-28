
const {check_info, insertdata, update_zoom_data, select_zoom_data, get_zoom_link } = require('../../models/zoom.service');
const { email } = require('../../../../controllers/email.controller');
const {appointment_by_id} = require('../../models/appointment.service');
module.exports = {
    checkbyid:(req,res)=>{
        const body = req.body;
        console.log(body); 
        
        const zoom_time = body.session_time.split("to");
        const meeting_time = zoom_time[0];
        //const zoom_date = (new Date(new Date(body.session_date+ meeting_time)).toISOString());
        const zoom_date = body.session_date;
        const UTC = zoom_date + " " + meeting_time;
        const IOS = (new Date(new Date(UTC)).toISOString());
        console.log(meeting_time);
        console.log(zoom_date);
        console.log(IOS);

        check_info(body,(error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                if (result.total_row == 0) {
                    insertdata(body, (error, result) => {
                        if (error) {
                            console.log(error);
                            return res.json({
                                success: 0,
                                message: "Database connection errror"
                            });
                        } else {
                            if (result != "") {
                            
                                const jwt = require('jsonwebtoken');
                                const rp = require('request-promise');
                                var resp;
                                //Use the ApiKey and APISecret from config.js
                                const payload = {
                                iss: process.env.ZOOM_API_KEY,
                                exp: ((new Date(body.session_date)).getTime() + 5000)
                                };
                                const token = jwt.sign(payload, process.env.ZOOM_API_SECRET);

                                var options = {
                                'method': 'POST',
                                
                                'url': 'https://api.zoom.us/v2/users/me/meetings',
                                'headers': {
                                  'Authorization': 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOm51bGwsImlzcyI6IkNwME1kXzkxU2V5c1RZbkQyUzdJUHciLCJleHAiOjE5MjQ4NDI2MDAsImlhdCI6MTY1MTQ3MjM0MX0.M4EyGExDj4xugRUcEP5le0prTuwAGqk5CL_dwYGZaik',
                                  'Content-Type': 'application/json',
                                  'Cookie': 'TS01a42040=011792258ed5d1a088f3c6c76b7c710e2c727f0815e21abeb6a899b4efe831e14b524d29d7ba31bf4b1c8a1fa7921f9b451f0c079a; _zm_mtk_guid=5c6c3c6cfac44376bab36422890d2d31; _zm_page_auth=us05_c_rtDSnHs3SqG0uRmySBMzTw; _zm_ssid=us05_c_S5Y4WY9wSxyxMRfHfUa8Tg; TS01d0dc3f=011792258ed5d1a088f3c6c76b7c710e2c727f0815e21abeb6a899b4efe831e14b524d29d7ba31bf4b1c8a1fa7921f9b451f0c079a; cred=B2DDAC2F2D9C7B6B24C8FC825D344AFA'
                                },
                                body: JSON.stringify({

                                  "start_time": IOS,
                                  "timezone":"UTC"
                                })
                              
                              };
                              
                              //Use request-promise module's .then() method to make request calls.
                              rp(options)
                                  .then(function (response) {
                                    //printing the response on the console
                                      console.log('User has', response);
                                      //console.log(typeof response);
                                      resp = response 
                                      //Prettify the JSON format using pre tag and JSON.stringify
                                      var result = JSON.stringify(resp, null, 2);
                                      res.send(result);
                               
                                  })
                                  .catch(function (err) {
                                      // API call failed...
                                      console.log('API call failed, reason ', err);
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
                        success: 2,
                        message: "Record is Already in Database"
                    });
                }
            }
        });
    },
    zoom_event:(req,res)=>{
        const body = req.body;
        update_zoom_data(body,(error, result) => {
                
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
                        if (!error) {
                            return res.json({
                                success: 1,
                                message: "User zoom data updated successfully"
                            });
                        }       
                    }
            }
        });
    },
    zoom_email: (req, res) => {
        const body = req.body;
        const st_time = body.session_time.split( 'to' );
        body.st_time = st_time[0];
        console.log(body);
        select_zoom_data(body,(error, result) => {
            if (error) {
                console.log(error);
                return res.json({
                    success: 0,
                    message: "Database connection errror"
                });
            } else {
                if (result != "") {

                    body.user_data = result;
                    function emailHtml (data){
                        if(data.reschedule_status == 0)
                        {
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
                                    <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.user_data.user_name}</strong>,</p><p style="color: #666; line-height: 26px;">Thank you for booking an appointment with ShivYog Cosmic Therapy. Below are the
                        details of your appointment. The link will only work on the date and time of your
                        appointment for the duration that you booked.</p><p style="color: #666;  line-height: 26px;">
                                    Therapist: <strong style="color: #000;">${data.user_data.f_name}</strong> <strong style="color: #000;">${data.user_data.l_name}</strong><br/>
                                    Therapist designation: <strong style="color: #000;">${data.user_data.designation}</strong><br/>
                                    Date time: <strong style="color: #000;">${data.session_date} ${data.st_time} IST</strong><br/>
                                    Session duration: <strong style="color: #000;">1 hour</strong><br/>
                        Zoom link:<strong style="color: #000;">
                        ${data.user_data.zoom_link}
                        </strong><br/>
                                    Zoom ID:<strong style="color: #000;">${data.user_data.zoom_id}</strong><br/>
                                    Zoom password: <strong style="color: #000;">${data.user_data.zoom_password}</strong>
                        </p><p style="color: #666; line-height: 26px;">Please note that this is an online only session. You will need to ensure stable internet
                        connectivity for the entire duration of the session.</p><p style="color: #666; line-height: 26px;">
                        You can reschedule this appointment within 24 hours of receiving this email. Please log into your account for rescheduling the appointment.<br/>
                        The 24 hour window should not be reset with every new rebooking. How many times?
                        The price has to remain the same - the therapist options displayed while rescheduling
                        should be with the same price as the original booking (not less, nor more).</p><p style="color: #666; line-height: 26px;">For further questions or assistance please email <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a></p>
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
                        }else{
                            
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
                                        <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.user_data.user_name}</strong>,</p><p style="color: #666; line-height: 26px;">You have rescheduled your appointment with <strong style="color: #000;">${data.user_data.f_name}</strong> <strong style="color: #000;">${data.user_data.l_name}</strong> on <strong style="color: #000;">${data.session_date} ${data.st_time} IST</strong></p>
                                        <p style="color: #666;  line-height: 26px;">Below are the details of your new appointment. The link will only work on the date and
                            time of your appointment for the duration that you booked.</p>
                                        
                                        <p style="color: #666;  line-height: 26px;">
                                        Therapist: <strong style="color: #000;">${data.user_data.f_name}</strong> <strong style="color: #000;">${data.user_data.l_name}</strong><br/>
                                        Therapist designation: <strong style="color: #000;">${data.user_data.designation}</strong><br/>
                                        Date time: <strong style="color: #000;">${data.session_date} ${data.st_time} IST</strong><br/>
                                        Session duration: <strong style="color: #000;">1 hour</strong><br/>
                            Zoom link:<strong style="color: #000;">
                            ${data.user_data.zoom_link}
                                        </strong><br/>
                                        Zoom ID:<strong style="color: #000;">${data.user_data.zoom_id}</strong><br/>
                                        Zoom password: <strong style="color: #000;">${data.user_data.zoom_password}</strong>
                            </p><p style="color: #666; line-height: 26px;">Please note that this is an online only session. You will need to ensure stable internet
                            connectivity for the entire duration of the session.</p><p style="color: #666; line-height: 26px;">For further questions or assistance please email <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a></p>
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
                      }
                      
                      function email_therapist_booked_Html (data){
                        if(data.reschedule_status == 0)
                        {
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
                                    <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.user_data.f_name}</strong>,</p><p style="color: #666; line-height: 26px;">
                                    <strong style="color: #000;">${data.user_data.user_name}</strong> <strong style="color: #000;">${data.user_data.user_l_name}</strong> has booked an appointment with you
                        through the ShivYog Cosmic Therapy platform. Below are the details of the
                        appointment.</p><p style="color: #666;  line-height: 26px;">
                                    Date time: <strong style="color: #000;">${data.session_date} ${data.st_time} IST</strong><br/>
                                    Session duration: <strong style="color: #000;">1 hour</strong><br/>
                        Zoom link:<strong style="color: #000;">
                        ${data.user_data.zoom_link}
                                    </strong><br/>
                                    Zoom ID:<strong style="color: #000;">${data.user_data.zoom_id}</strong><br/>
                                    Zoom password: <strong style="color: #000;">${data.user_data.zoom_password}</strong>
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
                        }else{
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
                                        <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.user_data.f_name}</strong>,</p><p style="color: #666; line-height: 26px;">
                                        <strong style="color: #000;">${data.user_data.user_name}</strong> <strong style="color: #000;">${data.user_data.user_l_name}</strong> has rescheduled their appointment on
                            <strong style="color: #000;">${data.session_date} ${data.st_time} IST</strong> with you.</p><p style="color: #666;  line-height: 26px;">
                                        Date time: <strong style="color: #000;">${data.session_date} ${data.st_time} IST</strong><br/>
                                        Session duration: <strong style="color: #000;">1 hour</strong><br/>
                            Zoom link:<strong style="color: #000;">
                            ${data.user_data.zoom_link}
                                        9</strong><br/>
                                        Zoom ID:<strong style="color: #000;">${data.user_data.zoom_id}</strong><br/>
                                        Zoom password: <strong style="color: #000;">${data.user_data.zoom_password}</strong>
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
                      }
                  
                    var email_data = {
                        "from": "demo@gmail.com",
                        "to": body.email,
                        "subject": "Zoom Meeting",
                        "text": "Hello " + body.user_data.user_name,
                        "html": emailHtml(body),
                    };
                    var email_therapist_data = {
                        "from": "demo@gmail.com",
                        "to": body.user_data.therapist_email,
                        "subject": "Zoom Meeting",
                        "text": "Hello " + body.user_data.f_name,
                        "html": email_therapist_booked_Html(body),
                    };
                    email(email_data);  
                    email(email_therapist_data);  
                    
                    
                } else {
                    return res.json({
                        success: 0,
                        message: "Something has Wrong"
                    });
                }
            }
        });
    },
    zoommeeting_link: (req,res) => {
        const body = req.body;
        console.log(body)
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