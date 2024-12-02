import ItemsCollection, {Item} from './ItemsCollection';

import Axios from 'axios';
import Module from './Module';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;

export class ApplicationsModuleItem extends Item implements IApplicationsCategory {
  id!: number | null;
  moduleId!: number | null;
  name!: string;
  active!: boolean;
  description!: string | null;
  children!: any[];
  parentId!: number | null;
  sort!: number;
  slug!: string | null;
  imageId!: number | null;
}

export class ApplicationsItemsCollection extends ItemsCollection<ApplicationsModuleItem> {
  model() {
    return ApplicationsModuleItem;
  }

  url(): string {
    return this.module.url();
  }

}

export default class ApplicationsModule extends Module<ApplicationsItemsCollection> {

  routeName = 'applications';

  Collection() {
    return ApplicationsItemsCollection;
  }

  url() {
    return `/applications/${this.id}`;
  }

  urlItem() {
    return `${this.url()}/application`;
  }

  urlCategory() {
    return `${this.url()}/categories`;
  }

  urlItems() {
    return `${this.url()}/application`;
  }

  updateCategory(id: any, category: ICategories) {
    return axiosInstance.patch(`${this.urlCategory()}/${id}`, category)
      .then((response) => response.data);
  }

  getAllCategories(search: string | null = null) {
    if (cancel !== undefined) {
      cancel();
    }
    const searchString = (Boolean(search)) ? `search=${search}` : '';
    return axiosInstance.get(`${this.urlCategory()}?${searchString}`, {
      cancelToken: new CancelToken(function executor(c: any) {
        cancel = c;
      }),
    })
      .then((response) => response.data);
  }

  fetchData(index: number = 1, search: string | null, perpage: number | null) {
    if (cancel !== undefined) {
      cancel();
    }

    if (Boolean(search)) {
      return axiosInstance.get(`${this.urlItems()}?search=${search}`, { cancelToken: new CancelToken(function executor(c: any) {cancel = c; })})
        .then((response) => response.data);
    } else { return axiosInstance.get(`${this.urlItems()}?page=${index}&perpage=${perpage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data);
    }
  }

  createNewItem(item: any) {
    return axiosInstance.post(`${this.urlItem()}`, item)
      .then((response) => {
        return response.data;
      });
  }

  detachCategoryFromPage(application: IApplicationsItem, n: any) {
    return axiosInstance.post(`${this.urlItem()}/${application.id}/detach`, {name: n.tag.text})
      .then((response) => response.data);
  }

  attachCategoryToPage(application: IApplicationsItem, n: any) {
    return axiosInstance.post(`${this.urlItem()}/${application.id}/attach`, {name: n.tag.text})
      .then((response) => response.data);
  }

  getAutocompleteCategories(query: string, source: any) {
    return axiosInstance.get(`${this.urlCategory()}/unattached?query=${query}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.urlItem()}/${id}`)
      .then((response) => response.data);
  }

  updateItem(id: number, application: IApplicationsItem) {
    return axiosInstance.put(`${this.urlItem()}/${id}`, application)
      .then((response) => response.data);
  }

  getApplicationDetails(id: number) {
    return axiosInstance.get(`${this.urlItem()}/${id}`)
      .then((response) => response.data);
  }

  createNewCategory(cat: IApplicationsCategory, id: string) {
    return axiosInstance.post(`${this.urlCategory()}`, {category: cat})
      .then((response) => {
        return response.data;
      });
  }

  deleteCategory(id: number) {
    return axiosInstance.delete(`${this.urlCategory()}/${id}`)
      .then((response) => response.data);
  }

  sortCategories(id: number, newPosition: number) {
    return axiosInstance.patch(`${this.urlCategory()}/${id}/resort`, {sort: newPosition})
      .then((response) => response.data);
  }

  sortMovies(id: number, newPosition: number) {
    return axiosInstance.patch(`${this.urlItem()}/${id}/resort`, {sort: newPosition})
      .then((response) => response.data);
  }

  getPagesCategories() {
    return axiosInstance.get(this.urlCategory())
      .then((response) => response.data);
  }

  editPagesCategory(cat: IApplicationsCategory) {
    return axiosInstance.put(`${this.urlCategory()}/${cat.id}`, cat)
      .then((response) => response.data);
  }

  getSpecificCategory(id: number) {
    return axiosInstance.get(`${this.urlCategory()}/${id}`)
      .then((response) => response.data);
  }

}

export class ApplicationsItem extends Item implements IApplicationsItem {
  moduleId!: number;
  name!: '';
  imageId!: null;
  content!: string;
  active!: boolean;
  categories!: ICategories[];
  parentId!: null;
  shortDescription!: string | null;
  description!: string;
  showDescription!: boolean | false;
  packageName!: string | null;

  url() {
    return `/applications/${this.id}`;
  }

  urlItem() {
    return `${this.url()}/application`;
  }

}
