'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { PushNotificationService } = require( './push-notification.service' );
const { PushNotification } = require( './push-notification.model' );
const { Display } = require( '../display/display.model' );
const { DisplayService } = require( '../display/display.service' );
const pushNotificationDTO = require( './push-notification.dto' );
const autoBind = require( 'auto-bind' ),
    pushNotificationService = new PushNotificationService(
        new PushNotification().getInstance(),
        new Display().getInstance()
    );

    class PushNotificationController extends CalmController {

        constructor( service ) {
            super( service );
            this.dto = { ...this.dto, ...pushNotificationDTO };
            this.displayService = new DisplayService( new Display().getInstance() );
            autoBind( this );
        }

         /**
          * For sending notification
          */
          async sendNotifications( req, res, next ) {
            let response;
            try {
                // if( req.body.isSendToAllStore ) {
                //     const query = {};
                //     const stores = await this.storeService.getAll( { ...query, 'skip': 0, 'limit': 0 } );
                //     req.body.stores = stores.data.map( x => x._id );
                // }
                response = await this.service.insert( new this.dto.InsertDTO( req.body ) );
    
                await this.service.sendNotifications( response.data );
                res.sendCalmResponse( { 'message': 'Notification send successfully' } );
            } catch ( e ) {
                if( response && response.data ) {
                    await this.service.delete( response.data._id );
                }
                next( e );
            }
        }

         /**
          * Get all
          */
          async getAll( req, res, next ) {
            try {
                const user = req.user;
                if( req.display && req.display._id ) {
                    req.query.displays = req.display._id;                 
                }
                if ( req.query.search ) {
                    const searchElem = req.query.search;
                    const searchValue = { '$regex': searchElem, '$options': 'si' };
                    req.query = { ...req.query, '$or': [ { 'name': searchValue } ] };
                };
                delete req.query.search;
                const response = await this.service.getAll( req.query );
                if( req.display && req.display._id ) {
                    const displayCount = await this.service.getAllUnreadCount( req.display._id );
                    res.sendCalmResponse( response.data.map( x => new this.dto.GetDTO( { ...x, 'isRead': x.readBy.map( read => read.display ).includes( ( req.display._id ).toString() ) } ) ), { 'totalCount': response.total, 'meta': { 'unreadCount': displayCount.data } } );
                }else {
                    res.sendCalmResponse( response.data.map( x => new this.dto.GetDTO( x ) ), { 'totalCount': response.total } );
                }
            } catch ( e ) {
                next( e );
            }
        }

        /**
         * For read notification
         */
         async readNotification( req, res, next ) {
            try {
                const { notificationId } = req.params;
                const display = req.display._id;
                await this.service.readNotification( notificationId, display );
                res.sendCalmResponse( null );
            } catch ( e ) {
                next( e );
            }
        }
    }

    module.exports = new PushNotificationController( pushNotificationService );