'use strict';
const { CalmService } = require( '../../../system/core/CalmService' );

class VerifyUniqueCodeService extends CalmService {
    // Setting Global Populate to apply in Get All & Get Single
    populateFields = [ { 'path': 'createdBy' }, { 'path': 'updatedBy' } ];
    constructor( model, playlistModel ) {
        super( model );
        this.playlistModel = playlistModel;
        // console.log(playlistModel);
        
    }

    async get( req, res, next ) {
        try {
            const item = await this.playlistModel.findOne({uniqueCode:req.body.uniqueCode} ).populate( 'logo zone1.playlist zone1.mimetype zone2.media zone3.media zone4.media' );
            // const item = await this.model.findOne({uniqueCode:uniqueCode} ).populate( 'logo zone1.media zone1.mimetype zone2.media zone3.feed zone4.feed' );

            if ( !item ) {
                const error = new Error( 'Item not found' );

                error.statusCode = 404;
                throw error;
            }

            return { 'data': item.toJSON() };
        } catch ( errors ) {
            throw errors;
        }
    }


    async insert1( data ) {
        try {
    
            const response = await this.model.insertOne( data );
            return response;
        } catch ( errors ) {
            throw errors;
        }
    }
}

module.exports = { VerifyUniqueCodeService };
