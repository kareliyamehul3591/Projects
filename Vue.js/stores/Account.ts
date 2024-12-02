import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/accounts';

export class Accounts extends Model<IAccount> implements IAccount {
  erpId!: string;
  name!: string;
  activationCode!: string;
  welcomeScreen!: boolean;
  active!: boolean;
  customerId!: number | null;
  id!: number | null;
  customer!: ICustomer | null;
  profiles!: IProfile[];
  users!: IUser[];
  devices!: IDevice[];
  deviceTypeId!: number;
  deviceType!: IDeviceType;
  deviceLimitStb!: number;
  deviceLimitMobile!: number;
  languages!: ILanguage[];

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          erpId: '',
          name: '',
          activationCode: '',
          welcomeScreen: false,
          active: false,
          customerId: null,
          customer: null,
          profiles: [],
          users: [],
          devices: [],
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

export default class AccountsCollection extends Collection<Accounts> {

  routeName = 'accounts';

  model(): Constructor<Accounts> {
    return Accounts;
  }

  url(): string {
    return URL;
  }

  /*
   * Patch
   */
  updateItem(id: any, data: any) {
    return axiosInstance.patch(`${this.url()}/${id}`, data)
      .then((response) => response.data);
  }

  fetchData(index: number | null, search: string | null, perpage: number | null) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    if (search) {
      return axiosInstance.get(`${this.url()}?search=${search}&page=${index}&perpage=${perpage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.url()}?page=${index}&perpage=${perpage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data);
    }
  }

  // resetPin(profile: IProfile) {
  //   return axiosInstance.patch(`${this.url()}/${profile.accountId}/profiles/${profile.id}`, profile)
  //   .then((response) => response.data);
  // }

  detachUser(account: IAccount, user: IUser) {
    return axiosInstance.post(`${this.url()}/${account.id}/users/detach`, user)
      .then((response) => response.data);
  }

  attachUser(idList: number[], accId: number) {
    return axiosInstance.post(`${this.url()}/${accId}/users/attach`, {ids: idList})
      .then((response) => response.data);
  }

  languages(accountId: number, index: any, perPage: any, search: any) {
    return axiosInstance.get(`${this.url()}/${accountId}/languages?page=${index}&perpage=${perPage}&search=${search}`)
      .then((response) => response.data);
  }

  updateLanguageStatus(accountId: number, languageId: number) {
    return axiosInstance.post(`${this.url()}/${accountId}/languages/default/${languageId}`)
      .then((response) => response.data);
  }

  attachLanguage(idList: number[], accId: number) {
    return axiosInstance.post(`${this.url()}/${accId}/languages/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachLanguage(account: IAccount, language: ILanguage) {
    return axiosInstance.post(`${this.url()}/${account.id}/languages/detach`, language)
      .then((response) => response.data);
  }

  createAccountProfile(profile: IProfile) {
    return axiosInstance.post(`${this.url()}/${profile.accountId}/profiles`, profile)
      .then((response) => response.data);
  }

  deleteAccountProfile(accId: number, proId: number) {
    return axiosInstance.delete(`${this.url()}/${accId}/profiles/${proId}`)
      .then((response) => response.data);
  }

  updateProfile(id: any, id2: any, data: any) {
    return axiosInstance.patch(`${this.url()}/${id}/profiles/${id2}`, data)
      .then((response) => response.data);
  }

  editAccountProfile(profile: IProfile) {
    return axiosInstance.patch(`${this.url()}/${profile.accountId}/profiles/${profile.id}`, profile)
      .then((response) => response.data);
  }

  attachCustomer(customerId: number, accountId: number) {
    return axiosInstance.post(`${this.url()}/${accountId}/customer/attach`, customerId)
     .then((response) => response.data);
  }

  deleteItem(id: string) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  getSelectedAccount(erpId: number) {
    return axiosInstance.get(`${this.url()}/${erpId}`)
      .then((response) => response.data);
  }

  editAccount(id: number, acc: any) {
    return axiosInstance.put(`${this.url()}/${id}`, acc)
      .then((response) => response.data);
  }

  createNewItem(newAccount: IAccount) {
    return axiosInstance.post(`${this.url()}`, newAccount)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }

  getAccountDevices(accID: string) {
    return axiosInstance.get(`${this.url()}/${accID}/devices`)
      .then((response) => response.data);
  }

  searchAccountsDevices(accID: string, input: string, page: number, source: any) {
    return axiosInstance.get(`${this.url()}/${accID}/devices?query=${input}&page=${page}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  attachDevice(idList: number[], accId: number) {
    return axiosInstance.post(`${this.url()}/${accId}/devices/attach`, idList)
      .then((response) => response.data);
  }

  detachCustomer(account: IAccount) {
    return axiosInstance.post(`${this.url()}/${account.id}/customer/detach`, account.customerId)
      .then((response) => response.data);
  }

  getAccountProfile(acc: number, prof: number) {
    return axiosInstance.get(`${this.url()}/${acc}/profiles/${prof}`)
      .then((response) => response.data);
  }

  getAccountValidationRules() {
    return axiosInstance.get(`account/validation`)
      .then((response) => response.data);
  }

  attachPackages(idList: number[], accId: number) {
    return axiosInstance.post(`${this.url()}/${accId}/packages/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachPackage(account: IAccount, packageItem: IPackage) {
    return axiosInstance.post(`${this.url()}/${account.id}/packages/detach`, packageItem)
      .then((response) => response.data);
  }

  getAccountPackages(accID: number | null) {
    return axiosInstance.get(`${this.url()}/${accID}/packages`)
      .then((response) => response.data);
  }

  getDeviceTypes() {
    return axiosInstance.get(`${this.url()}/devices/types`)
      .then((response) => response.data);
  }

  importAccounts(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportAccounts(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }

}
