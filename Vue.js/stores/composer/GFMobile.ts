import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
const URL: string = '/dashboards';
const elementURL: string = '/panel-elements';

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
  type = 'GFmobile';
  routeName = 'gFMobileDashboards';

  model(): Constructor<Dashboards> {
    return Dashboards;
  }

  // url for dashboard operations
  url(): string {
    return URL;
  }

  composerUrl(): string {
    return '/composer/mobile';
  }

  elementUrl(): string {
    return '';
  }

  /*
   * Dashboard panel operation area start
   */
  getDashboard(id: any) {
    return axiosInstance.get(`${this.composerUrl()}/${id}`)
      .then((response) => response.data);
  }

  update(data: any, dashboardId: any) {
    return axiosInstance.put(`${this.composerUrl()}/${dashboardId}`, data)
      .then((response) => response.data);
  }

  createNewDashboard(newDashboard: IDashboards) {
    newDashboard.type = 'GFMobile';
    return axiosInstance.post(`${this.url()}`, newDashboard)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }

}
