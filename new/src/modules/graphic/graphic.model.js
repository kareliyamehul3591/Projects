'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );
const uniqueValidator = require( 'mongoose-unique-validator' );

class Graphic {

    initSchema() {
        const schema = new Schema( {
            'name': {
                'type': String,
                'required': true,
            },
            'type': {
                'type': String,
                'enum': [ 'image', 'video', 'pptx' ],
                'default': 'image',
                'required': true,
            },
            'media': {
                'type': Schema.Types.ObjectId,
                'required': false,
                'default': null,
                'ref': 'media'
            },
            'folder': {
                'type': String,
                'required': false,
            },
            'account': {
                'type': Schema.Types.ObjectId,
                'required': false,
                'default': null,
                'ref': 'account'
            },
            'status': {
                'type': Boolean,
                'default': true
            }
        }, { 'timestamps': true } );
        
        schema.pre( 'save', function( next ) {
            const graphic = this;

            if ( !graphic.isModified( 'title' ) ) {
                return next();
            }
            graphic.slug = slugify( graphic.title );
            return next();
        } );

        schema.plugin( uniqueValidator );
        try {
            mongoose.model( 'graphic', schema );
        } catch ( e ) {

        }

    }

    getInstance() {
        this.initSchema();
        return mongoose.model( 'graphic' );
    }
}

module.exports = { Graphic };
