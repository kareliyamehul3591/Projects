'use strict';
const Country = require( 'country-state-city' ).Country;
const State = require( 'country-state-city' ).State;
const City = require( 'country-state-city' ).City;

class GeneralService {
    constructor( ) {
    }

    /**
     *
     * @returns get all countries
     */
    async getAllCountries() {
        try {
            return Country.getAllCountries();
        } catch( e ) {
            throw e;
        }
    }

    /**
     *
     * @param countryCode: string
     * @returns get all state by country code
     */
    async getStateByCountry( countryCode ) {
        try {
            return State.getStatesOfCountry( countryCode );
        } catch( e ) {
            throw e;
        }
    }

    /**
    *
    * @param {string} countryCode
    * @param {string} stateCode
    * @returns get cities by country and state
    */
    async getCityByCountryState( countryCode, stateCode ) {
        try {
            return City.getCitiesOfState( countryCode, stateCode );
        } catch( e ) {
            throw e;
        }
    }


}

module.exports = { GeneralService };
