'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { StoreService } = require( './store.service' );
const { Store } = require( './store.model' );
const storeDTO = require( './store.dto' );
const autoBind = require( 'auto-bind' ),
    storeService = new StoreService(
        new Store().getInstance()
    );

class StoreController extends CalmController {

    constructor( service ) {
        super( service );
        this.dto = { ...this.dto, ...storeDTO };
        autoBind( this );
    }

    async generateStoreId( req, res, next ) {
        try {
            const response = await this.service.generateStoreId( );
            res.sendCalmResponse( response.data );
        } catch ( e ) {
            next( e );
        }
    }

    async getAll( req, res, next ) {
        try {
           
            
            if(req.user.role != "admin")
            {
                req.query.storeUsers = req.user._id;
                req.query.account = req.user.account;
            }
            if ( req.query.search ) {
                const searchElem = req.query.search;
                const searchValue = { '$regex': searchElem, '$options': 'si' };
                req.query = { ...req.query, '$or': [ { 'name': searchValue } ] };
            }
           
            
            delete req.query.search;
            await super.getAll( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }

    async insert( req, res, next ) {
        try {
            req.body.owner = req.user._id;
            req.body.account = req.user.account;
            await super.insert( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }

    async updateStatus( req, res, next ) {
        const { id } = req.params;
        try {
            const response = await this.service.update( id, new this.dto.UpdateStatusDTO( req.body ) );
            res.sendCalmResponse( new this.dto.UpdateStatusResponseDTO( response.data ) );
        } catch ( e ) {
            next( e );
        }
    }

}

module.exports = new StoreController( storeService );
