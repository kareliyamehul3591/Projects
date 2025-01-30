'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { MediaService } = require( './media.service' );
const { Media } = require( './media.model' );
const autoBind = require( 'auto-bind' );
const multer = require( 'multer' );
const path = require('path');
const fs = require("fs");

// const { S3Upload } = require( '../../plugins' );
const { CalmError } = require( '../../../system/core/CalmError' );

const mediaService = new MediaService(
    new Media().getInstance()
);

async function getMonthPath(date) {
    // console.log("date: " + date);
    return date.getFullYear() + "/" + (date.getMonth() + 1) + "/";
}

async function getDirPath(dirPath) {
    try {
        if (!fs.existsSync(dirPath)) {
            // Ensure the directory is created asynchronously
            await fs.promises.mkdir(dirPath, { recursive: true });
        }
        return dirPath;
    } catch (error) {
        console.log(error.message);
        throw error;  // Re-throw the error to handle it elsewhere if necessary
    }
}

class MediaController extends CalmController {
    // file upload using multer
    // storage = multer.memoryStorage();

    storage = multer.diskStorage({
        /* 'destination': function( req, file, callback ) {
            // callback( null, `uploads/${new Date().getFullYear()}/${( `0${ new Date().getMonth() + 1}` ).slice( -2 )}/`);
            callback(null, getDirPath('uploads/' + getMonthPath(new Date())));
        }, */
        'destination': async function(req, file, callback) {
            try {
                const monthPath = await getMonthPath(new Date());
                const dirPath = await getDirPath(path.join('uploads', monthPath));
                callback(null, dirPath);
            } catch (error) {
                callback(error, null);  // Pass error to callback if directory creation fails
            }
        },
        'filename': (req, file, callback) => {
            callback(null, `${Math.floor(Math.random() * 9999)}-${new Date().getTime()}` + path.extname(file.originalname));
        }
    });

    upload = multer({
        'storage': this.storage,
        'limits': {
            'fileSize': 1024 * 1024 * 25
        }
    });

    constructor( service ) {
        super( service );
        // this.S3Upload = new S3Upload();
        autoBind( this );
    }

    async insert( req, res, next ) {
        try {           
            res.sendCalmResponse( req.file.data );
        } catch ( e ) {
            next( e );
        }
    }

    async insertMediaMiddleware( req, res, next ) {
        try {
            if( !req.file) {
                 throw new CalmError( 'VALIDATION_ERROR', 'File is required' );
             }
            const account = req.user.account;
            // console.log('req.body----------',req.body);
            // console.log('req.file----------',req.file);
            
            // const { key, Key } = await this.S3Upload.uploadFile( req.file.buffer, req.file.originalname, { 'ACL': 'public-read', 'pathPrefix': 'uploads' } );
            req.file = await this.service.insert( { ...req.file, account/* , 'path': key || Key */ } );
            next();
        } catch ( e ) {
            next( e );
        }
    }

    async insertTemplateMediaMiddleware(req, res, next){
        try {
            if( req.body.savePath === undefined) {
                 throw new CalmError( 'VALIDATION_ERROR', 'File is required' );
             }

            var inStr = fs.createReadStream(req.body.htmlPath);
            var outStr = fs.createWriteStream(req.body.savePath);
            // console.log(inStr);
            inStr.pipe(outStr);
            req.file = {mimetype:"template",filename:req.body.originalname,path:req.body.savePath,imagePath:req.body.imagePath,originalname:req.body.html};
            const account = req.user.account;
            req.file = await this.service.insert( { ...req.file, account} );
            next();
        } catch ( e ) {
            next( e );
        }
    }

    async delete( req, res, next ) {
        const { id } = req.params;
        try {
            
            
            const response = await this.service.model.findByIdAndDelete( id );
            if(response.path !== undefined)
             await fs.promises.unlink(response.path);

            res.sendCalmResponse( {status:true,data:response}, { 'deleted': true } );
        } catch ( e ) {
            next( e );
        }
    }
}
module.exports = new MediaController( mediaService );