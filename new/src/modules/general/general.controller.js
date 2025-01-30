'use strict';
const { GeneralService } = require( './general.service' );
const autoBind = require( 'auto-bind' ),
    generalService = new GeneralService();
const moment = require( 'moment-timezone' );

class GeneralController {

    constructor( service ) {
        this.service = service;
        // this.dto = { ...this.dto, ...generalDTO };
        autoBind( this );
    }

    async getAllCountries( req, res, next ) {
        try {
            const countries = await this.service.getAllCountries();
            res.sendCalmResponse( countries );
        } catch ( e ) {
            next( e );
        }
    }

    async getStateByCountry( req, res, next ) {
        try {
            const { countryCode } = req.params;
            const states = await this.service.getStateByCountry( countryCode );
            res.sendCalmResponse( states );
        } catch ( e ) {
            next( e );
        }
    }

    async getCityByCountryState( req, res, next ) {
        try {
            const { countryCode, stateCode } = req.params;
            const cities = await this.service.getCityByCountryState( countryCode, stateCode );
            res.sendCalmResponse( cities );
        } catch ( e ) {
            next( e );
        }
    }

    async getTimeZones( req, res, next ) {
        try {
            let timeZones = moment.tz.names();
            if( req.query.country ) {
                timeZones = moment.tz.zonesForCountry( req.query.country );
            }
            res.sendCalmResponse( timeZones );
        } catch ( e ) {
            next( e );
        }
       
    }

}

module.exports = new GeneralController( generalService );
