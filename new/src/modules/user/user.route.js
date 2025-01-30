'use strict';
const UserController = require( './user.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );

router.get( '/', UserController.getAll );
router.delete( '/:id', UserController.delete );


module.exports = router;
