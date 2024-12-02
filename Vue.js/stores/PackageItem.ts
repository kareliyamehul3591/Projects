import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const URL: string = 'package/items';

export class PackageItems extends Model<IPackageItem> implements IPackageItem {

  id!: number | null;
  packageId!: number;
  moduleId!: number;
  itemId!: number;
  itemableType!: string;
  active!: boolean;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          packageId: 0,
          moduleId: 0,
          itemId: 0,
          itemableType: '',
          active: false,
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

export default class PackageItemsCollection extends Collection<PackageItems> {

  model(): Constructor<PackageItems> {
    return PackageItems;
  }

  url(): string {
    return URL;
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  attachItem(idList: number[], id: number, mId: number) {
    return axiosInstance.post(`${this.url()}/${id}`, {moduleId: mId, list: idList})
      .then((response) => {
        return response.data;
      });
  }

  deleteAllItems(items: any[]) {
    return axiosInstance.post(`packages/items/delete-items`, {ids: items})
      .then((response) => response.data);
  }
}
