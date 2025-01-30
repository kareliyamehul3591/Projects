'use strict';
const FeedController = require( './feed.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );

router.get( '/', FeedController.getAll );
router.get( '/:id', FeedController.get );
router.post( '/', FeedController.insert );
router.put( '/:id', FeedController.update );
router.put( '/update-status/:id', FeedController.updateStatus );


module.exports = router;
