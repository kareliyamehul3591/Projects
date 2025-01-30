'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );
const uniqueValidator = require( 'mongoose-unique-validator' );
const { slugify } = require( '../../utils/index' );

class Store {


    initSchema() {
        const schema = new Schema( {
            'storeId': {
                'type': String,
                'unique': true,
                'required': true,
            },
            'name': {
                'type': String,
                'required': true,
            },
            'email': {
                'type': String,
                'required': true,
            },
            'phone': {
                'type': String,
                'required': true,
            },
            'country': {
                'type': String,
                'required': true,
            },
            'state': {
                'type': String,
                'required': true,
            },
            'city': {
                'type': String,
                'required': true,
            },
            'pincode': {
                'type': String,
                'required': true,
            },
            'address': {
                'type': String,
                'required': true,
            },
            'owner': {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'user'
            },
            'account': {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'account'
            },
            'location': {
                'type': {
                    'type': String,
                    'enum': [ 'Point' ],
                    'default': 'Point'
                },
                'coordinates': []
            },
            'storeUsers': [ {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'user'
            } ],
            'logo': {
                'type': Schema.Types.ObjectId,
                'required': false,
                'default': null,
                'ref': 'media'
            },
            'status': {
                'type': Boolean,
                'default': true
            }
        }, { 'timestamps': true } );

        schema.pre( 'save', function( next ) {
            const store = this;

            if ( !store.isModified( 'title' ) ) {
                return next();
            }
            store.slug = slugify( store.title );
            return next();
        } );

        schema.plugin( uniqueValidator );
        try {
            mongoose.model( 'store', schema );
        } catch ( e ) {

        }

    }

    getInstance() {
        this.initSchema();
        return mongoose.model( 'store' );
    }
}

module.exports = { Store };
