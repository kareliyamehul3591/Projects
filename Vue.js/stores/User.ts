import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/users';

export class Users extends Model<IUser> implements IUser {

  id!: number | null;
  erpId!: string;
  name!: string;
  username!: string;
  email!: string;
  password!: string;
  passwordConfirmation!: string;
  dashboardId!: string|number;
  mobileDashboardId!: string|number;
  pin!: string|number;
  adult!: boolean;
  pinRequired!: boolean;
  accounts!: IAccount[];
  dashboard!: IDashboard | null;
  mobileDashboard!: IDashboard | null;
  customer!: ICustomer | null;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          erpId: '',
          name: '',
          username: '',
          email: '',
          password: '',
          dashboardId: '',
          adult: false,
          pinRequired: false,
          accounts: [],
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

export default class UsersCollection extends Collection<Users> {

  routeName = 'users';

  model(): Constructor<Users> {
    return Users;
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

  createNewItem(newUser: IUser) {
    return axiosInstance.post(`${this.url()}`, newUser)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  attachAccounts(idList: number[], userId: number) {
    return axiosInstance.post(`${this.url()}/${userId}/accounts/attach`, {ids: idList})
      .then((response) => response.data);
  }

  attachDashboard(userId: any, dashboardId: any) {
    return axiosInstance.post(`${this.url()}/${userId}/dashboard/${dashboardId}/attach`)
      .then((response) => response.data);
  }

  attachMobileDashboard(userId: any, dashboardId: any) {
    return axiosInstance.post(`${this.url()}/${userId}/dashboard/${dashboardId}/attachMobile`)
      .then((response) => response.data);
  }

  detachDashboard(userId: any) {
    return axiosInstance.post(`${this.url()}/${userId}/dashboard/detach`)
      .then((response) => response.data);
  }

  detachMobileDashboard(userId: any) {
    return axiosInstance.post(`${this.url()}/${userId}/dashboard/mobileDetach`)
      .then((response) => response.data);
  }

  searchUserAccounts(userId: any, keyword: string) {
    return axiosInstance.post(`${this.url()}/${userId}/${keyword}/account`)
      .then((response) => response.data);
  }
  attachCustomer(idList: number[], userId: number) {
    return axiosInstance.post(`${this.url()}/${userId}/customers/attach`, {ids: [idList]})
      .then((response) => response.data);
  }

  detachCustomers(idList: number[], userId: number) {
    return axiosInstance.post(`${this.url()}/${userId}/customers/detach`, {ids: idList})
      .then((response) => response.data);
  }
  detachAccount(user: IUser, account: IAccount) {
    return axiosInstance.get(`${this.url()}/${user.id}/${account.id}/accounts/detach`)
      .then((response) => response.data);
  }

  updateItem(id: any, user: any) {
    return axiosInstance.put(`${this.url()}/${id}`, user)
      .then((response) => response.data);
  }

  getSelectedUser(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((reason) => console.log('error reasone', reason));
  }

  searchUsers(input: string, page: number, source: any, perPage: number | 10) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}&perPage=${perPage}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  getUsersOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  getAllUsers() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getUserValidationRules() {
    return axiosInstance.get(`user/validation`)
      .then((response) => response.data);
  }

  importUsers(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportUsers(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }
}
