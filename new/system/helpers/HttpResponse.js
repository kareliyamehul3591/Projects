'use strict';
const defaultExcludedItemsFromResponse = [ '__v', 'password' ];

class HttpResponse {

    /**
     * @author Sunil Kumar Samanta
     */
    error = false;
    success = true;
    responseTimestamp = new Date();

    /**
     * Create Http Response
     * @param { Object | Array } [data] Response data.
     * @param { Object } [options] Options.
     * @param { Number } [options.totalCount] Total Count.
     * @param { Number } [options.statusCode] Status Code. Default 200.
     * @param { Boolean } [options.deleted] Deleted Flag.
     * @param { Boolean } [options.updated] Updated Flag.
     */
    constructor( data = undefined, options = { 'totalCount': 0, 'statusCode': 200, 'deleted': false } ) {
        this.statusCode = options.statusCode || 200;
        let filteredData = data;
        if ( filteredData && typeof ( filteredData ) === 'object' ) {
            filteredData = this.filterData( JSON.parse( JSON.stringify( filteredData ) ) );
        }
        if ( filteredData && Array.isArray( filteredData ) ) {
            this.data = [ ...filteredData ];
            this.totalCount = options.totalCount || undefined;
        } else if ( filteredData && typeof ( filteredData ) === 'object' ) {
            this.data = { ...filteredData };
        } else {
            this.data = data;
            if ( !data ) {
                delete this.data;
            }
        }
        if ( options.deleted ) {
            this.deleted = options.deleted;
        }
        if ( options.updated ) {
            this.updated = options.updated;
        }
    }

    filterData( data ) {
        if ( Array.isArray( data ) ) {
            data.map( ( x, index ) => {
                Object.keys( x ).forEach( ( key ) => {
                    if ( defaultExcludedItemsFromResponse.includes( key ) ) {
                        delete data[ index ][ key ];
                    }
                } );
            } );
        } else if ( typeof ( data ) === 'object' ) {
            Object.keys( data ).forEach( ( key ) => {
                if ( defaultExcludedItemsFromResponse.includes( key ) ) {
                    delete data[ key ];
                }
            } );
        }
        return data;
    }
}

module.exports = { HttpResponse };
