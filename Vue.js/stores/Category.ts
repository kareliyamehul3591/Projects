import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const URL: string = '/tags';
// @ts-ignore
export class Category extends Model<ICategories> implements ICategories {

  id!: number;
  name!: string;
  sort!: number;
  active!: boolean;
  channels!: IChannel[];
  summary!: string;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{id: 0, name: '', sort: 0, active: false, channels: []}, ...attributes}, // Default values
      parentCollection,
    );
  }
  urlRoot(): string {
    return URL;
  }
}

export default class CategoryCollection extends Collection<Category> {

  model(): Constructor<Category> {
    return Category;
  }

  url(): string {
    return URL;
  }

  createNewCategory(newCategory: ICategories) {
    return axiosInstance.post(`${this.url()}`, newCategory)
      .then((response) => response.data);
  }

  getAllCategories() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  search(input: string) {
    return axiosInstance.get(`${this.url()}?query=${input}`)
      .then((response) => response.data);
  }

  getSpecificCategory(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data);
  }
}
