import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/messages';

export class Messages extends Model<IMessage> implements IMessage {

  id!: number;
  title!: string;

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

export default class MessageCollection extends Collection<Messages> {

  model(): Constructor<Messages> {
    return Messages;
  }

  url(): string {
    return URL;
  }

  getEPGNames(channelId: string, search: string | null) {
    console.log('TODO');
  }

  getMessages() {
    return axiosInstance.get(this.url(), { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
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

}
