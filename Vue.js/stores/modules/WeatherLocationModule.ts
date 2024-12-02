import ItemsCollection, { Item } from './ItemsCollection';
import Axios from 'axios';
import Module from './Module';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;

export class WeatherLocationModuleItem extends Item implements IWeatherLocation {
  id!: number | null;
  moduelId!: number | null;
  city!: string;
  country!: string;
  latitude!: number;
  longitude!: number;
}

export class WeatherlocationItemsCollection extends ItemsCollection<WeatherLocationModuleItem> {
  model() {
    return WeatherLocationModuleItem;
  }

  url(): string {
    return this.module.url();
  }

}

export default class WeatherLocationModule extends Module<WeatherlocationItemsCollection> {

  routeName = 'locations';

  Collection() {
    return WeatherlocationItemsCollection;
  }

  url() {
    return `/weather/${this.id}/locations`;
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  updateItem(id: number, loc: IWeatherLocation) {
    return axiosInstance.put(`${this.url()}/${id}`, loc)
      .then((response) => response.data);
  }

  createNewItem(loc: IWeatherLocation) {
    return axiosInstance.post(`${this.url()}`, loc)
      .then((response) => {
        return response.data;
      });
  }

  fetchData(index: number, search: string, perpage = 20) {
    if (cancel !== undefined) {
      cancel();
    }
    return axiosInstance.get(`${this.url()}?page=${index}&perpage=${perpage}&search=${search}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
      .then((response) => response.data);
  }

  getAllWeatherLocations() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getWeatherLocationsRules() {
    return axiosInstance.get(`weather/validation`)
      .then((response) => response.data);
  }

  import(data: any, tableName: string, id: any) {
    return axiosInstance.post(`import/${tableName}?type=module&module_id=${id}`, {data})
      .then((response) => response.data);
  }

  export(tableName: string, id: any) {
    return axiosInstance.get(`export/${tableName}?type=module&module_id=${id}`)
      .then((response) => response.data);
  }
}
