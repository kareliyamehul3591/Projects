import ItemsCollection, { Item } from './ItemsCollection';

import Axios from 'axios';
import Module from './Module';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;

export class RecorderModuleItem extends Item implements IRecorder {

  id!: number | null;
  name!: string;
  ip!: string;
  port!: number | null;
  type!: RecorderType;
  username!: string;
  password!: string;
  streams!: IStream[];
  }

export class RecorderItemsCollection extends ItemsCollection<RecorderModuleItem> {
    model() {
      return RecorderModuleItem;
    }

    url(): string {
      return this.module.url();
    }
  }

export default class RecorderModule extends Module<RecorderItemsCollection> {

  Collection() {
    return RecorderItemsCollection;
  }

  url(): string {
    return `/recorders/${this.id}`;
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

  getRecorders() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getSelectedRecorder(id: number) {
    return axiosInstance.get(`${this.url()}/recording/${id}`)
      .then((response) => response.data);
  }

  getRecordersOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  deleteRecordStream(recorderId: number, streamId: number) {
    return axiosInstance.delete(`${this.url()}/${recorderId}/${streamId}`)
    .then((response) => response.data);
  }

  deleteRecord(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
    .then((response) => response.data);
  }

  editRecorders(recorder: IRecorder) {
    return axiosInstance.put(`${this.url()}/${recorder.id}`, recorder)
      .then((response) => response.data);
  }

  createRecorders(recorder: IRecorder) {
    return axiosInstance.post(`${this.url()}`, recorder)
      .then((response) => response.data);
  }
  createRecorderStream(recorderId: number, stream: IStream) {
    return axiosInstance.post(`${this.url()}/${recorderId}`, stream)
      .then((response) => response.data);
  }
  search(input: string, page: number, source: any) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}`, {cancelToken: source.token})
      .then((response) => response.data);
  }
}
