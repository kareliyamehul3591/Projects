import ItemsCollection, { Item } from './ItemsCollection';
import Axios from 'axios';
import Module from './Module';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;

export class WorldClockLocationModuleItem extends Item implements IWorldClockLocation {
  id!: number | null;
  moduelId!: number | null;
  city!: string;
  timeZone!: string;
}

export class WorldClockLocationItemsCollection extends ItemsCollection<WorldClockLocationModuleItem> {
  model() {
    return WorldClockLocationModuleItem;
  }

  url(): string {
    return this.module.url();
  }

}

export default class WorldClockLocationModule extends Module<WorldClockLocationItemsCollection> {

  routeName = 'clock';

  Collection() {
    return WorldClockLocationItemsCollection;
  }

  url() {
    return `/world/${this.id}/clock`;
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  updateItem(id: number, loc: IWorldClockLocation) {
    return axiosInstance.put(`${this.url()}/${id}`, loc)
      .then((response) => response.data);
  }

  createNewWorldClockLocation(loc: IWorldClockLocation) {
    return axiosInstance.post(`${this.url()}`, loc)
      .then((response) => {
        return response.data;
      });
  }

  fetchData(index: number, search = '', perpage: number) {
    if (cancel !== undefined) {
      cancel();
    }
    return axiosInstance.get(`${this.url()}?page=${index}&perpage=${perpage}&search=${search}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
      .then((response) => response.data);
  }

  getAllWorldClockLocations() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getWorldClockRules() {
    return axiosInstance.get(`clock/validation`)
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
