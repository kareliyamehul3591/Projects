import ItemsCollection, { Item } from './ItemsCollection';

import Axios from 'axios';
import Module from './Module';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;

export class TvModuleItem extends Item implements IChannel {
  id!: number | null;
  name!: string;
  channelNumber!: number;
  imageId!: number;
  isAdult!: boolean;
  timeShift!: boolean;
  timeShiftDays!: number;
  epg!: boolean;
  recording!: boolean;
  type!: ChannelType;
  active!: boolean;
  categories!: ICategories[];
  sources!: ISource[];
}

export class TvItemsCollection extends ItemsCollection<TvModuleItem> {
  model() {
    return TvModuleItem;
  }

  url(): string {
    return this.module.url();
  }
}

export default class TvModule extends Module<TvItemsCollection> {

  routeName = 'tv';

  Collection() {
    return TvItemsCollection;
  }

  url() {
    return `/tv/${this.id}`;
  }

  urlChannel() {
    return `${this.url()}/channels`;
  }

  urlCategory() {
    return `${this.url()}/categories`;
  }

  updateItem(id: any, data: any) {
    console.log('Channel update data: ', data);
    return axiosInstance.patch(`${this.urlChannel()}/${id}`, data)
      .then((response) => response.data);
  }

  updateCategory(id: any, category: ICategories) {
    return axiosInstance.patch(`${this.urlCategory()}/${id}/`, category)
      .then((response) => response.data);
  }
  getSubItems(id: number, search: string) {
    return axiosInstance.get(`${this.urlCategory()}/${id}/content?search=${search}`)
      .then((response) => {
        return response.data;
      });
  }

  sortCategories(id: number, newPosition: number) {
    return axiosInstance.patch(`${this.urlCategory()}/${id}/resort`, {sort: newPosition})
      .then((response) => response.data);
  }

  sortChannels(id: number, newPosition: number) {
    return axiosInstance.patch(`${this.urlChannel()}/${id}/resort`, {channelNumber: newPosition})
      .then((response) => response.data);
  }

  deleteCategory(id: number) {
    return axiosInstance.delete(`${this.urlCategory()}/${id}`)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.urlChannel()}/${id}`)
      .then((response) => response.data);
  }

  editExistingTimeshifte(id: number, timeshift: any) {
    return axiosInstance.put(`${this.urlChannel()}/${id}/timeshifts/${timeshift.id}`, timeshift)
      .then((response) => response.data);
  }

  deleteTimeshift(channelId: number, timeshiftId: number) {
    return axiosInstance.delete(`${this.urlChannel()}/${channelId}/timeshifts/${timeshiftId}`)
      .then((response) => response.data);
  }

  createNewTimeshift(id: number, timeshift: any) {
    return axiosInstance.post(`${this.urlChannel()}/${id}/timeshifts`, timeshift)
      .then((response) => response.data);
  }

  attachEPG(id: number, name: string) {
    return axiosInstance.post(`${this.urlChannel()}/${id}/epg/attach`, {channel: name})
      .then((response) => response.data);
  }

  detachEPG(id: number, index: number) {
    return axiosInstance.delete(`${this.urlChannel()}/${id}/epg/detach/${index}`)
      .then((response) => response.data);
  }

  editExistingSource(id: number, source: any) {
    return axiosInstance.put(`${this.urlChannel()}/${id}/sources/${source.id}`, source)
      .then((response) => response.data);
  }

  saveCategories(id: number, tags: ICategories[]) {
    return axiosInstance.post(`${this.urlCategory()}/${id}/categories`, tags)
      .then((response) => response.data);
  }

  getCategories(id: number) {
    return axiosInstance.get(`${this.urlChannel()}/${id}/unattached`)
      .then((response) => response.data);
  }

  getAutocompleteCategories(query: string, source: any) {
    return axiosInstance.get(`${this.urlCategory()}?query=${query}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  createNewSource(id: number, sources: any) {
    return axiosInstance.post(`${this.urlChannel()}/${id}/sources`, sources)
      .then((response) => response.data);
  }

  getSourceTypes() {
    return axiosInstance.get(`${this.urlChannel()}/devices/types`)
      .then((response) => response.data);
  }

  getSelectedChannel(id: number) {
    return axiosInstance.get(`${this.urlChannel()}/${id}`)
      .then((response) => response.data);
  }

  getSelectedChannelSources(id: number) {
    return axiosInstance.get(`${this.urlChannel()}/${id}/sources`)
      .then((response) => response.data);
  }

