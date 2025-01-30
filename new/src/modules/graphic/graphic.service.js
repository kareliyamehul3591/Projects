'use strict';
const { CalmService } = require( '../../../system/core/CalmService' );

class GraphicService extends CalmService {
    // Setting Global Populate to apply in Get All & Get Single
    populateFields = [ { 'path': 'createdBy' }, { 'path': 'updatedBy' } ];
    constructor( model ) {
        super( model );
    }

    /**
     * Get All Items
     * @param { Object } query Query Parameters
     */
    async getAll( query ) {
        let { skip, limit, sortBy } = query;

        skip = skip ? Number( skip ) : 0;
        limit = limit ? Number( limit ) : 10;
        sortBy = sortBy ? sortBy : { 'createdAt': -1 };

        delete query.skip;
        delete query.limit;
        delete query.sortBy;

        try {
            const items = await this.model.find( query ).sort( sortBy ).skip( skip ).limit( limit ).populate( 'media' );
               
            const total = await this.model.countDocuments( query );

            return { 'data': JSON.parse( JSON.stringify( items ) ), total };
        } catch ( errors ) {
            throw errors;
        }
    }

    /**
     * Get Single Item
     * @param { string } id Instance ID
     */
    async get( id ) {
        try {
            const item = await this.model.findById( id ).populate( 'media' );

            if ( !item ) {
                const error = new Error( 'Item not found' );

                error.statusCode = 404;
                throw error;
            }

            return { 'data': item.toJSON() };
        } catch ( errors ) {
            throw errors;
        }
    }
}

module.exports = { GraphicService };
