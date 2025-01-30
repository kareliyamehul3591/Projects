'use strict';
const { CalmService } = require('../../../system/core/CalmService');

class TemplateService extends CalmService {
    populateFields = [{ 'path': 'createdBy' }, { 'path': 'updatedBy' }];
    
    constructor(model, templateModel) {
        super(model);
        this.templateModel = templateModel;
    }

    /**
     * Get All Templates
     * @param {Object} query Query Parameters
     */
    async getAllTemplates(query) {
        let { skip, limit, sortBy } = query;

        skip = skip ? Number(skip) : 0;
        limit = limit ? Number(limit) : 10;
        sortBy = sortBy ? sortBy : { 'createdAt': -1 };

        delete query.skip;
        delete query.limit;
        delete query.sortBy;

        try {
            const templates = await this.model.find(query)
                .select('_id image location datetime')  // Select the required fields
                .sort(sortBy)
                .skip(skip)
                .limit(limit)
                .populate('stores selectedtemplates logo');  // If you need to populate fields

            const total = await this.model.countDocuments(query);

            return { data: JSON.parse(JSON.stringify(templates)), total };
        } catch (errors) {
            throw errors;
        }
    }
}

module.exports = { TemplateService };
