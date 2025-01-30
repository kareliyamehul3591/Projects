'use strict';
const mongoose = require('mongoose');
const { Schema } = mongoose;
const uniqueValidator = require( 'mongoose-unique-validator' );
class Template {
    initSchema() {
        const schema = new Schema({
            _id: { type: Schema.Types.ObjectId, required: true },
            image: { type: String, required: true }, // Assuming this is a string that holds the image filename/path
            html: { type: String, required: true }, // Assuming this is a string that holds the image filename/path
            imagePath: { type: String, required: true },
            htmlPath: { type: String, required: true },
            datetime: { type: Date, required: true }
        }, { timestamps: true });
        schema.plugin( uniqueValidator );
        try {
            mongoose.model('template', schema);
        } catch (e) {
            console.log(e);
        }
    }
    getInstance() {
        this.initSchema();
        return mongoose.model('template');
    }
}
module.exports = { Template };