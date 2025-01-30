'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { TemplateService } = require( './template.service' );
const { Template } = require( './template.model' );
const templateDTO = require( './template.dto' );
const autoBind = require( 'auto-bind' );
// Remove the const declaration here
const templateService = new TemplateService(
    new Template().getInstance(),
);
class TemplateController extends CalmController {
    constructor( service ) {
        super( service );
        this.dto = { ...this.dto, ...templateDTO };
        autoBind( this );
    }
    async getAll( req, res, next ) {        
        try {
            if ( req.query.search ) {
                const searchElem = req.query.search;
                const searchValue = { '$regex': searchElem, '$options': 'si' };
                req.query = { ...req.query, '$or': [ { 'templateName': searchValue } ] };
            }
            delete req.query.search;
            await super.getAll( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }
    async get( req, res, next){
        try {            
            const response = await super.get( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }
}
module.exports = new TemplateController( templateService );