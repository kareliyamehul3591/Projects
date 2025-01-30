'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );
const uniqueValidator = require( 'mongoose-unique-validator' );

class Category {

    initSchema() {
        const schema = new Schema( {
            'name': {
                'type': String,
                'required': true,
            },
            'account': {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'account'
            },
            'logo': {
                'type': Schema.Types.ObjectId,
                'required': false,
                'default': null,
                'ref': 'media',
            },
            'status': {
                'type': Boolean,
                'default': true
            }
        }, { 'timestamps': true } );

        schema.plugin( uniqueValidator );
        try {
            mongoose.model( 'category', schema );
        } catch ( e ) {

        }

    }

    getInstance() {
        this.initSchema();
        return mongoose.model( 'category' );
    }
}

module.exports = { Category };
