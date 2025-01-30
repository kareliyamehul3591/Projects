'use strict';
const { StoreService } = require( '../store/store.service' );
const autoBind = require( 'auto-bind' );

class AuthStoreService {
    constructor( model, storeModel ) {
        this.model = model;
        this.storeService = new StoreService( storeModel );
        this.parseObj = data => JSON.parse( JSON.stringify( data ) );
        this.storeModel = storeModel;
        autoBind( this );
    }

}

module.exports = { AuthStoreService };