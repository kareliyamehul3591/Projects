import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/promotions';

export class Promotions extends Model<IPromotions> implements IPromotions {

  id!: number | null;
  active!: boolean;
  description!: string;
  imageId!: number | null;
  name!: string;
  sort!: number | null;
  validFrom!: string;
  validTo!: string;

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
          validFrom: '',
          validTo: '',
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }

}

export default class PromotionsCollection extends Collection<Promotions> {

  routeName = 'promotions';

  model(): Constructor<Promotions> {
    return Promotions;
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
    return axiosInstance.patch(`${this.url()}/${id}`, data)
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
    return axiosInstance.post(`promotions/${promotionId}/customers/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachCustomers(idList: [], promotionId: any) {
    return axiosInstance.post(`promotions/${promotionId}/customers/detach`, {ids: [idList]})
      .then((response) => response.data);
  }

  getDetail(promotionsId: any) {
    return axiosInstance.get(`/promotions/${promotionsId}`)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  attachAccounts(idList: number[], promotionsId: number) {
    return axiosInstance.post(`${this.url()}/${promotionsId}/accounts/attach`, {ids: idList})
      .then((response) => response.data);
  }

  attachDashboard(promotionsId: any, dashboardId: any) {
    return axiosInstance.post(`${this.url()}/${promotionsId}/dashboard/${dashboardId}/attach`)
      .then((response) => response.data);
  }

  detachDashboard(promotionsId: any) {
    return axiosInstance.post(`${this.url()}/${promotionsId}/dashboard/detach`)
      .then((response) => response.data);
  }

  searchpromotionsAccounts(promotionsId: any, keyword: string) {
    return axiosInstance.post(`${this.url()}/${promotionsId}/${keyword}/account`)
      .then((response) => response.data);
  }

  detachAccount(promotions: IPromotions, account: IAccount) {
    return axiosInstance.get(`${this.url()}/${promotions.id}/${account.id}/accounts/detach`)
      .then((response) => response.data);
  }

  editPromotions(id: any, promotions: any) {
    return axiosInstance.put(`${this.url()}/${id}`, promotions)
      .then((response) => response.data);
  }

  getSelectedPromotions(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((reason) => console.log('error reasone', reason));
  }

  searchPromotions(input: string, page: number, source: any, perPage: number | 10) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}&perPage=${perPage}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  getPromotionsOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  getAllPromotions() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getPromotionsValidationRules() {
    return axiosInstance.get(`promotions/validation`)
      .then((response) => response.data);
  }

  importPromotions(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportPromotions(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }

  listofPromotions() {
    return axiosInstance.get(`${this.url()}?page=${1}&perpage=${100}`, {
      cancelToken: new CancelToken(function executor(c: any) {
        cancel = c;
      }),
    })
      .then((response) => response.data.data);
  }
}
