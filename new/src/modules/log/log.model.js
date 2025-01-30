'use strict';
const mongoose = require('mongoose');
const { Schema } = mongoose;

class log {
    initSchema() {
        const schema = new Schema({
            action: {
                type: String,
                required: true,
            },
            playListId: {
                type: String,
                required: false,
            },
            deviceToken: {
                type: String,
                required: false,
            },
            message:{
                type: String,
                required: false,
            },
        }, { timestamps: true });

        try {
            mongoose.model('log', schema);
        } catch (e) {
            // Already exists error handling
        }
    }

    getInstance() {
        this.initSchema();
        return mongoose.model('log');
    }
}

module.exports = { log };
