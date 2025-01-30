'use strict';
const { CalmService } = require( '../../../system/core/CalmService' );
const PushNotification = require( '../../helpers/fcm' );

class PushNotificationService extends CalmService {
    constructor( model, displayModel ) {
        super( model );
        this.displayModel = displayModel;

    }

     /**
     * For sending notification
     */
      async sendNotifications( body ) {
        try {
            const data = await this.displayModel.find( { 'displayId': { '$exists': true, '$ne': null } } );
            const tokens = data.map( x => x.displayId );
            PushNotification.sendNotification( tokens, body.title, body.description, body._id );
            return { 'data': {} };
        } catch ( errors ) {
            throw errors;
        }
      }

      /**
     * For read notification
     */
    async readNotification( notificationId, display ) {
        try {
            await this.model.updateMany( { '_id': notificationId, 'readBy.display': { '$ne': display } }, { '$addToSet': { 'readBy': { display, 'readAt': new Date() } } } );
        }catch( e ) {
            throw e;
        }
    }

     /**
     * For get all unread count of notification
     */
      async getAllUnreadCount( display ) {
        try {
            const count = await this.model.countDocuments( { '$and': [ { 'displays': { '$in': display } }, { 'readBy.display': { '$ne': display } } ] } );
            return { 'data': count };
        }catch( e ) {
            throw e;
        }
    }
}

module.exports = { PushNotificationService };