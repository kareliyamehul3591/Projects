'use strict';


class InsertDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.title = props.title;
        this.content = props.content;
        // Auto Generated Fields
        this.createdBy = props.createdBy;
        this.updatedBy = props.updatedBy;
        this.createdAt = props.createdAt;
        this.updatedAt = props.updatedAt;
        Object.freeze( this );
    }
}


module.exports = { InsertDTO };
