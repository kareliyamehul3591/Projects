'use strict';
const { CalmService } = require( '../../../system/core/CalmService' );

class DisplayService extends CalmService {
    // Setting Global Populate to apply in Get All & Get Single
    populateFields = [ { 'path': 'createdBy' }, { 'path': 'updatedBy' } ];
    constructor( model ) {
        super( model );
    }

    async generateDisplayId() {
        const displayId = `${Math.floor( Math.random( 100000, 999999 ) * ( 999999 - 100000 ) + 100000 )}`;
        try {
            const foundRfq = await this.model.countDocuments( { displayId } );
            if( !foundRfq ) {
                return { 'data': { displayId } };
            }
            return await this.generateDisplayId();
        } catch ( e ) {
            throw e;
        }
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
            const items = await this.model.find( query ).sort( sortBy ).skip( skip ).limit( limit ).populate( 'store' );

            const total = await this.model.countDocuments( query );

            return { 'data': JSON.parse( JSON.stringify( items ) ), total };
        } catch ( errors ) {
            throw errors;
        }
    }
}

module.exports = { DisplayService };
