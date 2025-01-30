'use strict';
const TestController = require( './test.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

// router.use(AuthController.checkLogin);

router.get( '/', AuthController.checkLogin, TestController.getAll );
router.get( '/:id', TestController.get );
router.post( '/', TestController.insert );
router.put( '/:id', TestController.update );
router.delete( '/:id', TestController.delete );


module.exports = router;
