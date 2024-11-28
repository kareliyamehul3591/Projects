const { userlist, createuser, deleteuser, edituser, updateuser } = require('../../models/user.service');
var uuid = require('uuid');
const { insertcreatelink } = require('../../models/userconfirmationlink.service');
const { genSaltSync, hashSync, compareSync } = require('bcrypt');
const { email } = require('../email.controller');
const ejs = require("ejs");

module.exports = {
  adminlist: (req, res) => {
    // res.redirect('/login');
    userlist("admin", (error, result) => {
      if (!error) {
        res.locals = { title: 'Admin List' };
        res.render('admin/admin/admin_list', { 'user_data': result, auth_data: req.session.auth_data, message: req.flash('message'), status: req.flash('status') });
      }
    });
    //  console.log(user_data);

    //res.render('admin/user/user_list');
  },
  createadmin: (req, res) => {
    const body = req.body;
    body.unique_id = uuid.v4(0);
    body.role = 'admin';
    body.status = "2";
    createuser(body, (error, result) => {
      if (error) {
        console.log(error);
        return res.json({
          success: 0,
          message: "Database connection errror"
        });
      } else {
        const linkdata = {"user_id":result.insertId,"token":uuid.v4(0)};
        
        insertcreatelink(linkdata, (error, result) => {
          console.log(error);
          if (!error) {
            
   function emailHtml (data){
     return `<!doctype html>
     <html>
     <head>
     <meta charset="utf-8">
     <title>:: ShivYog ::</title>
     <style>
       body{box-sizing: border-box; padding: 0; margin: 0;}
       table{width: 100%; max-width: 700px; font-family: Helvetica; font-size: 16px; margin: 0px auto;}
     </style>
       </head>
     
     <body>
       <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 700px; font-family: Helvetica; font-size: 16px; margin: 0px auto;">
         <thead><tr><td style="padding: 15px 0;"><img src="https://api.ashoresystems.ws/public/assets/images/brand.jpg"></td></tr></thead>
         <tbody><tr><td style="padding: 15px 0; border-top:1px solid #000">
           <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.f_name}</strong>,</p><p style="color: #666;">Welcome to ShivYog Cosmic Therapy Platform.</p><p style="color: #666;  line-height: 26px;">
     To activate your account, please click the link below and follow the steps as directed.
     <a href="${data.url}" target="_blank" rel="noopener noreferrer" style="color: #000; text-decoration: underline;">Generate Password</a></p><p style="color: #666; line-height: 26px;">
     The link will expire within 5 days.<br/>
     For further questions or assistance please email <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a></p>
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
    body.url = process.env.AAP_URL + "generatepassword/" + linkdata.token ;

            var email_data = {
              "from": req.session.auth_data.email,
              "to": body.email,
              "subject": "Admin Create",
              "text": "Hello " + body.f_name + " " + body.l_name +"",
              "html": emailHtml(body)
            };
            
            email(email_data);
            
          }
        });
        userlist("admin", (error, result) => {
          if (!error) {
            return res.json({
              success: 1,
              message: "Admin Create Successfully",
              data: result
            });
          }
        });

      }
    });
  },
  deleteadmin: (req, res) => {
    const id = req.params.id;
    deleteuser(id, (error, result) => {
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
          userlist("admin", (error, result) => {
            if (!error) {
              return res.json({
                success: 1,
                message: "Admin deleted successfully",
                data: result
              });
            }
          });
        }
      }
    });
  },
  editadmin: (req, res) => {
    const id = req.params.id;
    edituser(id, (error, result) => {
      if (error) {
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
            data: result
          });
        }
      }
    });
  },
  updateadmin: (req, res) => {
    const body = req.body;
    updateuser(body, (error, result) => {
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
          userlist("admin", (error, result) => {
            if (!error) {
              return res.json({
                success: 1,
                message: "Admin updated successfully",
                data: result
              });
            }
          });
        }
      }
    });
  },
  resend_email: (req, res) => {
    const body = req.body;
    edituser(body.user_id, (error, edituser) => {
      if (error) {
        return res.json({
          success: 0,
          message: "Database connection errror"
        });
      } else {
        if (!edituser) {
          return res.json({
            success: 0,
            message: "Record Not Found"
          });
        } else {
          const linkdata = {"user_id":body.user_id,"token":uuid.v4(0)};
          insertcreatelink(linkdata, (error, result) => {
            console.log(error);
            if (!error) {
              function emailHtml (data){
                return `<!doctype html>
                <html>
                <head>
                <meta charset="utf-8">
                <title>:: ShivYog ::</title>
                <style>
                  body{box-sizing: border-box; padding: 0; margin: 0;}
                  table{width: 100%; max-width: 700px; font-family: Helvetica; font-size: 16px; margin: 0px auto;}
                </style>
                  </head>
                
                <body>
                  <table cellpadding="0" cellspacing="0" border="0" style="width: 100%; max-width: 700px; font-family: Helvetica; font-size: 16px; margin: 0px auto;">
                    <thead><tr><td style="padding: 15px 0;"><img src="https://api.ashoresystems.ws/public/assets/images/brand.jpg"></td></tr></thead>
                    <tbody><tr><td style="padding: 15px 0; border-top:1px solid #000">
                      <p style="color: #666; line-height: 26px;">Namah Shivay <strong style="color: #000;">${data.f_name}</strong>,</p><p style="color: #666;">Welcome to ShivYog Cosmic Therapy Platform.</p><p style="color: #666;  line-height: 26px;">
                To activate your account, please click the link below and follow the steps as directed.
                <a href="${data.url}" target="_blank" rel="noopener noreferrer" style="color: #000; text-decoration: underline;">Generate Password</a></p><p style="color: #666; line-height: 26px;">
                The link will expire within 5 days.<br/>
                For further questions or assistance please email <a href="#" style="color: #000; text-decoration: underline;">info@absclp.com</a></p>
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
              edituser.url = process.env.AAP_URL + "generatepassword/" + linkdata.token ;
              var email_data = {
                "from": req.session.auth_data.email,
                "to": edituser.email,
                "subject": "Admin Create",
                "text": "Hello " + edituser.f_name + " " + edituser.l_name,
                "html": emailHtml(edituser)
              };
              email(email_data);
              return res.json({
                success: 1,
                message: "Send Email Successfully"
              });
            }
          });
        }
      }
    });
  }
};