'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );
const uniqueValidator = require( 'mongoose-unique-validator' );
const { slugify } = require( '../../utils/index' );

class Test {

    initSchema() {
        const schema = new Schema( {
            'title': {
                'type': String,
                'required': true,
            },
            'slug': String,
            'subtitle': {
                'type': String,
                'required': false,
            },
            'description': {
                'type': String,
                'required': false,
            },
            'content': {
                'type': String,
                'required': true,
            }
        }, { 'timestamps': true } );

        schema.pre( 'save', function( next ) {
            const test = this;

            if ( !test.isModified( 'title' ) ) {
                return next();
            }
            test.slug = slugify( test.title );
            return next();
        } );

        schema.plugin( uniqueValidator );
        try {
            mongoose.model( 'test', schema );
        } catch ( e ) {

        }

    }

    getInstance() {
        this.initSchema();
        return mongoose.model( 'test' );
    }
}

module.exports = { Test };
