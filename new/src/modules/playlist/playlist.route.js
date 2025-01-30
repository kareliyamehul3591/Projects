'use strict';
const PlaylistController = require( './playlist.controller' );
const AuthController = require( '../auth/auth.controller' );

const express = require( 'express' );
const router = express.Router();
// without login check
router.get( '/:id', PlaylistController.get );
router.use( AuthController.checkLogin );
router.get( '/', PlaylistController.getAll );
router.get( '/playlistByDeviceId/:deviceId', PlaylistController.playlistByDeviceId );
router.post('/', PlaylistController.insert);
router.put( '/:id', PlaylistController.update );
router.put( '/update-status/:id', PlaylistController.updateStatus );
module.exports = router;