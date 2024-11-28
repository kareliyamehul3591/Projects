/* const nodemailer = require('nodemailer'); */
const sgMail = require( '@sendgrid/mail' );
sgMail.setApiKey( "ApiKey" ); 

module.exports = {
  email: function (email_data) {
    const msg = {
      from: 'Shiv Yog <' + email_data.from + '>', // sender address
    to: email_data.to, // list of receivers
    subject: email_data.subject, // Subject line
    text: email_data.text, // plain text body
    html: email_data.html, // html body
  };

  try {
    var data = sgMail.send( msg );
    console.log(data);
    return res.json({
        success: 1,
        message: "Email Send Successfully"
    });
    } catch ( error ) {
        if ( error.response ) {
            console.log(error.response);
            console.error( error.response.body );
            return res.json({
                success: 0,
                message: error
            });
        }
    }

 /*  let transporter = nodemailer.createTransport({
    host: process.env.EMAIL_HOST,
    port: process.env.EMAIL_PORT,
    secure: false, // true for 465, false for other ports
    auth: {
      user: process.env.EMAIL_USER, // generated ethereal user
      pass: process.env.EMAIL_PASSWORD, // generated ethereal password
    },
  });
  transporter.sendMail({
    from: 'Shiv Yog <' + email_data.from + '>', // sender address
    to: email_data.to, // list of receivers
    subject: email_data.subject, // Subject line
    text: email_data.text, // plain text body
    html: email_data.html, // html body
  }, (err, res) => {
    if (err) {
      console.log(err);
      return {
          success: 0,
          message: "Something Went Wrong"
        };
    } else {
       console.log("Message sent: %s", res.messageId);
      console.log("Preview URL: %s", nodemailer.getTestMessageUrl(res)); 
      return {
        success: 1,
        message: "Email Send Successfully"
      };
    }
  });
  /*     console.log(info);
      console.log("Message sent: %s", info);
      // Message sent: <b658f8ca-6296-ccf4-8306-87d57a0b4321@example.com>
  
      // Preview only available when sending through an Ethereal account
      console.log("Preview URL: %s", nodemailer.getTestMessageUrl(info)); */
} 
};