const router = require('express').Router();
const multer = require('multer');
var path = require('path');
var uuid = require('uuid');

const { login } = require('../auth/auth');
const { dashboard } = require('../controllers/admin/dashboard.controller');
const { countrylist, statelist, country_codelist } = require('../controllers/admin/country_state.controller');
const { country_mobile_codelist } = require('../controllers/admin/country_code.controller'); 

const { adminlist, createadmin, deleteadmin, editadmin, updateadmin, resend_email} = require('../controllers/admin/admin.controller');
const { userlist, createuser, deleteuser, edituser, updateuser , resend_user_email} = require('../controllers/admin/user.controller');
const { therapistlist, email_check, createtherapist, deletetherapist, edittherapist, updatetherapist, resend_therapist_email } = require('../controllers/admin/therapist.controller');
const { moderatorlist, createmoderator, deletemoderator, editmoderator, updatemoderator } = require('../controllers/admin/moderator.controller');
const { appointmentslist,appointments_in_calendar,cancel_appointment, upcomingappointmentslist, pastappointmentslist, appointmentsharelink, appointmentjoinlink, transectionlist } = require('../controllers/admin/appointments.controller');
const { cmslist, createcms, insertcms, deletecms, editcms, updatecms, viewcontent } = require('../controllers/admin/cms.controller');
const { slider_setting_list, create_slider_setting_list, insert_image,delete_slider, edit_slider, update_slider, viewcontent_slider} = require('../controllers/admin/web_setting.controller');
const { social_media_list, create_social_media, insert_social_media, delete_social_media, edit_social_media, update_social_media} = require('../controllers/admin/social_media.controller');
const { slider_list, create_slider_list, insert_slider_image,delete_slider_image, edit_slider_image, update_slider_image, viewcontent_slider_image} = require('../controllers/admin/success_story_slider.controller');
const { settings, edit_settings, update_settings, viewcontent_settings} = require('../controllers/admin/settings.controller');
const { myprofile,updateprofile } = require('../controllers/admin/admin_profile.controller');
const { profilerequest, profilerequestaccept, profilerequestcancel } = require('../controllers/admin/profilerequest.controller');

// image upload 
const maxSize = 2 * 1024 * 1024;
const storage = multer.diskStorage({
    destination: (req,file,callback) => {
        callback(null,'images');
    },
    filename: (req,file,callback) => {
        console.log(file)
        callback(null,uuid.v4(0) + path.extname(file.originalname));
    }
});

const upload = multer({storage:storage, limits: { fileSize: maxSize }});

// dashboard
router.get("/",login,function (req,res,next) {
    if (req.session.auth_data.role == "super_admin") {
        next();
      } else {
        res.redirect('/admin/user');
      }
} ,dashboard);

// admin
router.get("/admin",login,adminlist);
router.post("/admin/create",login,createadmin);
router.get("/admin/delete/:id",login,deleteadmin);
router.get("/admin/edit/:id",login,editadmin);
router.post("/admin/update",login,updateadmin);

// user
router.get("/user",login,userlist);
router.post("/user/create",login,createuser);
router.get("/user/delete/:id",login,deleteuser);
router.get("/user/edit/:id",login,edituser);
router.post("/user/update",login,updateuser);

//profilerequest
router.get("/profile-request",login,profilerequest);
router.post("/profile-request/accept",login,profilerequestaccept);
router.post("/profile-request/cancel",login,profilerequestcancel);

//therapist
router.get("/therapist",login,therapistlist);
router.post("/therapist/create",login,upload.single('profile_img'),createtherapist);
router.post("/therapist/email_check",login,email_check);
router.get("/therapist/delete/:id",login,deletetherapist);
router.get("/therapist/edit/:id",login,edittherapist);
router.post("/therapist/update",login,upload.single('profile_img'),updatetherapist);

//moderator
router.get("/moderator",login,moderatorlist);
router.post("/moderator/create",login,createmoderator);
router.get("/moderator/delete/:id",login,deletemoderator);
router.get("/moderator/edit/:id",login,editmoderator);
router.post("/moderator/update",login,updatemoderator);

//appointments
router.get("/appointments",login,appointmentslist);
router.get("/appointments/calendar-view",login,appointments_in_calendar);
router.get("/appointments/cancel/:id",login,cancel_appointment);
router.get("/appointments/upcoming-appointment",login,upcomingappointmentslist);
router.get("/appointments/past-appointment",login,pastappointmentslist);
router.get("/transections",login,transectionlist);

//appointments sharelink
router.post("/appointment/sharelink",login, appointmentsharelink);
router.post("/appointment/joinlink",login, appointmentjoinlink);


// cms
router.get("/cms",login,cmslist);
router.get("/cms/create",login,createcms);
router.post("/cms/create",login,insertcms);
router.get("/cms/delete/:id",login,deletecms);
router.get("/cms/edit/:id",login,editcms);
router.post("/cms/update",login,updatecms);
router.get("/cms/viewcontent/:id",login,viewcontent);

//web setting
router.get("/slider_setting_list",login,slider_setting_list);
router.get("/slider_setting_list/create",login,create_slider_setting_list);
router.post("/slider_setting_list/create",login,upload.single('images'),insert_image);
router.get("/slider_setting_list/delete/:id",login,delete_slider);
router.get("/slider_setting_list/edit/:id",login,edit_slider);
router.post("/slider_setting_list/update",login,upload.single('images'),update_slider);
router.get("/slider_setting_list/viewcontent/:id",login,viewcontent_slider);

//success story slider
router.get("/slider_list",login,slider_list);
router.get("/slider_list/create",login,create_slider_list);
router.post("/slider_list/create",login,upload.single('images'),insert_slider_image);
router.get("/slider_list/delete/:id",login,delete_slider_image);
router.get("/slider_list/edit/:id",login,edit_slider_image);
router.post("/slider_list/update",login,upload.single('images'),update_slider_image);
router.get("/slider_list/viewcontent/:id",login,viewcontent_slider_image);

//setting
router.get("/settings",login,settings);
router.get("/settings/edit/:id",login,edit_settings);
router.post("/settings/update",login,upload.single('images'),update_settings);
router.get("/settings/viewcontent/:id",login,viewcontent_settings);

//social-media
router.get("/social_media",login,social_media_list);
router.get("/social_media/create",login,create_social_media);
router.post("/social_media/create",login,upload.single('images'),insert_social_media);
router.get("/social_media/delete/:id",login,delete_social_media);
router.get("/social_media/edit/:id",login,edit_social_media);
router.post("/social_media/update",login,upload.single('images'),update_social_media);

//country_state
router.get("/country",login, countrylist );
router.post("/state",login, statelist );

//country_code
router.post("/country_code",login, country_codelist );
router.get("/country_mobile_code",login, country_mobile_codelist );

//Admin_profile
router.get("/myprofile",login, myprofile);
router.post("/myprofile", login, upload.single('profile_img'), updateprofile);

//Resend_Email
router.post("/resend_email",login,resend_email);
router.post("/resend_therapist_email",login,resend_therapist_email);
router.post("/resend_user_email",login,resend_user_email);


module.exports = router;