'use strict';
const { CalmController } = require('../../../system/core/CalmController');
const { mytemplatesService } = require('./mytemplates.service');
const { mytemplates } = require('./mytemplates.model');
const mytemplatesDTO = require('./mytemplates.dto');
var fs = require('fs');
const autoBind = require('auto-bind');
// Use a different name for the instance
const mytemplatesServiceInstance = new mytemplatesService(
    new mytemplates().getInstance(),
);
class mytemplatesController extends CalmController {
    constructor(service) {
        super(service);
        this.dto = { ...this.dto, ...mytemplatesDTO };
        autoBind(this);
    }
    // mytemplates.controller.js
    async getAll( req, res, next ) {
        try {

            if(req.user.role != "admin")
                req.query.account = req.user.account;

            if ( req.query.search ) {
                const searchElem = req.query.search;
                const searchValue = { '$regex': searchElem, '$options': 'si' };
                req.query = { ...req.query, '$or': [ { 'name': searchValue } ] };
            }
            delete req.query.search;
            await super.getAll( req, res, next );
        } catch ( e ) {
            next( e );
        }
    }

    async getByTemplate(req, res, next){
      
        const { templateId } = req.params;   
        var  query = {};  
        if(req.user.role != "admin")
        {
            query = {templateId:templateId,account:req.user.account};
        }else{
            query = {templateId:templateId};
        }
        try {
          var data =  await this.service.model.findOne(query);
        
          res.status(200).json({status:(!data) ? true: false});
        } catch ( e ) {
            next( e );
        }
    }
    // Updated insert method in mytemplatesController
    async insert(req, res, next) {
        try {
           
            req.body.account = req.user.account;
            req.body.type = 'template';
            req.body.htmlPath = req.body.savePath;
            
            
            await super.insert( req, res, next );

        } catch (e) {
            next(e);
        }
    }
    async delete(req, res, next) {
        try {
            const { id } = req.params;
            const deletedTemplate = await this.service.deleteById(id);
            if (deletedTemplate) {
                return res.status(200).json({ message: 'Template deleted successfully' });
            } else {
                return res.status(404).json({ message: 'Template not found' });
            }
        } catch (e) {
            next(e);
        }
    }
}
module.exports = new mytemplatesController(mytemplatesServiceInstance);