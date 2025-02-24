'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );
const uniqueValidator = require( 'mongoose-unique-validator' );
const bcrypt = require( 'bcrypt' );
const SALT_WORK_FACTOR = 10;

class User {

    initSchema() {
        const schema = new Schema( {
            'name': {
                'type': String,
                'required': true,
            },
            'email': {
                'type': String,
                'unique': true,
                'required': true,
                'lowercase': true,
                'trim': true
            },
            'phone': {
                'type': String,
                'required': true,
            },
            'account': {
                'type': Schema.Types.ObjectId,
                'default': null,
                'ref': 'account'
            },
            'password': {
                'type': String,
                'required': true,
                'select': false
            },
            'role': {
                'type': String,
                'enum': [ 'store-owner' ],
                'default': 'store-owner',
                'required': true,
            },
            'parentUser': {
                'type': Schema.Types.ObjectId,
                'default': null,
                'ref': 'user'
            },
            'status': {
                'type': Boolean,
                'required': true,
                'default': true
            },
            'otp': {
                'type': String
            },
            'otpExpiry': {
                'type': Date
            }
        }, { 'timestamps': true } );


        // Pre save Hook
        schema.pre( 'save', function( next ) {
            const user = this;
            // only hash the password if it has been modified (or is new)
            if ( this.isModified( 'password' ) || this.isNew ) {
                bcrypt.genSalt( SALT_WORK_FACTOR, ( err, salt ) => {
                    if ( err ) {
                        return next( err );
                    }
                    bcrypt.hash( user.password, salt, ( hashErr, hash ) => {
                        if ( hashErr ) {
                            return next( hashErr );
                        }
                        // override the cleartext password with the hashed one
                        user.password = hash;
                        next();
                    } );
                } );
            } else {
                return next();
            }
        } );

        // Compare Password
        schema.methods.comparePassword = async function( candidatePassword ) {
            return new Promise( ( resolve, reject ) => {
                bcrypt.compare( candidatePassword, this.password, ( err, isMatch ) => {
                    if ( err ) {
                        reject( err );
                    } else {
                        resolve( isMatch );
                    }
                } );
            } );
        };

        schema.statics.findByEmail = function( email ) {
            return this.findOne( { 'email': email } );
        };

        schema.plugin( uniqueValidator );

        try {
            mongoose.model( 'user', schema );
        } catch ( e ) {

        }

    }

    getInstance() {
        this.initSchema();
        return mongoose.model( 'user' );
    }
}

module.exports = { User };
