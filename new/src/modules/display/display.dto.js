'use strict';

class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.displayId = props.displayId;
        this.name = props.name;
        this.tags = props.tags;
        this.store = props.store;
        this.displayMode = props.displayMode;
        this.timeZone = props.timeZone;
        this.account = props.account;
        this.status = props.status;
        Object.freeze( this );
    }
}


class InsertDTO {
    constructor( { ...props } ) {
        this.displayId = props.displayId;
        this.account = props.account;
        this.name = props.name;
        this.tags = props.tags;
        this.store = props.store;
        this.displayMode = props.displayMode;
        this.timeZone = props.timeZone;
        Object.freeze( this );
    }
}

class UpdateDTO {
    constructor( { ...props } ) {
        this.displayId = props.displayId;
        this.name = props.name;
        this.tags = props.tags;
        this.store = props.store;
        this.displayMode = props.displayMode;
        this.timeZone = props.timeZone;
        
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
