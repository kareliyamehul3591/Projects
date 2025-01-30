'use strict';
const { CalmService } = require('../../../system/core/CalmService');
class DeviceService extends CalmService {
    constructor(model, playlistModel) {
        super(model);
        this.playlistModel = playlistModel;
    }
    async get(req, res, next) {
        try {
            // Find the device by deviceToken
            const item = await this.model.findOne({
                deviceToken: req.body.deviceToken
            });
            const playlist = await this.playlistModel.findOne({uniqueCode:item.uniqueCode} ).populate( 'logo zone1.playlist zone1.mimetype zone2.media zone3.media zone4.media' );
            if (!item && playlist) {
                // If the item is not found, send response with status: false
                return res.status(404).json({
                    status: false,
                    message: 'Item not found'
                });
            }
            // If the item is found, send response with status: true and the item data
            return res.status(200).json({
                status: true,
                playListStatus:false,
                data: item.toJSON(), 
                playlist: playlist.toJSON()
            });
        } catch (errors) {
            next(errors); // Pass any errors to the error handler
        }
    }
}
module.exports = { DeviceService };