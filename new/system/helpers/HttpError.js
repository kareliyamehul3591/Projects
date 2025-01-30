'use strict';
const humanizeString = require( 'humanize-string' );
class HttpError {
    error = true;
    success = false;
    responseTimestamp = new Date();

    /**
     * HTTP Error Class
     * @author Sunil Kumar Samanta
     * @param error
     */
    constructor( error ) {
        if ( typeof ( error ) === 'string' ) {
            this.statusCode = 500;
            this.message = error;
            this.name = 'InternalServerError';
        } else {
            if ( error.name === 'ValidationError' && error.errors ) {
                error.statusCode = 422;
                error.errors = this.formatMongooseError( error.errors );
                error.message = humanizeString( error.message.split( ':' )[ 0 ].trim() );
            }

            let errorName;

            switch ( error.statusCode ) {
                case 422:
                    errorName = 'ValidationError';
                    break;
                case 401:
                    errorName = 'UnauthorizedError';
                    break;
                case 403:
                    errorName = 'ForbiddenError';
                    break;
                case 404:
                    errorName = 'NotFoundError';
                    break;
                default:
                    errorName = 'InternalServerError';
            }
            this.statusCode = error.statusCode ? error.statusCode : 500;
            this.message = error.message || 'Internal Server Error!';
            this.errors = error.errors;
            this.name = errorName;
        }
    }

    formatMongooseError( errorsObj ) {
        const errors = {};
        Object.keys( errorsObj ).forEach( key => {
            switch( errorsObj[ key ].kind ) {
                case 'required': errors[ key ] = `${humanizeString( errorsObj[ key ].path )} is required`;
                    break;

                case 'unique': errors[ key ] = `${humanizeString( errorsObj[ key ].path )} already exists`;
                    break;

                default:
                    if( errorsObj[ key ].name === 'CastError' ) {
                        errors[ key ] = `${humanizeString( errorsObj[ key ].path )} is of invalid type`;
                    } else {
                        errors[ key ] = errorsObj[ key ].message;
                    }
            }
        } );
        return errors;
    };
}


module.exports = { HttpError };
