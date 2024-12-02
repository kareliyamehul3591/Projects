import ItemsCollection, { Item } from './ItemsCollection';

import Module from './Module';
import axiosInstance from '@/helpers/axios';

export class TranscoderStreamItem extends Item implements ITranscoderStream {

  id!: number | null;
  name!: string;
  hardware!: ITranscoderStreamHardware;
  in!: ITranscoderStreamIn;
  out!: ITranscoderStreamOut[];
  slug!: string;
  active!: boolean;
  log!: any[];

}

export class TranscoderStreamsCollection extends ItemsCollection<TranscoderStreamItem> {
  model() {
    return TranscoderStreamItem;
  }

  url(): string {
    return this.module.url();
  }
}

export default class StreamModule extends Module<TranscoderStreamsCollection> {

  url() {
    return `/transcoder/${this.id}/streams`;
  }

  getStreams() {
    return axiosInstance.get(this.url())
      .then((response) => response.data);
  }

  getSelectedStream(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  search(input: string, page: number, source: any) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  deleteStream(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  createStream(stream: ITranscoderStream) {
    return axiosInstance.post(`${this.url()}`, stream)
      .then((response) => response.data);
  }

  saveEditedStream(stream: ITranscoderStream) {
    return axiosInstance.put(`${this.url()}/${stream.id}`, stream)
      .then((response) => response.data);
  }

  getStreamsOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }
}
