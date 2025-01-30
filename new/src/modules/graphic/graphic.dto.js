'use strict';

class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.name = props.name;
        this.type = props.type;
        this.media = props.media;
        this.folder = props.folder;
        this.status = props.status;
        
        Object.freeze( this );
    }
}


class InsertDTO {
    constructor( { ...props } ) {
        this.name = props.name;
        this.type = props.type;
        this.media = props.media;
        this.folder = props.folder;
        this.account = props.account;
        Object.freeze( this );
    }
}

class UpdateDTO {
    constructor( { ...props } ) {
        this.name = props.name;
        this.type = props.type;
        this.media = props.media;
        this.folder = props.folder;

        // Delete Fields which are not present in data
        Object.keys( this ).forEach( key => {
            if ( this[ key ] === undefined ) {
                delete this[ key ];
            }
        } );
        Object.freeze( this );
    }

}

class UpdateStatusDTO {
    constructor( { ...props } ) {
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

class UpdateStatusResponseDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.status = props.status;
        Object.freeze( this );
    }
}

module.exports = { GetDTO, InsertDTO, UpdateDTO, UpdateStatusDTO, UpdateStatusResponseDTO };
