'use strict';
class GetDTO {
    constructor( { ...props } ) {
        this.uniqueCode = props.uniqueCode;
        this.ipAddress = props.ipAddress;
        this.deviceToken = props.deviceToken;
        this.display = props.display;
        this.deviceBrand = props.deviceBrand;
        this.ipv4Address = props.ipv4Address;
        Object.freeze( this );
    }
}
module.exports = { GetDTO };
