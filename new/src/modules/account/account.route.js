'use strict';
const AccountController = require( './account.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );

router.get( '/', AccountController.getAll );
router.get( '/:id', AccountController.get );
router.post( '/', AccountController.insert );
router.put( '/:id', AccountController.update );
router.delete( '/:id', AccountController.delete );


module.exports = router;
