'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { log } = require( './log.model' );
const { LogService } = require('./log.service');
const autoBind = require( 'auto-bind' ),
    logService = new LogService(
        new log().getInstance()
    );
class LogController extends CalmController {
    constructor( service ) {
        super( service );
        this.dto = { ...this.dto };
        autoBind( this );
    }

    async insert( data) {
        try {
            console.log(data);
            
            const item = await this.service.model.create( new this.dto.InsertDTO( data ) );
        } catch ( e ) {
            console.log(e);
            
        }
    }
}
module.exports = new LogController( logService );