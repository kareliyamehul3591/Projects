const { AuthStoreService } = require( './auth-store.service' );
const { AuthStore } = require( './auth-store.model' );
const { Store } = require( '../store/store.model' );
const autoBind = require( 'auto-bind' );
const authStoreDTO = require( './auth-store.dto' );
const storeDTO = require( '../store/store.dto' );
const authStoreService = new AuthStoreService( new AuthStore().getInstance(), new Store().getInstance() );

class AuthStoreController {

    constructor( service ) {
        this.service = service;
        this.dto = authStoreDTO;
        this.storeDTO = storeDTO;
        autoBind( this );
    }

   
}

module.exports = new AuthStoreController( authStoreService );