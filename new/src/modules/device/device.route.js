'use strict';
const DeviceController = require('./device.controller'); // Ensure the correct path
const express = require('express');
const router = express.Router(); // Initialize router
// Define the route for checking the device
router.post('/',DeviceController.get);
module.exports = router;