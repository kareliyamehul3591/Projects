import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/interests';

export class Interests extends Model<IInterests> implements IInterests {

  id!: number | null;
  active!: boolean;
  description!: number | null;
  imageId!: number | null;
  name!: string;
  sort!: number;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{id: null, name: '', description: '', imageId: '', active: '', sort: ''}, ...attributes}, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }

}

export default class InterestsCollection extends Collection<Interests> {

  routeName = 'interests';

  model(): Constructor<Interests> {
    return Interests;
  }

  url(): string {
    return URL;
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

  /*
   * Patch
   */
  updateItem(id: any, data: any) {
    return axiosInstance.patch(`${this.url()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  createNewItem(newInterests: IInterests) {
    return axiosInstance.post(`${this.url()}`, newInterests)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }

  getDetail(interestsId: any) {
    return axiosInstance.get(`/interests/${interestsId}`)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  editInterests(id: any, interests: any) {
    return axiosInstance.put(`${this.url()}/${id}`, interests)
      .then((response) => response.data);
  }

  getSelectedInterests(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((reason) => console.log('error reasone', reason));
  }

  searchInterests(input: string, page: number, source: any, perPage: number | 10) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}&perPage=${perPage}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  getInterestsOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  getAllInterests() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getInterestsValidationRules() {
    return axiosInstance.get(`interests/validation`)
      .then((response) => response.data);
  }

  importInterests(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportInterests(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }
}
