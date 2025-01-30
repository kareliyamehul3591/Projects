'use strict';
const CategoryController = require( './category.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );

router.get( '/', CategoryController.getAll );
router.get( '/:id', CategoryController.get );
router.post( '/', CategoryController.insert );
router.put( '/:id', CategoryController.update );
router.delete( '/:id', CategoryController.delete );
router.put( '/update-status/:id', CategoryController.updateStatus );


module.exports = router;
