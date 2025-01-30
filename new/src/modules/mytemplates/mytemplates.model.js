'use strict';
const mongoose = require('mongoose');
const { Schema } = mongoose;
const uniqueValidator = require( 'mongoose-unique-validator' );

class mytemplates {
    initSchema() {
        const schema = new Schema({
            templateId: {
                'type': Schema.Types.ObjectId,
                'required': false,
                'default': null,
                'ref': 'template'
            },
            account:{
                'type': Schema.Types.ObjectId,
                'required': false,
                'default': null,
                'ref': 'account'
            },
            imagePath: { type: String, required: true },
            htmlPath: { type: String, required: true },
            type: { type: String, required: true },
            media: {
                'type': Schema.Types.ObjectId,
                'required': false,
                'default': null,
                'ref': 'media'
            }
        }, { timestamps: true });
        schema.plugin(uniqueValidator);
        try {
            mongoose.model('mytemplates', schema);
        } catch (e) {
            console.log(e);
        }
    }
    getInstance() {
        this.initSchema();
        return mongoose.model('mytemplates');
    }
}
module.exports = { mytemplates };