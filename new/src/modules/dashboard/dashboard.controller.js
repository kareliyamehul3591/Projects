'use strict';
const { CalmController } = require( '../../../system/core/CalmController' );
const { DashboardService } = require( './dashboard.service' );
const { Display } = require( '../display/display.model' );
const { Store } = require( '../store/store.model' );
const dashboardDTO = require( './dashboard.dto' );
const autoBind = require( 'auto-bind' ),
    dashboardService = new DashboardService(
        new Display().getInstance(),
        new Store().getInstance()
    );

class DashboardController extends CalmController {

    constructor( service ) {
        super( service );
        this.dto = { ...this.dto, ...dashboardDTO };
        autoBind( this );
    }

    async ownerDashboard( req, res, next ) {
        const account = req.user.account;

        try {
            const response = await this.service.ownerDashboard( account );
            res.sendCalmResponse( response.data );
        } catch ( e ) {
            next( e );
        }
    }

}

module.exports = new DashboardController( dashboardService );
