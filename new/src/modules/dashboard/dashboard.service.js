'use strict';
const autoBind = require( 'auto-bind' );

class DashboardService {
    // Setting Global Populate to apply in Get All & Get Single
    populateFields = [ { 'path': 'createdBy' }, { 'path': 'updatedBy' } ];
    constructor( displayModel, storeModel ) {
        this.displayModel = displayModel;
        this.storeModel = storeModel;
        autoBind( this );
    }

    /**
     * Get owner dashboard
     * @param { string } account Query Parameters
     */
    async ownerDashboard( account ) {

        try {
            const totalDisplays = await this.displayModel.countDocuments( { account } );
            const inactiveDisplays = await this.displayModel.countDocuments( { account, 'status': false } );
            const activeDisplays = totalDisplays - inactiveDisplays;
            const totalSotores = await this.storeModel.countDocuments( { account } );
            const inactiveStores = await this.storeModel.countDocuments( { account, 'status': false } );
            const activeStores = totalSotores - inactiveStores;

            return { 'data': { totalDisplays, inactiveDisplays, activeDisplays, totalSotores, inactiveStores, activeStores } };
        } catch ( errors ) {
            throw errors;
        }
    }
}

module.exports = { DashboardService };
