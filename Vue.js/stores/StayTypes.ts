import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/stays';
const TYPES: string = '/stay/categories';

export class StayTypes extends Model<IStays> implements IStays {

  id!: number | null;
  mainCustomerName!: string;
  numberofCustomers!: number | null;
  checkInDate!: string;
  checkOutDate!: string;
  status!: string;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          mainCustomerName: '',
          numberofCustomers: '',
          checkInDate: '',
          checkOutDate: '',
          status: '',
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

export default class StayTypesCollection extends Collection<StayTypes> {

  routeName = 'stayTypes';

  model(): Constructor<StayTypes> {
    return StayTypes;
  }

  url(): string {
    return URL;
  }

  typesUrl(): string {
    return TYPES;
  }

  fetchData(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    if (search) {
      return axiosInstance.get(`${this.typesUrl()}?search=${search}&page=${index}&perpage=${perPage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data.data);
    } else {
      return axiosInstance.get(`${this.typesUrl()}?page=${index}&perpage=${perPage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data.data);
    }
  }

  updateItem(id: any, data: any) {
    return axiosInstance.patch(`${this.typesUrl()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  deleteItem(id: any) {
    return axiosInstance.delete(`${this.typesUrl()}/${id}`)
      .then((response) => response.data)
      .catch((error) => error);
  }

  createNewItem(newStaysCategory: IStaysCategory) {
    return axiosInstance.post(`${this.typesUrl()}`, newStaysCategory)
      .then((response) => response.data);
  }

}
