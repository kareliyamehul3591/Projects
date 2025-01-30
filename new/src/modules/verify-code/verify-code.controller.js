'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { VerifyUniqueCodeService } = require( './verify-code.service' );
const { VerifyCode } = require( './verify-code.model' );
const { Display } = require( '../display/display.model' );
const { Playlist } = require( '../playlist/playlist.model' );
const verifyCodeDTO = require( './verify-code.dto' );
const autoBind = require( 'auto-bind' ),
    verifyUniqueCodeService = new VerifyUniqueCodeService(
        new VerifyCode().getInstance(),
        new Playlist().getInstance()
    );

class verifyCodeController extends CalmController {

    constructor( service ) {
        super( service );
        // console.log(service);
        
        this.dto = { ...this.dto, ...verifyCodeDTO };
        autoBind( this );
    }

    async checkUniqueCode( req, res, next ) {
        req.body.uniqueCode = req.body.code;
        
        try { 
    
            const response = await this.service.get(  req, res, next );
            // console.log(response);
            // console.log(this.service);
           if(response.data != undefined)
                await this.service.insert( new this.dto.InsertDTO( req.body ) );

          
           
            return res.sendCalmResponse( new this.dto.GetDTO( response.data ) );
        } catch ( e ) {
            next( e );
        }
    }
}

module.exports = new verifyCodeController( verifyUniqueCodeService );
