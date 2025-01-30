'use strict';
class GetDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.themeId = props.themeId;
        this.uniqueCode = props.uniqueCode;
        this.displaysize = props.displaysize;
        this.playlistName = props.playlistName;
        this.stores = props.stores;
        this.displayMode = props.displayMode;
        this.selectedDisplays = props.selectedDisplays;
        this.staticAddress = props.staticAddress;
        this.logo = props.logo;
        this.scheduleType = props.scheduleType;
        this.startDate = props.startDate;
        this.endDate = props.endDate;
        this.recurring = props.recurring;
        this.zone1 = props.zone1;
        this.zone2 = props.zone2;
        this.zone3 = props.zone3;
        this.zone4 = props.zone4;
        this.status = props.status;
        this.displayStatus = props.displayStatus;
        this.updatedAt = props.updatedAt;
        Object.freeze( this );
    }
}
class InsertDTO {
    constructor( { ...props } ) {
        this.themeId = props.themeId;
        this.uniqueCode = props.uniqueCode;
        this.displaysize = props.displaysize;
        this.playlistName = props.playlistName;
        this.stores = props.stores;
        this.displayMode = props.displayMode;
        this.selectedDisplays = props.selectedDisplays;
        this.staticAddress = props.staticAddress;
        this.logo = props.logo;
        this.scheduleType = props.scheduleType;
        this.startDate = props.startDate;
        this.endDate = props.endDate;
        this.recurring = props.recurring;
        this.zone1 = props.zone1;
        this.zone2 = props.zone2;
        this.zone3 = props.zone3;
        this.zone4 = props.zone4;
        this.account = props.account;
        this.displayStatus = props.displayStatus;
        Object.freeze( this );
    }
}
class UpdateDTO {
    constructor( { ...props } ) {
        this.themeId = props.themeId;
        this.displaysize = props.displaysize;
        this.playlistName = props.playlistName;
        this.stores = props.stores;
        this.displayMode = props.displayMode;
        this.selectedDisplays = props.selectedDisplays;
        this.staticAddress = props.staticAddress;
        this.logo = props.logo;
        this.scheduleType = props.scheduleType;
        this.startDate = props.startDate;
        this.endDate = props.endDate;
        this.recurring = props.recurring;
        this.zone1 = props.zone1;
        this.zone2 = props.zone2;
        this.zone3 = props.zone3;
        this.zone4 = props.zone4;
        this.displayStatus = props.displayStatus;
        // Delete Fields which are not present in data
        Object.keys( this ).forEach( key => {
            if ( this[ key ] === undefined ) {
                delete this[ key ];
            }
        } );
        Object.freeze( this );
    }
}
class UpdateStatusDTO {
    constructor( { ...props } ) {
        this.status = props.status;
        this.displayStatus = props.displayStatus;
        // Delete Fields which are not present in data
        Object.keys( this ).forEach( key => {
            if ( this[ key ] === undefined ) {
                delete this[ key ];
            }
        } );
        Object.freeze( this );
    }
}
class UpdateStatusResponseDTO {
    constructor( { ...props } ) {
        this._id = props._id;
        this.status = props.status;
        this.displayStatus = props.displayStatus;
        Object.freeze( this );
    }
}
module.exports = { GetDTO, InsertDTO, UpdateDTO, UpdateStatusDTO, UpdateStatusResponseDTO };