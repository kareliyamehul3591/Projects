'use strict';
const DashboardController = require( './dashboard.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );

router.get( '/owner', DashboardController.ownerDashboard );


module.exports = router;
