const nodemailer = require('nodemailer');

module.exports = {
    test1email: (req, res) => {
        // Generate SMTP service account from ethereal.email
        nodemailer.createTestAccount((err, account) => {
            if (err) {
                console.error('Failed to create a testing account. ' + err.message);
                return res.exit(1);
            }
            console.log('Credentials obtained, sending message...');

            // Create a SMTP transporter object
            let transporter = nodemailer.createTransport({
                host: process.env.EMAIL_HOST,
                port: process.env.EMAIL_PORT,
                secure: false, // true for 465, false for other ports
                    auth: {
                    user: process.env.EMAIL_USER, // generated ethereal user
                    pass: process.env.EMAIL_PASSWORD, // generated ethereal password
                    },
            });

            // Message object
            let message = {
                from: 'Sender Name <sender@example.com>',
                to: 'Recipient <jaydeep_test21@yopmail.com>',
                subject: 'Nodemailer is unicode friendly ✔',
                text: 'Hello to myself!',
                html: '<p><b>Hello</b> to myself!</p>'
            };

            transporter.sendMail(message, (err, info) => {
                if (err) {
                    console.log('Error occurred. ' + err.message);
                    return res.json({
                        success: 0,
                        message: err
                    });
                }

                console.log('Message sent: %s', info.messageId);
                // Preview only available when sending through an Ethereal account
                console.log('Preview URL: %s', nodemailer.getTestMessageUrl(info));
            });
        });
    }
}