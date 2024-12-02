import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axios, {AxiosResponse} from 'axios';
import axiosInstance from '@/helpers/axios';

export class MatomoSite extends Model<MatomoSite> implements IMatomoSite {

  id!: number;
  name!: string;
  mainUrl!: string;
  createdAt!: string;
  timezone!: string;
  currency!: string;
  type!: string;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{id: 0, name: '', sort: 0, active: false, channels: []}, ...attributes}, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return '';
  }

  // urlRoot(): string {
  //   // return URL;
  // }
}

export default class MatomoSiteCollection extends Collection<MatomoSite> {

  model(): Constructor<MatomoSite> {
    return MatomoSite;
  }

  url(): string {
    return '';
  }

  getTvWatchTime(url: any, id: any) {
    axios.create().get(`${url}/tv/watched/history/${id}`)
      .then((response) => {
        return response.data;
      });
  }

  getAnalytic() {
    return axiosInstance.get('/matomo/watched/history')
      .then((response) => {
        return response.data;
      })
      .catch((error) => error);
  }

  getHomeData(id: any = 1) {
    return axiosInstance.get(`/home?page=${id}`)
      .then((response) => {
        return response.data;
      }).catch((err) => console.log(err));
  }
}
