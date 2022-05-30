const sgMail = require( '@sendgrid/mail' );
sgMail.setApiKey( "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" );


const mailFrom = 'no-reply@signage.com';
const mailFromName = 'signage';

module.exports = {
    testing123email: (req, res) => {
        
    const msg = {
        to: "jaydeep_test221@yopmail.com",
        from: { 'email': mailFrom, 'name': mailFromName },
        subject: "demo",
        text: "demo",
        html: "demo",
    };

    try {
    var data = sgMail.send( msg );
    console.log(data);
    return res.json({
        success: 1,
        message: data
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
}
};
