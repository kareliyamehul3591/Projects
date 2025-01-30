'use strict';
const Country = require( 'country-state-city' ).Country;
const State = require( 'country-state-city' ).State;


class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.storeId = props.storeId;
        this.name = props.name;
        this.email = props.email;
        this.phone = props.phone;
        this.country = props.country;
        this.countryName = Country.getCountryByCode( props.country ).name;
        this.state = props.state;
        this.stateName = State.getStateByCodeAndCountry( props.state, props.country ).name;
        this.city = props.city;
        this.pincode = props.pincode;
        this.address = props.address;
        this.lng = props.location.coordinates && props.location.coordinates.length ? props.location.coordinates[ 0 ] : '';
        this.lat = props.location.coordinates && props.location.coordinates.length ? props.location.coordinates[ 1 ] : '';
        this.logo = props.logo;
        this.status = props.status;
        Object.freeze( this );
    }
}


class InsertDTO {
    constructor( { ...props } ) {
        this.storeId = props.storeId;
        this.name = props.name;
        this.email = props.email;
        this.phone = props.phone;
        this.country = props.country;
        this.state = props.state;
        this.city = props.city;
        this.pincode = props.pincode;
        this.address = props.address;
        this.logo = props.logo;
        this.owner = props.owner;
        this.account = props.account;
        this.location = {
            'type': 'Point',
            'coordinates': [ props.lng, props.lat ]
        };
        this.storeUsers = [ props.owner ];
        Object.freeze( this );
    }
}

class UpdateDTO {
    constructor( { ...props } ) {
        this.name = props.name;
        this.email = props.email;
        this.phone = props.phone;
        this.country = props.country;
        this.state = props.state;
        this.city = props.city;
        this.pincode = props.pincode;
        this.address = props.address;
        this.logo = props.logo;
        this.location = {
            'type': 'Point',
            'coordinates': [ props.lng, props.lat ]
        };

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
