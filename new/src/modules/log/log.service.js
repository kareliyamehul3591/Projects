'use strict';
const { CalmService } = require( '../../../system/core/CalmService' );

class LogService extends CalmService {
    // Setting Global Populate to apply in Get All & Get Single
    constructor( model ) {
        super( model );
    }

}

module.exports = { LogService };
