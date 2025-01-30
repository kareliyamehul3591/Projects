'use strict';

class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.uniqueCode = props.uniqueCode;
        this.playlistName = props.playlistName;
        this.stores = props.stores;
        this.displayMode = props.displayMode;
        this.displaysize = props.displaysize;
        this.selectedDisplays = props.selectedDisplays;
        this.logo = props.logo;
        this.scheduleType = props.scheduleType;
        this.recurring = props.recurring;
        this.zone1 = props.zone1;
        this.zone2 = props.zone2;
        this.zone3 = props.zone3;
        this.zone4 = props.zone4;
        Object.freeze( this );
    }
}
class InsertDTO {
    constructor( { ...props } ) {
        this.uniqueCode = props.uniqueCode,
        this.ipAddress = props.ipAddress,
        this.deviceToken = props.deviceToken,
        this.display = props.display,
        this.deviceBrand = props.deviceBrand,
        this.ipv4Address = props.ipv4Address,
        Object.freeze( this );
    }
}
module.exports = { GetDTO,InsertDTO };
