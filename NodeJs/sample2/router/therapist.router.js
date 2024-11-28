const router = require('express').Router();
const multer = require('multer');
var path = require('path');
var uuid = require('uuid');

const { login } = require('../auth/auth');
const { dashboard } = require('../controllers/therapist/dashboard.controller');

const { schedulelist, show_schedules, createschedule, deleteschedule, editschedule, updateschedule, delete_schedule_time} = require('../controllers/therapist/schedule.controller');
const { myprofile, updateprofile } = require('../controllers/therapist/userprofile.controller');
const { country_codelist } = require('../controllers/therapist/country_code.controller');
const { theraappointmentslist,theraupcomingappointmentslist, therapastappointmentslist, appointmentjoinlink } = require('../controllers/therapist/appointmentsthera.controller');

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
router.get("/",login,dashboard);



//schedule
router.get("/schedule", login, schedulelist);
router.get("/schedule/:id", login, show_schedules);
router.post("/schedule/create", login, createschedule);
router.get("/schedule/delete/:id", login, deleteschedule);
router.post("/schedule/delete_schedule", login, delete_schedule_time);
router.get("/schedule/edit/:id", login, editschedule);
router.post("/schedule/update", login, updateschedule);

//appointments
router.get("/appointments",login,theraappointmentslist);
router.get("/appointments/upcoming-appointment",login,theraupcomingappointmentslist);
router.get("/appointments/past-appointment",login,therapastappointmentslist);
router.post("/appointment/joinlink",login, appointmentjoinlink);

//country_code
router.get("/country_code",login, country_codelist );

//therapist profile
router.get("/myprofile", login, myprofile);

router.post("/myprofile", login,upload.single('profile_img'), updateprofile);
module.exports = router;