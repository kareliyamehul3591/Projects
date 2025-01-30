'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );
const uniqueValidator = require( 'mongoose-unique-validator' );

class Feed {

    initSchema() {
        const schema = new Schema( {
            'title': {
                'type': String,
                'required': true,
            },
            'category': {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'category'
            },
            'logo': {
                'type': Schema.Types.ObjectId,
                'required': false,
                'default': null,
                'ref': 'media',
            },
            'rssFeedUrl': {
                'type': String,
                'required': true,
            },
            'rssReadMoreUrl': {
                'type': String,
                'required': false,
            },
            'account': {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'account'
            },
            'status': {
                'type': Boolean,
                'default': true
            }
        }, { 'timestamps': true } );

        schema.plugin( uniqueValidator );
        try {
            mongoose.model( 'feed', schema );
        } catch ( e ) {

        }

    }

    getInstance() {
        this.initSchema();
        return mongoose.model( 'feed' );
    }
}

module.exports = { Feed };
