'use strict';

const { CalmController } = require( '../../../system/core/CalmController' );
const { UserService } = require( './user.service' );
const { User } = require( './user.model' );
const userDTO = require( './user.dto' );
const autoBind = require( 'auto-bind' ),
    userService = new UserService(
        new User().getInstance()
    );

class UserController extends CalmController {

    constructor( service ) {
        super( service );
        this.dto = { ...this.dto, ...userDTO };
        autoBind( this );
    }

}

module.exports = new UserController( userService );
