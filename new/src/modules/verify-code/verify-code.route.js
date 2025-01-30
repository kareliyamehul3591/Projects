'use strict';
const verifyCodeController = require( './verify-code.controller' );
// const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

// router.use( AuthController.checkLogin );

router.post( '/',verifyCodeController.checkUniqueCode);

module.exports = router;
