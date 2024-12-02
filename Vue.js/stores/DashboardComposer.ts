import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
const URL: string = '/composer';
const mobileURL: string = URL + '/mobile';

// Model for dashboard backend data
export class MobileComposer extends Model<IDashboards> implements IDashboards {
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
export default class MobileComposerCollection extends Collection<MobileComposer> {

  model(): Constructor<MobileComposer> {
    return MobileComposer;
  }

  // url for dashboard operations
  url(): string {
    return URL;
  }

  baseUrl(): string {
    return mobileURL;
  }

  test(data: any) {
    return axiosInstance.post(`${this.baseUrl()}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }
}
