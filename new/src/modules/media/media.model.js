'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );

class Media {

    initSchema() {
        const schema = new Schema( {
            'originalname': {
                'type': String,
                'required': false,
            },
            'encoding': {
                'type': String,
                'required': false,
            },
            'mimetype': {
                'type': String,
                'required': false,
            },
            'filename': {
                'type': String,
                'required': false,
            },
            'imagePath':{
                'type': String,
                'required': false,
            },
            'path': {
                'type': String,
                'required': false,
            },
            'html': {
                'type': String,
                'required': false,
            },
            'size': {
                'type': Number,
                'required': false,
            },
            'account': {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'account'
            }
        }, { 'timestamps': true } );
        try {
            mongoose.model( 'media', schema );
        } catch ( e ) {

        }
    }

    getInstance() {
        this.initSchema();
        return mongoose.model( 'media' );
    }
}

module.exports = { Media };
