'use strict';
const MediaController = require( './media.controller' );
const express = require( 'express' );
const router = express.Router();
const AuthController = require( '../auth/auth.controller' );

router.use( AuthController.checkLogin );

router.get( '/:id', MediaController.get );
router.get( '/', MediaController.getAll );
// router.post( '/', MediaController.upload.single( 'file' ), MediaController.insert );
router.post( '/', [ MediaController.upload.single( 'file' ), MediaController.insertMediaMiddleware ], MediaController.insert );
router.post( '/template', MediaController.insertTemplateMediaMiddleware, MediaController.insert );
router.delete( '/:id', MediaController.delete );

module.exports = router;