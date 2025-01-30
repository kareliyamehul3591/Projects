'use strict';
const sequenceController = require( '../../src/modules/sequence/sequence.controller' );
module.exports.generateSequence = async( collectionName ) => {
    try {
        const response = await sequenceController.updateOne( collectionName );
        return response.data.counter;
    }catch( e ) {
        throw e;
    }

};

