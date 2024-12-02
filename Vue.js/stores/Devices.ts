import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/devices';

export class Devices extends Model<IDevice> implements IDevice {

  id!: number | null;
  type!: string;
  serialNumber!: string;
  ip!: string;
  macEth!: string;
  macWifi!: string;
  firmware!: string;
  apk!: string;
  manufacturer!: string;
  model!: string;
  switchMac!: string;
  switchIp!: string;
  timeZone!: string;
  name!: string;
  locationId!: number | null;
  deviceTypeId!: number | null;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{id: 0, type: '', serialNumber: '', ip: '', mac: '', firmware: '', apk: '', manufacturer: '', model: '', switchMac: '', switchIp: '', locationId: null, name: '', timeZone: ''}, ...attributes}, // Default values
      parentCollection,
    );
  }
  urlRoot(): string {
    return URL;
  }
}

export default class DevicesCollection extends Collection<Devices> {

  routeName = 'devices'; // same as routers base name

  model(): Constructor<Devices> {
    return Devices;
  }

  url(): string {
    return URL; // nared še eno metodo za type
  }

  updateDeviceType(id: any, data: any) {
    return axiosInstance.put(`${this.url()}/types/${id}`, data)
      .then((response) => response.data);
  }

  updateItem(id: any, data: any) {
    return axiosInstance.patch(`${this.url()}/${id}`, data)
      .then((response) => response.data);
  }

  fetchDeviceTypesData(index: number | 1, search: string | null, perpage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }
    if (index === null) {
      index = 1;
    }
    if (search) {
      return axiosInstance.get(`${this.url()}/types?search=${search}&page=${index}&perpage=${perpage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
          .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.url()}/types?page=${index}&perpage=${perpage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data);
    }
  }

  fetchData(index: number | null, search: string | null, perpage: number | 10) {
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

  detachAccount(accountID: number, deviceID: number) {
    return axiosInstance.patch(`${this.url()}/${deviceID}/account/detach`, accountID)
      .then((response) => response.data);
  }

  createNewDevice(device: IDevice) {
    return axiosInstance.post(`${this.url()}`, device)
      .then((response) => response.data);
  }

  getAllDevices() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getDevicesOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  saveDeviceEdit(device: any) {
    return axiosInstance.put(`${this.url()}/${device.id}`, device)
      .then((response) => response.data);
  }

  getSelectedDevice(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  getAccountForDevice(id: number) {
    return axiosInstance.get(`${this.url()}/${id}/account`)
      .then((response) => response.data);
  }

  getAllDeviceTypes() {
    return axiosInstance.get(`${this.url()}/types`)
      .then((response) => response.data);
  }

  getAllDeviceTypesFull() {
    return axiosInstance.get(`${this.url()}/types/all`)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
    .then((response) => response.data);
  }

  deleteItems(ids: number[]) {
    return axiosInstance.post(`${this.url()}/delete`, ids)
    .then((response) => response.data);
  }

  attachAccount(accId: number, devId: number) {
    return axiosInstance.patch(`${this.url()}/${devId}/account/attach`, accId)
      .then((response) => response.data);
  }
  attachDashboard(deviceId: any, dashboardId: any) {
    return axiosInstance.post(`${this.url()}/${deviceId}/dashboard/${dashboardId}/dashbordAttach`)
      .then((response) => response.data);
  }

  createDeviceType(type: IDeviceType) {
    return axiosInstance.post(`${this.url()}/types`, type)
    .then((response) => response.data);
  }

  getSelectedDeviceType(id: number) {
    return axiosInstance.get(`${this.url()}/types/${id}`)
      .then((response) => response.data);
  }

  putDeviceType(type: IDeviceType) {
    return axiosInstance.put(`${this.url()}/types/${type.id}`, type)
      .then((response) => response.data);
  }

  deleteDeviceType(id: number) {
    return axiosInstance.delete(`${this.url()}/types/${id}`)
      .then((response) => response.data);
  }

  getDeviceValidationRules() {
    return axiosInstance.get(`device/validation`)
      .then((response) => response.data);
  }
}
