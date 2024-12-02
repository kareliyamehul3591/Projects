import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/gfm';

// @ts-ignore
export class GuestFlow extends Model<IGuestFlowModule> implements IGuestFlowModule {

  id!: number | null;
  name!: string;
  type!: string;
  description!: string;
  active!: boolean;
  sort!: number | null;
  imageId!: number | null;
  qrimageId!: number | null;
  licenseKey!: string;
  accommodationLibraries!: ILibrary[];

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
          accommodationLibraries: [],
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }

}

export default class GuestFlowCollection extends Collection<GuestFlow> {

  routeName = 'guestflowmodule';

  model(): Constructor<GuestFlow> {
    return GuestFlow;
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
    return axiosInstance.put(`${this.url()}/${id}`, data)
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

  getDetail(guestFlowModuleId: any) {
    return axiosInstance.get(`${this.url()}/${guestFlowModuleId}`)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  editGuestFlowModule(id: any, guestFlowModule: any) {
    return axiosInstance.put(`${this.url()}/${id}`, guestFlowModule)
      .then((response) => response.data);
  }

  getSelectedGuestFlowModule(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((reason) => console.log('error reasone', reason));
  }

  getGuestFlowModuleValidationRules() {
    return axiosInstance.get(`guestFlowModule/validation`)
      .then((response) => response.data);
  }

  importGuestFlowModule(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportGuestFlowModule(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }

  listofServices() {
    return axiosInstance.get(`${this.url()}?page=${1}&perpage=${100}`, {
      cancelToken: new CancelToken(function executor(c: any) {
        cancel = c;
      }),
    })
      .then((response) => response.data.data);
  }
}
