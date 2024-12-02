import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/admins';

export class Admin extends Model<IAdmin> implements IAdmin {
  static getById(id: string): Promise<Admin> {
    return axiosInstance.get(`${URL}/${id}`)
      .then((response) => {
        return new Admin(response.data);
      });
  }

  email!: string;
  status!: number;
  id!: number | null;
  name!: string;
  password!: string;
  passwordConfirmation!: string;
  username!: string;
  neighbours!: any;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      // {...{id: 0, email: '', enabled: false, languageId: 0}, ...attributes}, // Default values
      {...{id: 0, email: '', status: false, password: '', passwordConfirm: null, username: ''}, ...attributes}, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

export default class AdminCollection extends Collection<Admin> {

  model(): Constructor<Admin> {
    return Admin;
  }

  url(): string {
    return URL;
  }

  update(admin: any) {
    delete admin._original;
    return axiosInstance.patch(`${this.url()}/${admin.id}`, admin)
      .then((response) => response.data);
  }

  edit(admin: IAdmin) {
    return axiosInstance.patch(`${this.url()}/${admin.id}`, admin)
      .then((response) => response.data);
  }

  changePassword(id: number, admin: IAdmin) {
    return axiosInstance.patch(`${this.url()}/${id}`, {password: admin.password})
      .then((response) => response.data);
  }

  delete(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  create(admin: IAdmin) {
    return axiosInstance.post(this.url(), admin)
    .then((response) => response.data);
  }

  getAll(index: number | null, search: string | null, perpage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    if (search) {
      return axiosInstance.get(`${this.url()}?search=${search}&page=${index}&perpage=${perpage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
          .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.url()}?page=${index}&perpage=${perpage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data);
    }
  }

  getAdminValidationRules() {
    return axiosInstance.get(`admin/validation`)
      .then((response) => response.data);
  }
}
