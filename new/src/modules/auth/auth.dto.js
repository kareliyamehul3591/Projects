'use strict';
const { GetDTO } = require( '../user/user.dto' );

class LoginRequestDTO {
    constructor( { ...props } ) {
        this.email = props.email ? props.email.toLowerCase() : undefined;
        this.password = props.password;
        Object.freeze( this );
    }
}

class LoginResponseDTO {
    constructor( { ...props } ) {
        this.token = props.token;
        this.user = new GetDTO( props.user );
        Object.freeze( this );
    }
}

class RegisterRequestDTO {
    constructor( { ...props } ) {
        this.name = props.name;
        this.email = props.email ? props.email.toLowerCase() : undefined;
        this.phone = props.phone;
        this.password = props.password;
        this.role = props.role;
        this.parentUser = props.parentUser;
        this.status = props.status;

        // Delete Fields which are not present in data
        Object.keys( this ).forEach( key => {
            if ( this[ key ] === undefined ) {
                delete this[ key ];
            }
        } );
        Object.freeze( this );
    }
}

class RegisterResponseDTO extends GetDTO {
    constructor( { ...props } ) {
        super( props );
    }
}

class JWTSignDTO {
    constructor( { ...props } ) {
        this._id = props._id.toString();
        this.email = props.email;
        this.name = props.name;
        Object.freeze( this );
    }
}

module.exports = { LoginRequestDTO, LoginResponseDTO, RegisterRequestDTO, RegisterResponseDTO, JWTSignDTO };
