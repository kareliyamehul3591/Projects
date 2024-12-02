import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/location/zones';
const URLCATEGORY: string = '/location/categories';

export class LocationCategory extends Model<Ilocations> implements Ilocations {

  id!: number | null;
  propertyId!: number | null;
  name!: string;
  description!: string;
  active!: boolean;
  sort!: number | null;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          propertyId: '',
          name: '',
          description: '',
          active: '',
          sort: '',
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

export default class LocationCategoryCollection extends Collection<LocationCategory> {

  routeName = 'zoneCategory';

  model(): Constructor<LocationCategory> {
    return LocationCategory;
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
      .then((response) => response.data.data);
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
