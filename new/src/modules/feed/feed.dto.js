'use strict';

class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.title = props.title;
        this.category = props.category;
        this.rssFeedUrl = props.rssFeedUrl;
        this.rssReadMoreUrl = props.rssReadMoreUrl;
        this.status = props.status;
        this.logo = props.logo;
        Object.freeze( this );
    }
}


class InsertDTO {
    constructor( { ...props } ) {
        this.title = props.title;
        this.category = props.category;
        this.rssFeedUrl = props.rssFeedUrl;
        this.rssReadMoreUrl = props.rssReadMoreUrl;
        this.account = props.account;
        this.logo = props.logo;
        this.status = props.status;
        Object.freeze( this );
    }
}

class UpdateDTO {
    constructor( { ...props } ) {
        this.title = props.title;
        this.category = props.category;
        this.rssFeedUrl = props.rssFeedUrl;
        this.rssReadMoreUrl = props.rssReadMoreUrl;
        this.logo = props.logo;
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
