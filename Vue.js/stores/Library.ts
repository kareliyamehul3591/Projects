import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/accommodation/library';

export class Library extends Model<ILibrary> implements ILibrary {

  id!: number | null;
  name!: string;
  type!: string;
  description!: string;
  active!: boolean;
  sort!: number | null;
  imageId!: number | null;
  autoscrollSpeed!: string;
  path!: string;
  fileName!: string;
  filePath!: string;
  pmsId!: number;

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
          autoscrollSpeed: '',
          path: '',
          fileName: '',
          pmsId: '',
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }

}

export default class LibraryCollection extends Collection<Library> {

  routeName = 'library';

  model(): Constructor<Library> {
    return Library;
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

  updateItem(id: any, data: any) {
    return axiosInstance.post(`${this.url()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  createNewItem(item: any) {
    return axiosInstance.post(`${this.url()}`, item)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }

  attachCustomer(idList: number[], promotionId: any) {
    return axiosInstance.post(`library/${promotionId}/customers/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachCustomers(idList: [], promotionId: any) {
    return axiosInstance.post(`library/${promotionId}/customers/detach`, {ids: [idList]})
      .then((response) => response.data);
  }

  getDetail(libraryId: any) {
    return axiosInstance.get(`${this.url()}/${libraryId}`)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  attachAccounts(idList: number[], libraryId: number) {
    return axiosInstance.post(`${this.url()}/${libraryId}/accounts/attach`, {ids: idList})
      .then((response) => response.data);
  }

  attachDashboard(libraryId: any, dashboardId: any) {
    return axiosInstance.post(`${this.url()}/${libraryId}/dashboard/${dashboardId}/attach`)
      .then((response) => response.data);
  }

  detachDashboard(libraryId: any) {
    return axiosInstance.post(`${this.url()}/${libraryId}/dashboard/detach`)
      .then((response) => response.data);
  }

  searchlibraryAccounts(libraryId: any, keyword: string) {
    return axiosInstance.post(`${this.url()}/${libraryId}/${keyword}/account`)
      .then((response) => response.data);
  }

  detachAccount(library: ILibrary, account: IAccount) {
    return axiosInstance.get(`${this.url()}/${library.id}/${account.id}/accounts/detach`)
      .then((response) => response.data);
  }

  editLibrary(id: any, library: any) {
    return axiosInstance.put(`${this.url()}/${id}`, library)
      .then((response) => response.data);
  }

  getSelectedLibrary(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((reason) => console.log('error reasone', reason));
  }

  searchLibrary(input: string, page: number, source: any, perPage: number | 10) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}&perPage=${perPage}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  getLibraryOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  getAllLibrary() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getLibraryValidationRules() {
    return axiosInstance.get(`library/validation`)
      .then((response) => response.data);
  }

  importLibrary(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportLibrary(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }

  listofLibrary() {
    return axiosInstance.get(`${this.url()}?page=${1}&perpage=${100}`, {
      cancelToken: new CancelToken(function executor(c: any) {
        cancel = c;
      }),
    })
      .then((response) => response.data.data);
  }
}
