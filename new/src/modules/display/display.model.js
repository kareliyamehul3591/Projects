'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );
const uniqueValidator = require( 'mongoose-unique-validator' );

class Display {

    initSchema() {
        const schema = new Schema( {
            'displayId': {
                'type': String,
                'unique': true,
                'required': true,
            },
            'name': {
                'type': String,
                'required': true,
            },
            'tags': [ {
                'type': String,
                'required': false,
            } ],
            'store': {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'store'
            },
            'account': {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'account'
            },
            'displayMode': {
                'type': String,
                'enum': [ 'landscape', 'portrait' ],
                'default': 'landscape',
                'required': true,
            },
            'timeZone': {
                'type': String,
                'required': true,
            },
            'status': {
                'type': Boolean,
                'default': true
            }
        }, { 'timestamps': true } );

        schema.plugin( uniqueValidator );
        try {
            mongoose.model( 'display', schema );
        } catch ( e ) {

        }

    }

    getInstance() {
        this.initSchema();
        return mongoose.model( 'display' );
    }
}

module.exports = { Display };
