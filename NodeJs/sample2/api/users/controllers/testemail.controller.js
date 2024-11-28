const nodemailer = require('nodemailer');

module.exports = {
    testingemail: (req, res) => {
        let transporter = nodemailer.createTransport({
            host: process.env.EMAIL_HOST,
            port: process.env.EMAIL_PORT,
            secure: false, // true for 465, false for other ports
            auth: {
              user: process.env.EMAIL_USER, // generated ethereal user
              pass: process.env.EMAIL_PASSWORD, // generated ethereal password
            },
          });
          transporter.sendMail({
            from: 'Shiv Yog <demo@gmail.com>', // sender address
            to: "jaydeep_test21@yopmail.com", // list of receivers
            subject: "demo", // Subject line
            text:"demo", // plain text body
            html: "demo", // html body
          }, (error, rel) => {
              console.log("start");
              
            if (error) {
              console.log(error);
              console.log(rel);
              return res.json({
                success: 0,
                message: error
            });
            } else {
              //console.log("Preview URL: %s", rel.getTestMessageUrl(rel));
              console.log(error);
              console.log(rel);
              return res.json({
                success: 1,
                message: rel
            });
            }
          });
    }
}