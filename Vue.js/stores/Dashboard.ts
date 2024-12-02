import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import {MobileComposer} from '@/stores/DashboardComposer';

const CancelToken: any = Axios.CancelToken;
const URL: string = '/dashboards';
const elementURL: string = '/panel-elements';
let cancel: any;

// Model for dashboard backend data
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

// Model for dashboard panel
export class DashboardPanel extends Model<IPanel> implements IPanel {
  id!: number;
  dashboardId!: number;
  name!: string;
  showName!: boolean;
  showMore!: boolean;
  linkModuleId!: string;
  linkLayout!: string;
  linkAction!: string;
  linkElementId!: any;
  sort!: number;
  panelType!: string;
  position!: string;
  elementType!: string;
  dashboardBackground!: number;
  active!: boolean;
  elements!: IPanelElement[];

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          dashboardId: null,
          name: '',
          showName: true,
          showMore: true,
          active: false,
          linkModuleId: '',
          linkLayout: '',
          linkAction: '',
          linkElementId: '',
          sort: null,
          panelType: null,
          position: null,
          elementType: '',
          dashboardBackground: null,
          elements: [],
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

// Model for dashboard panel element
export class PanelElement extends Model<IPanelElement> implements IPanelElement {
  id!: number;
  dashboardPanelId!: number;
  name!: string;
  timebar!: boolean;
  linkModuleId!: number|string;
  linkLayout!: string;
  linkAction!: string;
  linkElementId!: number|string;
  dashboardBackground!: number;
  active!: boolean;
  description!: string;
  image: any;
  imageId!: number | null;
  sort!: any;
  videoLink!: any;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null, dashboardPanelId: null, name: '', active: false, timebar: false,
          linkModuleId: '', linkLayout: '', linkAction: '', linkElementId: '', description: '', sort: '',
          dashboardBackground: null, image: '', imageId: null, videoLink: '',
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

  model(): Constructor<Dashboards> {
    return Dashboards;
  }

  // url for dashboard operations
  url(): string {
    return URL;
  }

  elementUrl(): string {
    return elementURL;
  }

  /*
   * Dashboard operation area start
   */
  fetchData(index: number | null, search: string | null, perpage: number | null, type: any | null) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
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

  update(id: any, data: any) {
    return axiosInstance.patch(`${this.url()}/${id}`, data).then((response) => response.data)
      .catch((error) => error);
  }

  createNewItem(newDashboard: IDashboards) {
    return axiosInstance.post(`${this.url()}`, newDashboard)
      .then((response) => response.data);
  }

  deleteDashboard(id: string) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }
  getDashboardValidationRules() {
    return axiosInstance.get(`dashboard/validation`)
      .then((response) => response.data);
  }
  /*
   * Dashboard operation area end
   */

  /*
   * Dashboard panel operation area start
   */
  getDashboard(id: string) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  createPanel(newPanel: IPanel, id: any) {
    return axiosInstance.post(`${this.url()}/${id}/panel`, newPanel)
      .then((response) => response.data);
  }

  getDashboardPanel(id: string) {
    return axiosInstance.get(`${this.url()}/panels/${id}`)
    .then((response) => response.data);
  }

  updatePanel(id: any, data: any) {
    return axiosInstance.put(`${this.url()}/panels/${id}`, data)
    .then((response) => response.data);
  }

  deletePanel(id: string) {
    return axiosInstance.delete(`${this.url()}/panels/${id}`)
      .then((response) => response.data);
  }
  getDashboardPanelValidationRules() {
    return axiosInstance.get('dashboard/panel/validation')
      .then((response) => response.data);
  }
  /*
   * Dashboard panel operation area end
   */

  /*
  * Dashboard panel element operation area start
  */
  createPanelElement(newElement: IPanelElement, id: any) {
    return axiosInstance.post(`${this.elementUrl()}/${id}/panel`, newElement)
      .then((response) => response.data);
  }

  getPanelElement(id: string) {
    return axiosInstance.get(`${this.elementUrl()}/${id}`)
      .then((response) => response.data);
  }

  updatePanelElement(id: any, data: any) {
    return axiosInstance.patch(`${this.elementUrl()}/${id}`, data)
      .then((response) => response.data);
  }

  deletePanelElement(id: string) {
    return axiosInstance.delete(`${this.elementUrl()}/${id}`)
      .then((response) => response.data);
  }
  getPanelElementValidationRules() {
    return axiosInstance.get('dashboard/panel/element/validation')
      .then((response) => response.data);
  }
  /*
   * Dashboard panel element operation area end
   */
}
