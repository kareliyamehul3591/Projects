'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { TestService } = require( './test.service' );
const { Test } = require( './test.model' );
const testDTO = require( './test.dto' );
const autoBind = require( 'auto-bind' ),
    testService = new TestService(
        new Test().getInstance()
    );

class TestController extends CalmController {

    constructor( service ) {
        super( service );
        this.dto = { ...this.dto, ...testDTO };
        autoBind( this );
    }

}

module.exports = new TestController( testService );
