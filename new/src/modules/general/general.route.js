'use strict';
const GeneralController = require( './general.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );

router.get( '/countries', GeneralController.getAllCountries );
router.get( '/countries/:countryCode/states', GeneralController.getStateByCountry );
router.get( '/countries/:countryCode/states/:stateCode/cities', GeneralController.getCityByCountryState );
router.get( '/timezones', GeneralController.getTimeZones );

module.exports = router;
