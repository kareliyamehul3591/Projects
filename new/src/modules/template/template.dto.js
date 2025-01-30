'use strict';
class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.image = props.image;
        this.html = props.html;
        this.imagePath = props.imagePath;
        this.htmlPath = props.htmlPath;
        Object.freeze( this );
    }
}
module.exports = { GetDTO };