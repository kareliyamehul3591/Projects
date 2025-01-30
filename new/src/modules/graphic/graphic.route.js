'use strict';
const GraphicController = require( './graphic.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();
const path = require('path');
const app = express();

// console.log(__dirname);

// app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

router.use( AuthController.checkLogin );

router.get( '/', GraphicController.getAll );
router.get( '/:id', GraphicController.get );
router.post( '/', GraphicController.insert );
router.put( '/:id', GraphicController.update );
router.put( '/update-status/:id', GraphicController.updateStatus );
router.delete( '/:id', GraphicController.delete );

module.exports = router;
