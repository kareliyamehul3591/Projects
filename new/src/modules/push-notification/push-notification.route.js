'use strict';
const PushNotificationController = require( './push-notification.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );

router.get( '/', PushNotificationController.getAll );
router.get( '/:id', PushNotificationController.get );
router.post( '/', PushNotificationController.sendNotifications );
router.post( '/:notificationId/read-notification', PushNotificationController.readNotification );

router.put( '/:id', PushNotificationController.update );
router.delete( '/:id', PushNotificationController.delete );


module.exports = router;