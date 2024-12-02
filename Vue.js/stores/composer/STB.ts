import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const URL: string = '/dashboards';
const elementURL: string = '/panel-elements';
const CancelToken: any = Axios.CancelToken;
let cancel: any;
export class Dashboards extends Model<IDashboards> implements IDashboards {
  deviceTypeId!: number;
  name!: string;
  type!: any;
  activePanel!: number;
  background!: number;
  redirectModuleId!: string;
  redirectLayout!: string;
  redirectAction!: string;
  redirectElementId!: string;
  active!: boolean;
  id!: number | null;
  panels!: IPanel[];

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          deviceTypeId: null,
          name: '',
          type: '',
          activePanel: null,
          background: null,
          active: false,
          redirectModuleId: '',
          redirectLayout: '',
          redirectAction: '',
          redirectElementId: '',
          panels: [],
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

// Class for all dashboard, Panel and its element operations
export default class DashboardsCollection extends Collection<Dashboards> {
  type = 'STB';
  routeName = 'stbDashboards';

  model(): Constructor<Dashboards> {
    return Dashboards;
  }

  // url for dashboard operations
  url(): string {
    return URL;
  }

  composerUrl(): string {
    return '/composer/stb';
  }

  elementUrl(): string {
    return '';
  }

  fetchData(index: number = 1, search: string | null, perpage: number | null, type = 'STB') {
    if (cancel !== undefined) {
      cancel();
    }

    if (search) {
      return axiosInstance.get(`${this.url()}?type=${type}&search=${search}&page=${index}&perpage=${perpage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.url()}?type=${type}&page=${index}&perpage=${perpage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data);
    }
  }

  /*
   * Dashboard panel operation area start
   */
  getDashboard(id: any) {
    return axiosInstance.get(`${this.composerUrl()}/${id}`)
      .then((response) => response.data);
  }

  createNewItem(newDashboard: IDashboards) {
    newDashboard.type = 'STB';
    return axiosInstance.post(`${this.url()}`, newDashboard)
      .then((response) => {
        return response.data;
      });
  }

  deleteItem(id: string) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  updateItem(dashboardId: any, data: any) {
    return axiosInstance.put(`${this.composerUrl()}/${dashboardId}`, data)
      .then((response) => {
        return response.data;
      });
  }

  create(data: any) {
    return axiosInstance.post(`${this.composerUrl()}`, data)
      .then((response) => response.data);
  }

  vodCategories(moduleId: any) {
    return axiosInstance.get(`vod/${moduleId}/categories`)
      .then((response) => response.data);
  }

  vodCategory(moduleId: any, categoryId: any) {
    return axiosInstance.get(`vod/${moduleId}/categories/${categoryId}`)
      .then((response) => response.data);
  }

  tvCategories(moduleId: any) {
    return axiosInstance.get(`tv/${moduleId}/categories`)
      .then((response) => response.data);
  }

  tvCategory(moduleId: any, categoryId: any) {
    return axiosInstance.get(`tv/${moduleId}/categories/${categoryId}`)
      .then((response) => response.data);
  }

  pagesCategories(moduleId: any) {
    return axiosInstance.get(`pages/${moduleId}/categories`)
      .then((response) => response.data);
  }

  pagesCategory(moduleId: any, categoryId: any) {
    return axiosInstance.get(`pages/${moduleId}/categories/${categoryId}`)
      .then((response) => response.data);
  }
}
