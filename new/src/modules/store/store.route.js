'use strict';
const StoreController = require( './store.controller' );
const AuthController = require( '../auth/auth.controller' );
const express = require( 'express' );
const router = express.Router();

router.use( AuthController.checkLogin );

router.get( '/', StoreController.getAll );
router.get( '/generate-store-id', StoreController.generateStoreId );
router.get( '/:id', StoreController.get );
router.post( '/', StoreController.insert );
router.put( '/:id', StoreController.update );
router.put( '/update-status/:id', StoreController.updateStatus );
router.delete( '/:id', StoreController.delete );


module.exports = router;
