import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/property/assets';
const URLCATEGORY: string = '/asset/categories';

export class AssetCategory extends Model<IPropertyAssets> implements IPropertyAssets {

  id!: number | null;
  active!: boolean;
  description!: string;
  imageId!: number | null;
  name!: string;
  sort!: number | null;
  address!: string;
  validToccRequiredAtCheckin!: string;
  defaultErFrequency!: string;

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

  urlcategory(): string {
    return URLCATEGORY;
  }
}

export default class AssetCategoryCollection extends Collection<AssetCategory> {

  routeName = 'assetCategory';

  model(): Constructor<AssetCategory> {
    return AssetCategory;
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

    let url = this.urlcategory() + '?page=' + index + '&perpage=' + perPage;
    if (search) {
      url = url + '&search=' + search;
    }

    return axiosInstance.get(url, {
      cancelToken: new CancelToken(function executor(c: any) {
        cancel = c;
      }),
    })
      .then((response) => response.data.data);
  }

  createNewItem(item: any) {
    return axiosInstance.post(`${this.urlcategory()}`, item)
      .then((response) => response.data);
  }

  updateItem(id: number, data: any) {
    return axiosInstance.put(`${this.urlcategory()}/${id}`, data)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.urlcategory()}/${id}`)
      .then((response) => response.data);
  }
}
