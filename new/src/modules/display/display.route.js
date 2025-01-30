'use strict';
const DisplayController = require( './display.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );

router.get( '/', DisplayController.getAll );
router.get( '/assignable-displays-by-store', DisplayController.assignableDisplaysByStore );
router.get( '/generate-display-id', DisplayController.generateDisplayId );
router.get( '/:id', DisplayController.get );
router.post( '/', DisplayController.insert );
router.put( '/:id', DisplayController.update );
router.put( '/update-status/:id', DisplayController.updateStatus );
router.delete( '/:id', DisplayController.delete );

module.exports = router;
