'use strict';

class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.templateId = props.templateId;
        this.imagePath = props.imagePath;
        this.htmlPath = props.htmlPath;
        this.media = props.media;
        this.type = props.type;
        Object.freeze( this );
    }
}

class InsertDTO {
    constructor( { ...props } ) {
        this.templateId = props.templateId;
        this.imagePath = props.imagePath;
        this.htmlPath = props.htmlPath;
        this.media = props.media;
        this.type = props.type;
        this.account = props.account;
        Object.freeze( this );
    }
}
module.exports = { GetDTO, InsertDTO };
