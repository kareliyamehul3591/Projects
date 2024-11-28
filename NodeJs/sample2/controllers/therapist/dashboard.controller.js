const { appointments_thera_list  } = require('../../models/appointment_thera.service');

module.exports = {

    dashboard: (req,res) => {
        res.locals = {  title: 'Dashboard' };
        req.flash('message', 'login Sucessfully!..');
        req.flash('status', 'success');
       // res.render('therapist/dashboard/dashboard',{auth_data:req.session.auth_data,message:req.flash('message'),status:req.flash('status')});
        const thrapist_id = req.session.auth_data.id;
        console.log(thrapist_id);
        appointments_thera_list(thrapist_id,(error, result) => {
            if (error) {
                console.log(error);
            }else {  
                console.log(error);           
                res.locals = { title: 'Appointments List' };
                res.render('therapist/dashboard/dashboard',{auth_data:req.session.auth_data,data:result,message:req.flash('message'),status:req.flash('status')});
            }
        });

        
    }
};