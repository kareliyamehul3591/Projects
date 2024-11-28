const { appointmentslist } = require('../../models/appointments.service');

module.exports = {

    dashboard: (req,res) => {
        res.locals = {  title: 'Dashboard' };
        req.flash('message', 'login Sucessfully!..');
        req.flash('status', 'success');
       // res.render('admin/dashboard/dashboard',{auth_data:req.session.auth_data,message:req.flash('message'),status:req.flash('status')});
       appointmentslist((error, result) => {
        if (error) {
            console.log(error);
        }else{             
            res.locals = { title: 'Appointments List' };
            res.render('admin/dashboard/dashboard',{auth_data:req.session.auth_data,data:result,message:req.flash('message'),status:req.flash('status')});
        }
    });
    }
};