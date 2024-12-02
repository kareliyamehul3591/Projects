import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
const URL: string = '/settings';
const ICON: string = '/icons';
let cancel: any;

export class Setting extends Model<ISetting> implements ISetting {
  value!: any;
  key!: string;
  id!: number;
  type?: string;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{id: null, key: '', value: ''}, ...attributes}, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

export default class SettingCollection extends Collection<Setting> {

  model(): Constructor<Setting> {
    return Setting;
  }

  url(): string {
    return URL;
  }

  icons(): string {
    return ICON;
  }

  /*
   * Patch
   */
  update(id: any, data: any) {
    return axiosInstance.post(`${this.url()}/${id}`, {data})
      .then((response) => response.data)
      .catch((error) => error);
  }

  updateDefaultBG(data: any) {
    return axiosInstance.post(`${this.url()}/defaultBG`, {data})
      .then((response) => response.data)
      .catch((error) => error);
  }

  // system setting of configuration
  getSettings() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data)
      .catch((error) => error);
  }

  // system setting of configuration
  getKeySetting(name: any) {
    return axiosInstance.get(`${this.url()}/key/${name}`)
      .then((response) => response.data)
      .catch((error) => error);
  }

  // system logo dynamic
  getSystemLogo() {
    return axiosInstance.get(`${this.url()}/logo`)
      .then((response) => response.data)
      .catch((error) => error);
  }

  /**
   * get default dashboard
   */
  getDashboard(name: any) {
    return axiosInstance.get(`${this.url()}/default/${name}/dashboard`)
      .then((response) => response.data)
      .catch((error) => error);
  }

  /**
   * set default dashboard
   */
  setDashboard(id: any) {
    return axiosInstance.post(`${this.url()}/default/dashboard/${id}`)
      .then((response) => response)
      .catch((error) => error);
  }

  fetchIcon(index: number | null, search: string | null, perpage: number | null) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    if (search) {
      return axiosInstance.get(`${this.icons()}?search=${search}&page=${index}&perpage=${perpage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.icons()}?page=${index}&perpage=${perpage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data);
    }
  }

  createIcon(data: any) {
    return axiosInstance.post(`${this.icons()}`, data)
      .then((response) => response)
      .catch((error) => error);
  }

  getIcon(id: any) {
    return axiosInstance.get(`${this.icons()}/${id}`)
      .then((response) => response.data)
      .catch((error) => error);
  }

  deleteIcon(ids: any) {
    return axiosInstance.delete(`${this.icons()}/${ids}`)
      .then((response) => response.data);
  }
}
