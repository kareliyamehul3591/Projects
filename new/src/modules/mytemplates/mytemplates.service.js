'use strict';
const { CalmService } = require('../../../system/core/CalmService');
const mongoose = require('mongoose'); // Add this line
class mytemplatesService extends CalmService {
populateFields = [{ 'path': 'createdBy' }, { 'path': 'updatedBy' }];
constructor(model, mytemplatesModel) {
    super(model);
    this.mytemplatesModel = mytemplatesModel;
}
    // mytemplates.service.js
    async getAll( query ) {
        let { skip, limit, sortBy } = query;

        skip = skip ? Number( skip ) : 0;
        limit = limit ? Number( limit ) : 10;
        sortBy = sortBy ? sortBy : { 'createdAt': -1 };

        delete query.skip;
        delete query.limit;
        delete query.sortBy;

        try {
            const items = await this.model.find( query ).sort( sortBy ).skip( skip ).limit( limit ).populate( 'media' );
            
            const total = await this.model.countDocuments( query );

            return { 'data': JSON.parse( JSON.stringify( items ) ), total };
        } catch ( errors ) {
            throw errors;
        }
    }

    /**
     * Insert new mytemplates
     * @param {Object} data The data to insert
     */    
    async deleteById(id) {
        try {
            const deletedTemplate = await this.model.findByIdAndDelete(id);
            return deletedTemplate;
        } catch (errors) {
            throw errors;
        }
    }
      
    
}

module.exports = { mytemplatesService };
