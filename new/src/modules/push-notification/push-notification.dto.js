'use strict';

class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.displays = props.displays;
        this.title = props.title;
        this.description = props.description;
        this.createdAt = props.createdAt;
        this.isRead = props.isRead;
        Object.freeze( this );
    }
}

class InsertDTO {
    constructor( { ...props } ) {
        this.displays = props.displays;
        this.title = props.title;
        this.description = props.description;
        this.content = props.content;
        Object.freeze( this );
    }
}

module.exports = { GetDTO, InsertDTO };