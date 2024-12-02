import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/rating-platform';
const socialURL: string = '/social-media-platform';
const commentProposals: string = '/comment-proposal';

export class RatingPlatform extends Model<IMedia> implements IMedia {

  id!: number | null;
  name!: string;
  description!: string;
  active!: boolean;
  sort!: number | null;
  imageId!: number | null;
  webUrl!: string;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          active: '',
          description: '',
          imageId: '',
          name: '',
          sort: '',
          webUrl: '',
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }

}

export default class RatingPlatformCollection extends Collection<RatingPlatform> {

  routeName = 'ratingPlatform';

  model(): Constructor<RatingPlatform> {
    return RatingPlatform;
  }

  url(): string {
    return URL;
  }

  socialURL(): string {
    return socialURL;
  }

  commentProposals(): string {
    return commentProposals;
  }

  fetchData(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    if (search) {
      return axiosInstance.get(`${this.url()}?search=${search}&page=${index}&perpage=${perPage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data.data);
    } else {
      return axiosInstance.get(`${this.url()}?page=${index}&perpage=${perPage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data.data);
    }
  }

  updateItem(id: any, data: any) {
    return axiosInstance.put(`${this.url()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  createNewItem(item: any) {
    return axiosInstance.post(`${this.url()}`, item)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }
}
