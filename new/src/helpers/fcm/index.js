'use strict';
const admin = require( 'firebase-admin' );

const serviceAccount = require( '../../configs/digital-signage-b8071-firebase-adminsdk-jo14k-de88d1839a.json' );

admin.initializeApp( {
    'credential': admin.credential.cert( serviceAccount )
} );

module.exports.sendNotification = ( tokens, title, body, notificationId ) => {
    const message = {
        'notification': { 'title': title, 'body': body },
        'data': {
            'notificationId': notificationId.toString()
        }
    };
    console.log(tokens);
      // Send a message to the device corresponding to the provided
    // registration token.
    admin.messaging().sendToDevice( tokens, message, { 'priority': 'high' } )
        .then( () => {
            // Response is a message ID string.
            console.log('Successfully sent message:', message);
        } )
        .catch( ( error ) => {
            console.log( 'Error sending message:', error );
        } );

};