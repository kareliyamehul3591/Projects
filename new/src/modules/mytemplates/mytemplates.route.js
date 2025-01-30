'use strict';
const mytemplatesController = require('./mytemplates.controller');
const AuthController = require( '../auth/auth.controller' );
const express = require('express');
const router = express.Router();

router.use( AuthController.checkLogin );

router.post('/save-to-mytemplate', mytemplatesController.insert);
router.get('/', mytemplatesController.getAll);
router.get('/byTemplate/:templateId', mytemplatesController.getByTemplate);
router.delete('/:id', mytemplatesController.delete);


module.exports = router;
