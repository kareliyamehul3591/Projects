'use strict';
const mongoose = require('mongoose');
const { Schema } = mongoose;
//const uniqueValidator = require('mongoose-unique-validator');
class VerifyCode {
    initSchema() {
        const schema = new Schema({
            'uniqueCode': {
                'type': Number,
                'unique': false,
                'required': true
            },
            'ipAddress': {
                'type': String,
                'required': true
            },
            'deviceToken': {
                'type': String,
                'required': true
            },
            'display': {
                'width': {
                    'type': Number,
                    'required': true
                },
                'height': {
                    'type': Number,
                    'required': true
                },
                'scale': {
                    'type': Number,
                    'required': true
                },
                'fontScale': {
                    'type': Number,
                    'required': true
                }
            },
            'deviceBrand': {
                'type': String,
                'required': true
            },
            'ipv4Address': {
                'type': String,
                'required': true
            },
        }, { 'timestamps': true });
     //   schema.plugin(uniqueValidator);
        try {
            mongoose.model('VerifyCode', schema);
        } catch (e) {
            console.log(e);
        }
    }
    getInstance() {
        this.initSchema();
        return mongoose.model('VerifyCode');
    }
}
module.exports = { VerifyCode };