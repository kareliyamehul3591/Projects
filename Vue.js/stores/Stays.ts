import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/stays';
const TYPES: string = '/stay/categories';

export class Stays extends Model<IStays> implements IStays {

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

export default class StaysCollection extends Collection<Stays> {

  routeName = 'stays';

  model(): Constructor<Stays> {
    return Stays;
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
      return axiosInstance.get(`${this.url()}?search=${search}&page=${index}&perpage=${perPage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data.data);
    } else {
      return axiosInstance.get(`${this.url()}?page=${index}&perpage=${perPage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data.data);
    }
  }

  fetchStayTypesData(index: number | null, search: string | null, perPage: number | 10) {
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

  createNewItem(newStays: IStays) {
    return axiosInstance.post(`${this.url()}`, newStays)
      .then((response) => response.data);
  }

  attachStay(idList: number[], customerId: any) {
    return axiosInstance.post(`customers/${customerId}/stays/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachStay(idList: number[], customerId: any) {
    return axiosInstance.post(`customers/${customerId}/stays/detach`, {ids: idList})
      .then((response) => response.data);
  }

  attachCatgories(selectedInterest: [], stayId: any) {
    return axiosInstance.post(`stays/${stayId}/categories/attach`, {categories: selectedInterest})
      .then((response) => response.data);
  }

  detachCatgories(selectedInterest: [], stayId: any) {
    return axiosInstance.post(`stays/${stayId}/categories/detach`, {categories: selectedInterest})
      .then((response) => response.data);
  }

  attachCustomer(idList: number[], stayId: any) {
    return axiosInstance.post(`stays/${stayId}/customers/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachRelatedCustomer(idList: number[], stayId: any) {
    return axiosInstance.post(`stays/${stayId}/customers/detach`, {ids: [idList]})
      .then((response) => response.data);
  }

  detachCustomers(idList: number[], customerId: any) {
    return axiosInstance.post(`customers/${customerId}/stays/detach`, {ids: idList})
      .then((response) => response.data);
  }

  attachStays(idList: number[], stayId: any) {
    return axiosInstance.post(`stays/${stayId}/locations/attach`, {ids: [idList]})
      .then((response) => response.data);
  }

  getDetail(staysId: any) {
    return axiosInstance.get(`/stays/${staysId}`)
      .then((response) => response.data);
  }

  getTypesDetail(staysId: any) {
    return axiosInstance.get(`${this.typesUrl()}/${staysId}`)
      .then((response) => response.data);
  }

  /*
   * Patch
   */
  updateCategory(id: any, data: any) {
    return axiosInstance.patch(`${this.typesUrl()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  deleteCategory(id: any) {
    return axiosInstance.delete(`${this.typesUrl()}/${id}`)
      .then((response) => response.data)
      .catch((error) => error);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  getAutocompleteCategories(query: string, source: any) {
    return axiosInstance.get(`/stay/categories`, {cancelToken: source.token})
      .then((response) => response.data.data);
  }

  updateItem(id: any, stays: any) {
    return axiosInstance.put(`${this.url()}/${id}`, stays)
      .then((response) => response.data);
  }

  getSelectedStays(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((reason) => console.log('error reasone', reason));
  }

  searchStays(input: string, page: number, source: any, perPage: number | 10) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}&perPage=${perPage}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  createNewTypes(newStaysCategory: IStaysCategory) {
    return axiosInstance.post(`${this.typesUrl()}`, newStaysCategory)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }
  setmainCustomer(stayId: any, customerId: any) {
    console.log(`customers/${customerId}/stays/${stayId}/main-guest`);
    return axiosInstance.get(`customers/${customerId}/stays/${stayId}/main-guest`)
      .then((response) => response.data);
  }
  getStaysOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  getAllStays() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getStaysValidationRules() {
    return axiosInstance.get(`Stays/validation`)
      .then((response) => response.data);
  }

  importStays(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportStays(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }
}
