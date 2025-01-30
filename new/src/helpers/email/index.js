'use strict';
const sgMail = require( '@sendgrid/mail' );
sgMail.setApiKey( process.env.SENDGRID_API_KEY || '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' );
const Handlebars = require( 'handlebars' );

const fs = require( 'fs' );
const path = require( 'path' );
const mailFrom = 'no-reply@signage.com';
const mailFromName = 'signage';
// const moment = require( 'moment' );

// const initControllers = function( ) {
//     return {
//         'ContactController': require( '../../modules/contact/contact.controller' ),
//     };
// };

const sendEmail = async function( toEmail, subject, contentHtml ) {
    const msg = {
        'to': toEmail,
        'from': { 'email': mailFrom, 'name': mailFromName },
        'subject': subject,
        'text': subject,
        'html': contentHtml,
    };

    try {
        await sgMail.send( msg );
    } catch ( error ) {
        if ( error.response ) {
            console.error( error.response.body );
        }
    }
};

const sendEmailByTemplateData = async function( template, toEmail, subject, data ) {
    data.MEDIA_BASE_URL = `${process.env.MEDIA_BASE_URL }/email-images/`;
    try {
        const html = fs.readFileSync( path.resolve( `${__dirname}/views/${template}.html` ), 'utf8' );
        const htmlTemplate = Handlebars.compile( html );
        const htmlContent = htmlTemplate( { ...data } );

        await sendEmail( toEmail, subject, htmlContent );
    } catch ( e ) {
        throw e;
    }
};


const otpEmail = async function( data ) {
    try {
        await sendEmailByTemplateData( 'forgot-password-otp', data.email, 'Signage! Reset password OTP Request !', {
            ...data
        } );
    } catch( e ) {
        console.log( e );
    }

};

module.exports = { otpEmail };
