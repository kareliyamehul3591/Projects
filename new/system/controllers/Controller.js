'use strict';
const autoBind = require( 'auto-bind' );
const { HttpResponse } = require( '../helpers/HttpResponse' );
const defaultDTO = require( '../dto/Dto' );
class Controller {

    /**
     * Base Controller Layer
     * @author Sunil Kumar Samanta
     * @param { any } service
     */
    constructor( service ) {
        this.service = service;
        this.dto = defaultDTO;
        autoBind( this );
    }

    async getAll( req, res, next ) {
        try {
            const response = await this.service.getAll( req.query );
            const httpResponse = new HttpResponse( response.data.map( x => new this.dto.GetDTO( x ) ), { 'totalCount': response.total } );
            return res.status( httpResponse.statusCode ).json( httpResponse );
        } catch ( e ) {
            next( e );
        }
    }

    async get( req, res, next ) {
        const { id } = req.params;

        try {
            const response = await this.service.get( id );

            const httpResponse = new HttpResponse( new this.dto.GetDTO( response.data ) );
            return res.status( httpResponse.statusCode ).json( httpResponse );
        } catch ( e ) {
            next( e );
        }
    }

    async insert( req, res, next ) {
        try {
            const response = await this.service.insert( new this.dto.InsertDTO( req.body ) );

            const httpResponse = new HttpResponse( new this.dto.GetDTO( response.data ) );
            return res.status( httpResponse.statusCode ).json( httpResponse );
        } catch ( e ) {
            next( e );
        }
    }

    async update( req, res, next ) {
        const { id } = req.params;

        try {
            const response = await this.service.update( id, new this.dto.UpdateDTO( req.body ) );

            const httpResponse = new HttpResponse( new this.dto.GetDTO( response.data ) );
            return res.status( httpResponse.statusCode ).json( httpResponse );
        } catch ( e ) {
            next( e );
        }
    }

    async delete( req, res, next ) {
        const { id } = req.params;

        try {
            const response = await this.service.delete( id );

            const httpResponse = new HttpResponse( new this.dto.GetDTO( response.data ), { 'deleted': response.deleted } );
            return res.status( httpResponse.statusCode ).json( httpResponse );
        } catch ( e ) {
            next( e );
        }
    }

}

module.exports = { Controller };
