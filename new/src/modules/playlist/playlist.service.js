'use strict';
const { CalmService } = require( '../../../system/core/CalmService' );
class PlaylistService extends CalmService {
    // Setting Global Populate to apply in Get All & Get Single
    populateFields = [ { 'path': 'createdBy' }, { 'path': 'updatedBy' } ];
    constructor( model, displayModel ) {
        super( model );
        this.displayModel = displayModel;
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
            const items = await this.model.find( query ).sort( sortBy ).skip( skip ).limit( limit ).populate( 'stores selectedDisplays logo' );
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
            const item = await this.model.findById( id ).populate( 'logo zone1.media zone1.mimetype zone2.media zone3.media zone4.media' );
            // const item = await this.model.findById( id ).populate( 'logo zone1.media zone1.mimetype zone2.media zone3.feed zone4.feed' );
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
    /**
     * Get Single Item
     * @param { string } deviceId Instance ID
     */
    async playlistByDeviceId( deviceId ) {
        try {
            const display = await this.displayModel.find( { 'displayId': deviceId } );
            const displayId = display.map( x => x._id );
            if( !display && !display.length ) {
                const error = new Error( 'Display not found' );
                error.statusCode = 404;
                throw error;
            }
            const item = await this.model.find( { 'selectedDisplays': { '$in': displayId } } ).populate( 'logo zone1.media zone1.mimetype zone2.media zone3.media zone4.media' );
            // const item = await this.model.find( { 'selectedDisplays': { '$in': displayId } } ).populate( 'logo zone1.media zone1.mimetype zone2.media zone3.feed zone4.feed' );
            if ( !item ) {
                const error = new Error( 'Item not found' );
                error.statusCode = 404;
                throw error;
            }
            return { 'data': JSON.parse( JSON.stringify( item ) ) };
        } catch ( errors ) {
            throw errors;
        }
    }
}
module.exports = { PlaylistService };