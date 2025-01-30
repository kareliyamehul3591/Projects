'use strict';
const { CalmController } = require('../../../system/core/CalmController');
const { DeviceService } = require('./device.service');
const { VerifyCode } = require( './device.model' );
const verifyCodeDTO = require( './device.dto' );
const { Playlist } = require( '../playlist/playlist.model' );
const LogController = require( '../log/log.controller' );
const autoBind = require('auto-bind');
let deviceService = new DeviceService(new VerifyCode().getInstance(),new Playlist().getInstance());
class DeviceController extends CalmController {
    constructor(service) {
        super(service);        
        this.dto = { ...this.dto, ...verifyCodeDTO };
        autoBind( this );   
    }
    async get( req, res, next ) {       
        try { 
            var logData = {
                deviceToken: req.body.deviceToken,
                action: "isOnlineCheck",
                message: `device is online`
            };
        
            await LogController.insert(logData);
            const response = await this.service.get(  req, res, next );
            return res.sendCalmResponse( new this.dto.GetDTO( response.req.body),new this.dto.PlaylistGetDTO( response.data ) );
        } catch ( e ) {
            next( e );
        }
    }
}
module.exports = new DeviceController( deviceService );