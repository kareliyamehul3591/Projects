'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { CategoryService } = require( './category.service' );
const { Category } = require( './category.model' );
const categoryDTO = require( './category.dto' );
const autoBind = require( 'auto-bind' ),
    categoryService = new CategoryService(
        new Category().getInstance()
    );

class CategoryController extends CalmController {

    constructor( service ) {
        super( service );
        this.dto = { ...this.dto, ...categoryDTO };
        autoBind( this );
    }

    async getAll( req, res, next ) {
        try {
            req.query.account = req.user.account;
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

module.exports = new CategoryController( categoryService );
