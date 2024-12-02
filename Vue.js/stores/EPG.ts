import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/epgs';

export class EPGs extends Model<IEpg> implements IEpg {

  id!: number;
  name!: string;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{id: null, name: ''}, ...attributes}, // Default values
      parentCollection,
    );
  }
  urlRoot(): string {
    return URL;
  }
}

export default class EPGCollection extends Collection<EPGs> {

  model(): Constructor<EPGs> {
    return EPGs;
  }

  url(): string {
    return URL;
  }

  getEPGNames(channelId: string, search: string | null) {

    if (cancel !== undefined) {
      cancel();
    }

    let url = this.url() + '?channel=' + channelId;
    if (search) {
      url = url + '&search=' + search;
    }

    return axiosInstance.get(url, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
      .then((response) => response.data)
      .catch((error) => error);
  }

  getEpgSources() {
    return axiosInstance.get(this.url() + '/sources', { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
      .then((response) => response.data)
      .catch((error) => error);
  }

  createEpgSource(source: IEpgSource) {
    return axiosInstance.post(`${this.url()}/sources/create`, { source: source.source, active: source.active })
    .then((response) => response.data);
  }

  updateEpgSource(source: IEpgSource) {
    return axiosInstance.post(`${this.url()}/sources/update`, { id: source.id, source: source.source, active: source.active })
    .then((response) => response.data);
  }

  deleteEpgSource(source: IEpgSource) {
    return axiosInstance.delete(`${this.url()}/sources/delete/${source.id}`)
    .then((response) => response.data);
  }

  // attachEPG(name: string) {
  //   return axiosInstance.post(`${this.url()}/attach`, name)
  //     .then((response) => response.data);
  // }
  importEpg(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportEpg(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }
}
