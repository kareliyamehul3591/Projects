'use strict';
const TemplateController = require( './template.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );
router.get( '/',TemplateController.getAll);
router.get( '/:id',TemplateController.get);


module.exports = router;
