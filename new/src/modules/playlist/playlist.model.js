'use strict';
const mongoose = require( 'mongoose' );
const { Schema } = require( 'mongoose' );
const uniqueValidator = require( 'mongoose-unique-validator' );
class Playlist {
    initSchema() {
        const schema = new Schema( {
            'themeId': {
                'type': Number,
                'required': true,
                'default': 1,
            },
            'uniqueCode': {
                'type': Number,
                'unique': true,
            },
            'displaysize': {
                'type': String,
                'enum': [ 'layout1', 'layout2' , 'layout3', 'layout4', 'layout5' ],
                'default': 'layout1',
                'required': true,
            },
            'playlistName': {
                'type': String,
                'required': true,
            },
            'stores': [
                {
                    'type': Schema.Types.ObjectId,
                    'required': true,
                    'default': null,
                    'ref': 'store',
                },
            ],
            'displayMode': {
                'type': String,
                'enum': [ 'landscape', 'portrait' ],
                'default': 'landscape',
                'required': true,
            },
            'selectedDisplays': [
                {
                    'type': Schema.Types.ObjectId,
                    'required': true,
                    'default': null,
                    'ref': 'display',
                },
            ],
            'staticAddress': {
                'type': String,
            },
            'logo': {
                'type': Schema.Types.ObjectId,
                'required': false,
                'default': null,
                'ref': 'media',
            },
            'scheduleType': {
                'type': String,
                'enum': [ 'fixed', 'infinite' ],
                'default': 'fixed'
            },
            'startDate': {
                'type': Date,
            },
            'endDate': {
                'type': Date,
            },
            'recurring': {
                'weekdays': {
                    'type': [ Number ],
                    'default': [ 0, 1, 2, 3, 4, 5, 6 ],
                },
                'startTime': {
                    'type': String,
                },
                'endTime': {
                    'type': String,
                },
            },
            'zone1': [
                {
                    'media': {
                        'type': Schema.Types.ObjectId,
                        'required': false,
                        'default': null,
                        'ref': 'media',
                    },
                    'duration': {
                        'type': String,
                    },
                },
            ],
            'zone2': [
                {
                    'media': {
                        'type': Schema.Types.ObjectId,
                        'required': false,
                        'default': null,
                        'ref': 'media',
                    },
                    'duration': {
                        'type': String,
                    },
                },
            ],
            'zone3': [
                {
                    'media': {
                        'type': Schema.Types.ObjectId,
                        'required': false,
                        'default': null,
                        'ref': 'media',
                    },
                    'duration': {
                        'type': String,
                    },
                },
                // {
                //     'feed': {
                //         'type': Schema.Types.ObjectId,
                //         'required': false,
                //         'default': null,
                //         'ref': 'feed',
                //     },
                // },
            ],
            'zone4': [
                {
                    'media': {
                        'type': Schema.Types.ObjectId,
                        'required': false,
                        'default': null,
                        'ref': 'media',
                    },
                    'duration': {
                        'type': String,
                    },
                },
                // {
                //     'feed': {
                //         'type': Schema.Types.ObjectId,
                //         'required': false,
                //         'default': null,
                //         'ref': 'feed',
                //     },
                // },
            ],
            'account': {
                'type': Schema.Types.ObjectId,
                'required': true,
                'default': null,
                'ref': 'account',
            },
            'displayStatus':{
                'type': Boolean,
                'default': true,
            },
            'status': {
                'type': Boolean,
                'default': true,
            },
        }, { 'timestamps': true } );
        schema.plugin( uniqueValidator );
        try {
            mongoose.model( 'playlist', schema );
        } catch ( e ) {

        }
    }
    getInstance() {
        this.initSchema();
        return mongoose.model( 'playlist' );
    }
}
module.exports = { Playlist };