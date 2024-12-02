import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = 'packages';

export class Packages extends Model<IPackage> implements IPackage {

  id!: number | null;
  name!: string;
  comment!: string | null;
  imageId!: number | string | null;
  active!: boolean;
  items!: IPackageItem[];
  packageId!: number|string|null;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          name: '',
          comment: null,
          mediaFileId: null,
          active: false,
          items: [],
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

export default class PackagesCollection extends Collection<Packages> {

  routeName = 'packages';

  model(): Constructor<Packages> {
    return Packages;
  }

  url(): string {
    return URL;
  }

  update(id: any, data: any) {
    return axiosInstance.patch(`${this.url()}/${id}`, data)
      .then((response) => response.data);
  }

  fetchData(index: number | null, search: string | null, perpage: number | 10) {
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

  getItemsForModule(module: IModule, packageId: any, index: number = 1, search: string | null = null) {
    if (cancel) {
      cancel();
    }

    let searchString = '';
    if (search) {
      searchString = `&search=${search}`;
    }

    let callBase: string;
    if (module.type === 'VOD') {
      callBase = `/vod/${module.id}/movies`;

    } else if (module.type === 'PAGES') {
      callBase = `/pages/${module.id}/items`;

    } else if (module.type === 'APPLICATION') {
      callBase = `/applications/${module.id}/items`;

    } else if (module.type === 'SERIES') {
      callBase = `/series/${module.id}/series`;
    } else {
      callBase = `/tv/${module.id}/channels`;
    }

    if (callBase) {
      return axiosInstance.get(`${callBase}?packageId=${packageId}&page=${index}&perpage=10${searchString}`,
        {
          cancelToken: new CancelToken(function executor(c: any) {
            cancel = c;
          }),
        })
        .then((response) => response.data);
    }

  }

  search(input: string, page: number, source: any) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  getAllPackagesOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  getAllPackages() {
    return axiosInstance.get(`${this.url()}/show/all`)
      .then((response) => response.data);
  }

  getSelectedPackage(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  createPackage(pack: IPackage) {
    return axiosInstance.post(`${this.url()}`, pack)
      .then((response) => {
        return response.data;
      });
  }

  updateItem(id: number|string, pack: IPackage) {
    return axiosInstance.put(`${this.url()}/${id}`, pack)
      .then((response) => response.data);
  }

  getPackageValidationRules() {
    return axiosInstance.get(`package/validation`)
      .then((response) => response.data);
  }
}
