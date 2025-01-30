'use strict';


class InsertDTO {
    constructor( { ...props } ) {
        this.playListId = props.playListId;
        this.action = props.action;
        this.message = props.message;
        this.deviceToken = props.deviceToken;
        Object.freeze( this );
    }
}


module.exports = { InsertDTO };
