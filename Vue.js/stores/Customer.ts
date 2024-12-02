import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/customers';

export class Customer extends Model<ICustomer> implements ICustomer {

  id!: number | null;
  erpId!: number | null;
  isCompany!: boolean;
  company!: string;
  gender!: string;
  documentType!: string;
  salutation!: string;
  firstName!: string;
  lastName!: string;
  nationality!: string;
  dateOfBirth!: string;
  placeOfBirth!: string;
  identityNumber!: string;
  taxNumber!: string;
  active!: boolean;
  accounts!: IAccount[];
  addresses!: ICustomerAddress[];
  contacts!: IContact[];
  imageId!: number | null;
  image!: [];
  neighbours!: any;
  languages!: any;
  documents!: IDocument[];

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          accounts: [],
          active: false,
          addresses: [],
          documents: [],
          company: '',
          contacts: [],
          dateOfBirth: '',
          erpId: 0,
          firstName: '',
          gender: '',
          id: -1,
          identityNumber: '',
          isCompany: false,
          lastName: '',
          name: '',
          nationality: '',
          placeOfBirth: '',
          salutation: '',
          taxNumber: '',
          imageId: null,
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

export default class CustomerCollection extends Collection<Customer> {

  routeName = 'customers';

  model(): Constructor<Customer> {
    return Customer;
  }

  url(): string {
    return URL;
  }

  /*
   * Get list
   */
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

    return axiosInstance.get(url, {
      cancelToken: new CancelToken(function executor(c: any) {
        cancel = c;
      }),
    })
      .then((response) => response.data);
  }

  deleteCustomerContact(contact: IContact) {
    return axiosInstance.delete(`${this.url()}/${contact.customerId}/contacts/${contact.id}`)
      .then((response) => response.data);
  }

  addCustomerContact(contact: IContact) {
    return axiosInstance.post(`${this.url()}/${contact.customerId}/contacts`, contact)
      .then((response) => response.data);
  }

  editCustomerContact(contact: IContact) {
    return axiosInstance.put(`${this.url()}/${contact.customerId}/contacts/${contact.id}`, contact)
      .then((response) => response.data);
  }

  attachAccounts(customerId: number, accountId: number[]) {
    return axiosInstance.post(`${this.url()}/${customerId}/accounts/attach`, {accountId})
      .then((response) => {
        return response.data;
      });
  }

  attachUser(selectedUser: number[], customerId: number) {
    return axiosInstance.post(`${this.url()}/${customerId}/user/attach`, {ids: [selectedUser]})
      .then((response) => {
        return response.data;
      });
  }

  detachUser(selectedUser: number[], customerId: number) {
    return axiosInstance.post(`${this.url()}/${customerId}/user/detach`, {ids: [selectedUser]})
      .then((response) => {
        return response.data;
      });
  }

  importCustomers(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportCustomers(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }

  /*
   * Get customer
   */
  getById(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((error) => error);
  }

  /*
   * post
   */
  saveDocuments(id: any, data: any) {
    return axiosInstance.post(`${this.url()}/${id}/documents`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  /*
   * Patch
   */
  UpdateDocuments(id: any, data: any, documentId: any) {
    return axiosInstance.post(`${this.url()}/${id}/documents/${documentId}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  /*
   * Patch
   */
  updateItem(id: any, data: any) {
    return axiosInstance.patch(`${this.url()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }

  searchCustomerAddresses(id: number, query: string) {
    return axiosInstance.get(`${this.url()}/${id}/addresses?query=${query}`)
      .then((response) => response.data);
  }

  getAllCustomerAccounts(id: number) {
    return axiosInstance.get(`${this.url()}/${id}/accounts`)
      .then((response) => response.data);
  }

  searchCustomerAccounts(id: number, query: string) {
    return axiosInstance.get(`${this.url()}/${id}/accounts?query=${query}`)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  saveCustomer(customer: ICustomer) {
    return axiosInstance.put(`${this.url()}/${customer.id}`, customer)
      .then((response) => response.data);
  }

  createCustomer(customer: ICustomer) {
    return axiosInstance.post(`${this.url()}`, customer)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }

  detachAccount(customer: ICustomer, account: IAccount) {
    return axiosInstance.post(`${this.url()}/${customer.id}/accounts/detach`, {id: account.id})
      .then((response) => response.data);
  }

  getCustomerValidationRules() {
    return axiosInstance.get(`customer/validation`)
      .then((response) => response.data);
  }

  attachLanguage(idList: number[], customerId: number) {
    return axiosInstance.post(`${this.url()}/${customerId}/languages/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachLanguage(account: ICustomer, language: ILanguage) {
    return axiosInstance.post(`${this.url()}/${account.id}/languages/detach`, language)
      .then((response) => response.data);
  }

  getAutocompleteCategories(query: string, source: any) {
    return axiosInstance.get(`/interests`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  attachInterest(selectedCategory: any, customerId: number) {
    return axiosInstance.post(`customers/${customerId}/interests/attach`, {interests: selectedCategory})
      .then((response) => response.data);
  }

  detachInterest(selectedCategory: any, customerId: number) {
    return axiosInstance.post(`customers/${customerId}/interests/detach`, {interests: selectedCategory})
      .then((response) => response.data);
  }

  attachPromotions(idList: number[], customerId: any) {
    return axiosInstance.post(`customers/${customerId}/promotions/attach`, {ids: idList})
      .then((response) => response.data);
  }

  detachPromotions(idList: number[], customerId: any) {
    return axiosInstance.post(`customers/${customerId}/promotions/detach`, {ids: idList})
      .then((response) => response.data);
  }
}
