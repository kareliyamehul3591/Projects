'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );
const uniqueValidator = require( 'mongoose-unique-validator' );
const { slugify } = require( '../../utils/index' );

class PushNotification {

    initSchema() {
        const schema = new Schema( {
            'displays': [ {
                'type': Schema.Types.ObjectId,
                'required': true,
                'ref': 'display'
            } ],
            'title': {
                'type': String,
                'required': true,
            },
            'description': {
                'type': String,
                'required': false,
            },
            'content': {
                'type': String,
                'required': false,
            },
            'readBy': [ {
                'display': {
                    'type': Schema.Types.ObjectId,
                    'ref': 'display'
                },
                'readAt': Date
            } ],
        }, { 'timestamps': true } );

        schema.pre( 'save', function( next ) {
            const sample = this;

            if ( !sample.isModified( 'title' ) ) {
                return next();
            }
            sample.slug = slugify( sample.title );
            return next();
        } );

        schema.plugin( uniqueValidator );
        try {
            mongoose.model( 'pushNotification', schema );
        } catch ( e ) {

        }
    }

    getInstance() {
        this.initSchema();
        return mongoose.model( 'pushNotification' );
    }
}

module.exports = { PushNotification };