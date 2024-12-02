import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/location/zones';
const URLCATEGORY: string = '/location/categories';

export class Locations extends Model<Ilocations> implements Ilocations {

  id!: number | null;
  propertyId!: number | null;
  name!: string;
  description!: string;
  active!: boolean;
  sort!: number | null;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          propertyId: '',
          name: '',
          description: '',
          active: '',
          sort: '',
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }

  urlcategory(): string {
    return URLCATEGORY;
  }

}

export default class LocationsCollection extends Collection<Locations> {

  routeName = 'zones';

  model(): Constructor<Locations> {
    return Locations;
  }

  url(): string {
    return URL;
  }

  urlcategory(): string {
    return URLCATEGORY;
  }

  fetchData(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    let url = this.url() + '?page=' + index + '&perpage=' + perPage;
    if (search) {
      url = url + '&search=' + search;
    }

    return axiosInstance.get(url, {
      cancelToken: new CancelToken(function executor(c: any) {
        cancel = c;
      }),
    })
      .then((response) => response.data.data);
  }

  fetchCategory(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    let url = this.urlcategory() + '?page=' + index + '&perpage=' + perPage;
    if (search) {
      url = url + '&search=' + search;
    }

    return axiosInstance.get(url, {
      cancelToken: new CancelToken(function executor(c: any) {
        cancel = c;
      }),
    })
      .then((response) => response.data.data);
  }

  updateLocation(id: any, data: any) {
    return axiosInstance.put(`${this.url()}/${id}`, {propertyId: data})
      .then((response) => response.data)
      .catch((error) => error);
  }

  updateItem(id: any, data: any) {
    return axiosInstance.patch(`${this.url()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  updateCategoryDetailPage(id: any, data: any) {
    return axiosInstance.patch(`${this.urlcategory()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  createNewItems(item: any) {
    return axiosInstance.post(`${this.url()}`, item)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }

  createNewCategory(item: any) {
    return axiosInstance.post(`${this.urlcategory()}`, item)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }

  attachStay(idList: number[], zoneId: any) {
    return axiosInstance.post(`location/${zoneId}/stay/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachStay(idList: number[], zoneId: any) {
    return axiosInstance.post(`location/${zoneId}/stay/detach`, {ids: [idList]})
      .then((response) => response.data);
  }

  detachCustomers(idList: [], promotionId: any) {
    return axiosInstance.post(`locations/${promotionId}/customers/detach`, {ids: [idList]})
      .then((response) => response.data);
  }

  getDetail(locationsId: any) {
    return axiosInstance.get(`/location/zones/${locationsId}`)
      .then((response) => response.data.data);
  }

  getCategoryDetail(locationsId: any) {
    return axiosInstance.get(`/location/categories/${locationsId}`)
      .then((response) => response.data.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  deleteZoneCategory(id: number) {
    return axiosInstance.delete(`${this.urlcategory()}/${id}`)
      .then((response) => response.data);
  }

  attachDevice(idList: number[], accId: number) {
    return axiosInstance.post(`${this.url()}/${accId}/devices/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachDevice(idList: any, zoneId: number) {
    console.log('url', `${this.url()}/${zoneId}/devices/detach`);
    return axiosInstance.post(`${this.url()}/${zoneId}/devices/detach`, {ids: [idList]})
      .then((response) => response.data);
  }

  attachAccounts(idList: number[], locationsId: number) {
    return axiosInstance.post(`${this.url()}/${locationsId}/devices/attach`, {ids: idList})
      .then((response) => response.data);
  }

  attachDashboard(locationsId: any, dashboardId: any) {
    return axiosInstance.post(`${this.url()}/${locationsId}/dashboard/${dashboardId}/attach`)
      .then((response) => response.data);
  }

  detachDashboard(locationsId: any) {
    return axiosInstance.post(`${this.url()}/${locationsId}/dashboard/detach`)
      .then((response) => response.data);
  }

  searchlocationsAccounts(locationsId: any, keyword: string) {
    return axiosInstance.post(`${this.url()}/${locationsId}/${keyword}/account`)
      .then((response) => response.data);
  }

  detachAccount(locations: Ilocations, account: IAccount) {
    return axiosInstance.get(`${this.url()}/${locations.id}/${account.id}/accounts/detach`)
      .then((response) => response.data);
  }

  editLocations(id: any, locations: any) {
    return axiosInstance.put(`${this.url()}/${id}`, locations)
      .then((response) => response.data);
  }

  getSelectedLocations(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((reason) => console.log('error reasone', reason));
  }

  searchLocations(input: string, page: number, source: any, perPage: number | 10) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}&perPage=${perPage}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  getLocationsOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  getAllLocations() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getAutocompleteCategories(query: string, source: any) {
    return axiosInstance.get(`/location/categories`, {cancelToken: source.token})
      .then((response) => response.data.data);
  }

  attachCatgories(selectedInterest: [], zoneId: any) {
    return axiosInstance.post(`location/zones/${zoneId}/categories/attach`, {categories: [selectedInterest]})
      .then((response) => response.data);
  }

  getLocationsValidationRules() {
    return axiosInstance.get(`locations/validation`)
      .then((response) => response.data);
  }

  importLocations(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportLocations(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }
}
