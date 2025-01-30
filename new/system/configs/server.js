'use strict';
const express = require( 'express' );
const helmet = require( 'helmet' );
const cors = require( 'cors' );
const server = express();
const { setRoutes } = require( './routing' );
const path = require('path');
// Apply Helmet Security

/* Image display for graphics & videos */
const staticPath = path.join(__dirname, '../../uploads');
server.use('/uploads', express.static(staticPath));

server.use( helmet() );
// CORS Configuration
server.use( cors( { 'origin': '*' } ) );
// Setup Body Parser
server.use( express.json() );
server.use( express.urlencoded( { 'extended': true } ) );



// Setup Routes
setRoutes( server );
module.exports = {
    server
};
