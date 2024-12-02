import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const URL = '/customers';
export class Location extends Model<ICustomerAddress> implements ICustomerAddress {

    address!: string;
    address2!: string;
    city!: string;
    country!: string;
    id!: number | null;
    customerId!: number | null;
    post!: string;
    default!: number;
    firstName!: string;
    lastName!: string;
    company!: string;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{address: '', address2: '', city: '', country: '', id: 0, customerId: 0, post: '', default: 0, company: '', firstName: '', lastName: ''}, ...attributes}, // Default values
      parentCollection,
    );
  }
  urlRoot(): string {
    return URL;
  }
}

export default class LocationCollection extends Collection<Location> {

  url(): string {
    return URL;
  }

  model(): Constructor<Location> {
    return Location;
  }

  editAddress(locationID: number, subscriberID: number, newLocation: ICustomerAddress) {
    return axiosInstance.put(`${this.url()}/${subscriberID}/addresses/${locationID}`, newLocation)
      .then((response) => response.data);
  }

  setDefaultAddress(locationID: number, subscriberID: number) {
    return axiosInstance.patch(`${this.url()}/${subscriberID}/addresses/${locationID}/default`)
      .then((response) => response.data);
  }

  deleteAddress(locationID: number, subscriberID: number) {
    return axiosInstance.delete(`${this.url()}/${subscriberID}/addresses/${locationID}`)
      .then((response) => response.data);
  }

  createNewAddress(subscriberID: number, newAddress: ICustomerAddress) {
    return axiosInstance.post(`${this.url()}/${subscriberID}/addresses`, newAddress)
      .then((response) => response.data);
  }
}