  searchSelectedChannelSources(id: number, query: string, source: any) {
    return axiosInstance.get(`${this.urlChannel()}/${id}/sources?query=${query}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  deleteSource(channelID: number, sourceID: number) {
    return axiosInstance.delete(`${this.urlChannel()}/${channelID}/sources/${sourceID}`)
      .then((response) => response.data);
  }

  getUnattachedChannelsForCategory(id: number) {
    return axiosInstance.get(`${this.urlCategory()}/${id}/unattached`)
      .then((response) => response.data);
  }

  searchTvChannelCategory(id: number, input: string) {
    return axiosInstance.get(`${this.urlCategory()}/${id}?query=${input}`)
      .then((response) => response.data);
  }

  saveTVModule(newModule: IModule, id: number) {
    return axiosInstance.put(`${this.url}/${id}`, newModule)
      .then((response) => response.data);
  }

  editSelectedChannel(channel: IChannel) {
    return axiosInstance.put(`${this.urlChannel()}/${channel.id}`, channel)
      .then((response) => response.data);
  }

  fetchData(index: number | null, search: string | null, perpage: number | null) {
    if (cancel !== undefined) {
      cancel();
    }

    if (search) {
      return axiosInstance.get(`${this.urlChannel()}?search=${search}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
          .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.urlChannel()}?page=${index}&perpage=${perpage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
          .then((response) => response.data);
    }
  }

  fetchCategoryData(search: string | null) {
    if (cancel !== undefined) {
      cancel();
    }

    if (search) {
      return axiosInstance.get(`${this.urlCategory()}?search=${search}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
          .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.urlCategory()}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
          .then((response) => response.data);
    }
  }

  getAllModuleElements() {
    return axiosInstance.get(`${this.urlChannel()}`)
      .then((response) => response.data);
  }

  search(input: string, source: any) {
    return axiosInstance.get(`${this.urlChannel()}?query=${input}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  createNewItem(newChannel: IChannel) {
    return axiosInstance.post(`${this.urlChannel()}`, newChannel)
      .then((response) => {
        return response.data;
      });
  }

  createNewCategory(newCategory: ICategories) {
    return axiosInstance.post(`${this.urlCategory()}`, {category: newCategory})
      .then((response) => {
        return response.data;
      });
  }

  getAllCategories(search: string | null = null) {
    if (cancel !== undefined) {
      cancel();
    }
    const searchString = (Boolean(search)) ? `search=${search}` : '';
    return axiosInstance.get(`${this.urlCategory()}?${searchString}`, {
      cancelToken: new CancelToken(function executor(c: any) {cancel = c; }),
    }).then((response) => response.data);
  }

  searchCategory(input: string, source: any) {
    return axiosInstance.get(`${this.urlCategory()}?query=${input}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  getSpecificCategory(id: number) {
    return axiosInstance.get(`${this.urlCategory()}/${id}`)
      .then((response) => response.data);
  }

  editCategory(category: ICategories) {
    return axiosInstance.put(`${this.urlCategory()}/${category.id}`, category)
      .then((response) => response.data);
  }

  addChannelToCategory(category: ICategories, ids: number[]) {
    return axiosInstance.post(`${this.urlCategory()}/${category.id}/attach`, ids)
      .then((response) => response.data);
  }

  detachCategoryfromChannel(channel: IChannel, n: any) {
    return axiosInstance.post(`${this.urlChannel()}/${channel.id}/detach`, {name: n.tag.text})
      .then((response) => response.data);
  }

  attachCategoryToChannel(channel: IChannel, n: any) {
    return axiosInstance.post(`${this.urlChannel()}/${channel.id}/attach`, {name: n.tag.text})
      .then((response) => response.data);
  }
  attachPackageToChannel(channel: IChannel, n: any) {
    return axiosInstance.post(`${this.urlChannel()}/${channel.id}/attachpackagetotv`, {name: n.tag.text})
      .then((response) => response.data);
  }
  detachPackage(channel: IChannel, n: any) {
    return axiosInstance.post(`${this.urlChannel()}/${channel.id}/detachpackagetotv`, {name: n.tag.text})
      .then((response) => response.data);
  }

  removeChannelfromCategory(category: ICategories, id: number) {
    return axiosInstance.post(`${this.urlCategory()}/${category.id}/detach`, id)
      .then((response) => response.data);
  }

  getTvChannelValidationRules() {
    return axiosInstance.get(`tvchannel/validation`)
      .then((response) => response.data);
  }

  getTvCategoryValidationRules() {
    return axiosInstance.get(`tvcategory/validation`)
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
