import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/property/assets';
const URLCATEGORY: string = '/asset/categories';
export class Assets extends Model<IPropertyAssets> implements IPropertyAssets {

  id!: number | null;
  active!: boolean;
  description!: string;
  imageId!: number | null;
  name!: string ;
  sort!: number | null;
  address!: string;
  validToccRequiredAtCheckin!: string;
  defaultErFrequency!: string;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{id: null, active: '', description: '', imageId: '', name: '', sort: '', validFrom: '', validTo: ''}, ...attributes}, // Default values
      parentCollection,
    );
  }
  urlRoot(): string {
    return URL;
  }
  urlcategory(): string {
    return URLCATEGORY;
  }
}

export default class AssetsCollection extends Collection<Assets> {

  routeName = 'assets';

  model(): Constructor<Assets> {
    return Assets;
  }

  url(): string {
    return URL;
  }
  urlcategory(): string {
    return URLCATEGORY;
  }

  fetchData(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    let url = this.url() + '?page=' + index + '&perpage=' + perPage;
    if (search) {
      url = url + '&search=' + search;
    }

    return axiosInstance.get(url, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
      .then((response) => response.data.data);
  }

  fetchCategory(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    let url = this.urlcategory() + '?page=' + index + '&perpage=' + perPage;
    if (search) {
      url = url + '&search=' + search;
    }

    return axiosInstance.get(url, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
      .then((response) => response.data.data);
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
  createNewCategory(item: any) {
    return axiosInstance.post(`${this.urlcategory()}`, item)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }
  attachCustomer(idList: number[], promotionId: any) {
    return axiosInstance.post(`assets/${promotionId}/customers/attach`, {ids: idList})
      .then((response) => response.data);
  }
  detachCustomers(idList: [], promotionId: any) {
    return axiosInstance.post(`assets/${promotionId}/customers/detach`, {ids: [idList]})
      .then((response) => response.data);
  }
  getDetail(assetsId: any) {
    return axiosInstance.get(`/property/assets/${assetsId}`)
      .then((response) => response.data.data);
  }
  attachGallery(id: number, imageId: number) {
    return axiosInstance.post(`/property/${id}/media/attach`, {ids: [imageId]})
      .then((response) => response.data);
  }
  detachGallery(id: number, imageId: number) {
    return axiosInstance.post(`/property/${id}/media/detach`, {ids: [imageId]})
      .then((response) => response.data);
  }
  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }
  updateAssetsCategory(id: number, data: any) {
    return axiosInstance.put(`${this.urlcategory()}/${id}`, data)
      .then((response) => response.data);
  }
  getAssetsCategory(id: number) {
    return axiosInstance.get(`${this.urlcategory()}/${id}`)
      .then((response) => response.data);
  }
  deleteAssetsCategory(id: number) {
    return axiosInstance.delete(`${this.urlcategory()}/${id}`)
      .then((response) => response.data);
  }

  attachAccounts(idList: number[], assetsId: number) {
    return axiosInstance.post(`${this.url()}/${assetsId}/accounts/attach`, {ids: idList})
      .then((response) => response.data);
  }
  attachAccomodation(idList: number[], zoneId: any) {
    return axiosInstance.post(`property/${zoneId}/accommodation/attach`, {ids: idList})
      .then((response) => response.data);
  }
  attachLocation(idList: number[], assetId: any) {
    return axiosInstance.post(`property/${assetId}/locations/attach`, {ids: idList})
      .then((response) => response.data);
  }
  attachAssets(idList: number[], accId: any) {
    return axiosInstance.post(`accommodation/${accId}/property/attach`, {ids: idList})
      .then((response) => response.data);
  }
  detachAccomodation(idList: number[], zoneId: any) {
    return axiosInstance.post(`property/${zoneId}/accommodation/detach`, {ids: [idList]})
      .then((response) => response.data);
  }
  attachDashboard(assetsId: any, dashboardId: any) {
    return axiosInstance.post(`${this.url()}/${assetsId}/dashboard/${dashboardId}/attach`)
      .then((response) => response.data);
  }
  detachDashboard(assetsId: any) {
    return axiosInstance.post(`${this.url()}/${assetsId}/dashboard/detach`)
      .then((response) => response.data);
  }
  searchassetsAccounts(assetsId: any, keyword: string) {
    return axiosInstance.post(`${this.url()}/${assetsId}/${keyword}/account`)
      .then((response) => response.data);
  }

  detachAccount(assets: IPropertyAssets, account: IAccount) {
    return axiosInstance.get(`${this.url()}/${assets.id}/${account.id}/accounts/detach`)
      .then((response) => response.data);
  }

  editAssets(id: any, assets: any) {
    return axiosInstance.put(`${this.url()}/${id}`, assets)
      .then((response) => response.data);
  }

  getSelectedAssets(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((reason) => console.log('error reasone', reason));
  }

  searchAssets(input: string, page: number, source: any, perPage: number | 10) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}&perPage=${perPage}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  getAssetsOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  getAllAssets() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getAssetsValidationRules() {
    return axiosInstance.get(`assets/validation`)
      .then((response) => response.data);
  }

  importAssets(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportAssets(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }
}
