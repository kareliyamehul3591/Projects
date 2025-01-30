'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { PlaylistService } = require( './playlist.service' );
const { Playlist } = require( './playlist.model' );
const { Display } = require( '../display/display.model' );
const playlistDTO = require( './playlist.dto' );
const LogController = require( '../log/log.controller' );
const autoBind = require( 'auto-bind' ),
    playlistService = new PlaylistService(
        new Playlist().getInstance(),
        new Display().getInstance()
    );
class PlaylistController extends CalmController {
    constructor( service ) {
        super( service );
        this.dto = { ...this.dto, ...playlistDTO };
        autoBind( this );
    }
    async get( req, res, next ) {
        const { id } = req.params;
        
        try {
            const response = await this.service.get( id );
            res.sendCalmResponse( new this.dto.GetDTO( response.data ) );
        } catch ( e ) {
            next( e );
        }
    }
    async playlistByDeviceId( req, res, next ) {
        const { deviceId } = req.params;
        try {
            const response = await this.service.playlistByDeviceId( deviceId );
            res.sendCalmResponse( response.data );
        } catch ( e ) {
            next( e );
        }
    }
    async getAll( req, res, next ) {
        try { 
            if(req.user.role != "admin")
             req.query.account = req.user.account;
            if ( req.query.search ) {
                const searchElem = req.query.search;
                const searchValue = { '$regex': searchElem, '$options': 'si' };
                req.query = { ...req.query, '$or': [ { 'playlistName': searchValue } ] };
            }
            delete req.query.search;
            await super.getAll( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }
    async getPlaylistByuniqueCode( req, res, next ) {
        try {
            // req.query.account = req.user.account;
            if ( req.query.search ) {
                const searchElem = req.query.search;
                const searchValue = { '$regex': searchElem, '$options': 'si' };
                req.query = { ...req.query, '$or': [ { 'playlistName': searchValue } ] };
            }
            delete req.query.search;
            await super.getAll( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }
    async insert( req, res, next ) {
        try {
            req.body.uniqueCode = Math.floor(100000 + Math.random() * 900000);
            req.body.account = req.user.account;
            await super.insert( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }
    async update(req, res, next){
        const { id } = req.params;
        try {
            var logData = {
                playListId: id,
                action: "playListUpdate",
                message: `playList update by ${req.user.role}`
            };
        
            await LogController.insert(logData);
            await super.update( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }
    async updateStatus( req, res, next ) {
        const { id } = req.params;
        try {
            var logData = {
                playListId: id,
                action: "updateStatus",
                message: `playList update by ${req.user.role}`
            };
        
            await LogController.insert(logData);
            const response = await this.service.update(
                id,
                new this.dto.UpdateStatusDTO( req.body )
            );
            
            res.sendCalmResponse( new this.dto.UpdateStatusResponseDTO( response.data ) );
        } catch ( e ) {
            next( e );
        }
    }
}
module.exports = new PlaylistController( playlistService );


