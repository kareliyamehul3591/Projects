import Collection from './abstract/Collection';
import Module from './modules/Module';
import PagesModule from './modules/Pages';
import RecorderModule from './modules/Recorders';
import Axios from 'axios';
import StreamModule from './modules/Streams';
import TvModule from './modules/TvModule';
import VODModule from './modules/VODs';
import ApplicationsModule from '@/stores/modules/Applications';
import WeatherLocationModule from './modules/WeatherLocationModule';
import WorldClockLocationModule from './modules/WorldClockLocation';
import axiosInstance from '@/helpers/axios';
import SeriesModule from '@/stores/modules/Series';

const CancelToken: any = Axios.CancelToken;
let cancel: any;

type ModuleTypes = { [key in ModuleType]?: Constructor; };
const moduleTypeMap: ModuleTypes = {
  TV_RADIO: TvModule,
  VOD: VODModule,
  TRANSCODER: StreamModule,
  RECORDER: RecorderModule,
  PAGES: PagesModule,
  WEATHER: WeatherLocationModule,
  TIME: WorldClockLocationModule,
  APPLICATION: ApplicationsModule,
  SERIES: SeriesModule,
};

export default class ModulesCollection extends Collection<Module> {

  routeName = 'modules';

  get items() {
    return this.models;
  }

  url() {
    return '/modules';
  }

  model() {
    return Module;
  }

  /**
   * Overwritten, to allow dynamic models
   */
  build(attributes: { [key: string]: any } = {}) {
    // @ts-ignore
    const modelClass = moduleTypeMap[attributes.type] || this.model();
    return new modelClass(attributes, this);
  }

  modules() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data)
      .catch((error) => error);
  }

  getModule(id: any) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => {
        return response.data;
      }).catch((error) => error);
  }

  fetchData(index: any = 1, search: any = '', perpage: any = 100000) {
    if (cancel !== undefined) {
      cancel();
    }
    return axiosInstance.get(`${this.url()}/list/?page=${index}&search=${search}&perpage=${perpage}`, {
      cancelToken: new CancelToken(function executor(c: any) {
        cancel = c;
      }),
    })
      .then((response) => response.data);
  }

  createNewModule(moduel: IModule) {
    console.log('res', moduel);
    return axiosInstance.post(`${this.url()}`, moduel)
      .then((response) => {
        return response.data;
      });
  }

  updateItem(id: any, data: any) {
    return axiosInstance.patch(`${this.url()}/${id}`, data)
      .then((response) => response.data);
  }

  getSourceTypes() {
    return axiosInstance.get(`/devices/types/all`)
      .then((response) => response.data);
  }

  deleteModule(module: IModule) {
    return axiosInstance.delete(`${this.url()}/${module.id}`);
  }

  deleteItem(id: any) {
    return axiosInstance.delete(`${this.url()}/${id}`);
  }

  updateModuleSetting(id: any, data: any) {
    return axiosInstance.post(`${this.url()}/${id}/settings`, data)
      .then((response) => response.data);
  }

  fetchModuleSetting(id: any) {
    return axiosInstance.get(`${this.url()}/${id}/settings`)
      .then((response) => response.data);
  }

  findModuleById(moduleId: number | string) {
    return this.items.find((id: any) => Number(id) === Number(moduleId));
  }
}
