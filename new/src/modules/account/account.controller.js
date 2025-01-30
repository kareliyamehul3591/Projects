'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { AccountService } = require( './account.service' );
const { Account } = require( './account.model' );
const accountDTO = require( './account.dto' );
const autoBind = require( 'auto-bind' ),
    accountService = new AccountService(
        new Account().getInstance()
    );

class AccountController extends CalmController {

    constructor( service ) {
        super( service );
        this.dto = { ...this.dto, ...accountDTO };
        autoBind( this );
    }

}

module.exports = new AccountController( accountService );
