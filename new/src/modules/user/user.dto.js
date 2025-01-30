'use strict';

class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.name = props.name;
        this.email = props.email;
        this.phone = props.phone;
        this.role = props.role;
        this.status = props.status;
        this.account = props.account;
        Object.freeze( this );
    }
}
class InsertDTO {
    constructor( { ...props } ) {
        this.email = props.email;
        this.password = props.password;
        this.name = props.name;
        this.phone = props.phone;
        this.account = props.account;
        Object.freeze( this );
    }
}

class UpdateDTO {
    constructor( { ...props } ) {
        this.name = props.name;
        this.phone = props.phone;
        // Delete Fields which are not present in data
        Object.keys( this ).forEach( key => {
            if ( this[ key ] === undefined ) {
                delete this[ key ];
            }
        } );
        Object.freeze( this );
    }
}

class GetProfileDTO {
    constructor( { ...props } ) {
        this.email = props.email;
        this.phone = props.phone;
        this.name = props.name;
        Object.freeze( this );
    }
}


module.exports = { GetDTO, InsertDTO, UpdateDTO, GetProfileDTO };
