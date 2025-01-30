'use strict';
const { CalmService } = require( '../../../system/core/CalmService' );

class TestService extends CalmService {
    // Setting Global Populate to apply in Get All & Get Single
    populateFields = [ { 'path': 'createdBy' }, { 'path': 'updatedBy' } ];
    constructor( model ) {
        super( model );
    }
}

module.exports = { TestService };
