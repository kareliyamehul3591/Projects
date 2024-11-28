const router = require('express').Router();
const { checkToken } = require("../../../auth/token_validation");
const { login, socialmedialogin } = require("../controllers/login.controller");

const { therapistlist, therapistbyid } = require("../controllers/therapist/therapist.controller");
const { filterfunctionality } = require("../controllers/filter/filter.controller");
const { schedulelist, getschedulebydate } = require("../controllers/schedule/schedule.controller");
const { ragistrationemail, getdatabytoken, generatepassword, resetpassword , updateprofile, email_check} = require('../controllers/user.controller');
/* const { testingemail } = require('../controllers/testemail.controller');
const { test1email } = require('../controllers/test1.controller');
const { testing123email } = require('../controllers/sendgrid.controller'); */ 
const { forgetpasswordemail, getdatabytokens, generatepasswords, forgotpassword } = require('../controllers/userforgot.controller');
const { cmslist, image_slider_list, success_image_list, settings, social_media} = require('../controllers/cms/cms.controller');
const { add_appointment, appointment_info, appointmentbyid, appointments_byid} = require('../controllers/appointment/appointment.controller');
const { reschedule_appointment } = require('../controllers/appointment/updateappointment.controller');

const { add_card_details} = require('../controllers/payment/payment.controller'); 
 const { checkbyid, zoom_event, zoom_email, zoommeeting_link} = require('../controllers/zoom/zoom.controller'); 
const { countrylist, statelist } = require('../controllers/countrylist_statelist.controller');

router.use(function (req, res, next) {


    res.setHeader('Access-Control-Allow-Origin', '*');


    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');

    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');

    res.setHeader('Access-Control-Allow-Credentials', true);

    // Pass to next layer of middleware
    next();
});

//email
/* router.post('/send_email',testingemail);
router.post('/sending_email',test1email); */
/* router.post('/sending12_email',testing123email); */


//login
router.post('/login',login);
router.post('/socialmedialogin',socialmedialogin);

//therapist
router.get('/therapist',therapistlist);
router.get('/therapist/:therapist_id',therapistbyid);

//schedule
router.get('/therapist/schedule_list/:therapist_id',schedulelist);
router.post('/therapist/schedule_list/get_schedule_by_date',getschedulebydate);

// user create
router.post('/user/ragistration/sed_email',ragistrationemail);
router.get('/getdata_by_token/:token', getdatabytoken);
router.post('/generatepassword', generatepassword);
router.post('/user/resetpassword', resetpassword);
router.post("/user/email_check",email_check);

// user password update
router.post('/user/forgetpassword/sed_email',forgetpasswordemail);
router.get('/getdata_by_token/:token', getdatabytokens);
router.post('/generatepassword', generatepasswords);
router.post('/user/forgotpassword', forgotpassword);
router.post('/user/updateprofile', updateprofile);

// appointment
router.post('/appointment/add_appointment', add_appointment);
router.get('/appointment/appointment_info', appointment_info);
router.post('/appointment/:user_id',appointmentbyid);
router.get('/appointment/:id',appointments_byid);
router.post('/reschedule_appointment', reschedule_appointment);
 
// cms
router.get('/cms',cmslist);
router.get('/cms/:id',cmslist);
router.get('/image_slider',image_slider_list);
router.get('/image_slider/:id',image_slider_list);
router.get('/success_image_slider',success_image_list);
router.get('/success_image_slider/:id',success_image_list);
router.get('/settings',settings);
router.get('/social_media',social_media);
//
//
router.get("/country", countrylist );
router.post("/state", statelist );

//payment
 router.post('/payment/card_payment', add_card_details); 

 // checking api for therapist, session_time, session_date 
 router.post('/zoom/checking', checkbyid); 
 router.post('/zoom/zoom_info', zoom_event); 
 router.post('/zoom/zoom_info/send_email', zoom_email); 


 // zoom
 router.post('/zoom/get_link', zoommeeting_link);  


 // filter
 router.post('/therapist/fiter', filterfunctionality );
 //router.post('/therapist/therapist_type_fiter');

module.exports = router;