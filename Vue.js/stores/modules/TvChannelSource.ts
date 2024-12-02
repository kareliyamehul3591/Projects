import ItemsCollection, { Item } from './ItemsCollection';

import Module from './Module';
import axiosInstance from '@/helpers/axios';

export class TvChannelSourceItem extends Item implements IChannelSource {
  id!: number | null;
  tvChannelId!: number | null;
  source!: string; // (to je URL)
  quality!: ChannelQualityType; // (SD.HD.4K. izbereš samo enega)
  deviceTypeId!: number | null;
  sort!: number | null;
}

export class SourceItemCollection extends ItemsCollection<TvChannelSourceItem> {
  model() {
    return TvChannelSourceItem;
  }

  // url() {
  //   return `${this.itemurkls}/soruces`;
  // }

  // searchCategory(id: number, input: string) {
  //   return axiosInstance.get(`${this.url()}/${id}/sources?query=${input}`)
  //   .then((response) => response.data);
  // }

  // createNewSource(id: number, sources: any) {
  //   return axiosInstance.post(`${this.url()}/${id}/sources`, sources)
  //   .then((response) => response.data);
  // }

  // editExistingSource(id: number, source: any) {
  //   return axiosInstance.put(`${this.url()}/${id}sources`, source)
  //   .then((response) => response.data);
  // }

  getSelectedChannelSources(id: number) {
    return axiosInstance.get(`${this.url()}/${id}/sources`)
    .then((response) => response.data);
  }

  // deleteSource(channelID: number, sourceID: number) {
  //   return axiosInstance.delete(`${this.url()}/${channelID}/sources/${sourceID}`)
  //   .then((response) => response.data);
  // }
}
