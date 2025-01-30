'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { DisplayService } = require( './display.service' );
const { Display } = require( './display.model' );
const displayDTO = require( './display.dto' );
const StoreController = require( '../store/store.controller' );
const autoBind = require( 'auto-bind' ),
    displayService = new DisplayService(
        new Display().getInstance()
    );

class DisplayController extends CalmController {

    constructor( service ) {
        super( service );
        this.dto = { ...this.dto, ...displayDTO };
        autoBind( this );
    }

    async generateDisplayId( req, res, next ) {
        try {
            const response = await this.service.generateDisplayId();
            res.sendCalmResponse( response.data );
        } catch ( e ) {
            next( e );
        }
    }

    async getAll( req, res, next ) {
        try {
            // get stores for current user
            var where = {};
                        
            if(req.user.role != "admin")
            {
                where =  {
                    'storeUsers': req.user._id,
                };
                req.query.account = req.user.account;
            }
            
            
            const { 'data': stores } = await StoreController.service.getAll( where );
            req.query.store = { '$in': stores.map( ( x ) => x._id ) };
            
            // console.log('req.user.account: '+req.user.account);

            
            if ( req.query.search ) {
                const searchElem = req.query.search;
                const searchValue = { '$regex': searchElem, '$options': 'si' };
                req.query = {
                    ...req.query,
                    '$or': [ { 'name': searchValue }, { 'displayId': searchValue } ],
                };
            }
            delete req.query.search;
            await super.getAll( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }

    async assignableDisplaysByStore( req, res, next ) {
        try {
            if(req.user.role != "admin")
            req.query.account = req.user.account;
            const response = await this.service.getAll( req.query );
            res.sendCalmResponse( response.data );
        } catch ( e ) {
            next( e );
        }
    }

    async insert( req, res, next ) {
        try {
            const { 'data': stores } = await StoreController.service.getAll( {
                '_id': req.body.store,
                'storeUsers': req.user._id,
            } );
            if ( !stores.length ) {
                const error = new Error( 'Permission Denied' );

                error.statusCode = 403;
                throw error;
            }
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
            const response = await this.service.update(
                id,
                new this.dto.UpdateStatusDTO( req.body )
            );
            res.sendCalmResponse( new this.dto.UpdateStatusResponseDTO( response.data ) );
        } catch ( e ) {
            next( e );
        }
    }

}

module.exports = new DisplayController( displayService );
