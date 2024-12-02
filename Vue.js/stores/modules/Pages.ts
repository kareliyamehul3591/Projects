import ItemsCollection, { Item } from './ItemsCollection';

import Axios from 'axios';
import Module from './Module';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;

export class PagesModuleItem extends Item implements IPagesCategory {
  id!: number | null;
  moduleId!: number | null;
  name!: string;
  active!: boolean;
  menu!: boolean;
  summary!: string;
  slug!: string | null;
  imageId!: number | null;
  imageType!: string | null;
  erpId!: number | null;
  children!: any[];
  parentId!: number | null;
  sort!: number;
}

export class PagesItemsCollection extends ItemsCollection<PagesModuleItem> {
  model() {
    return PagesModuleItem;
  }

  url(): string {
    return this.module.url();
  }

  getMovies() {
    return axiosInstance.get(this.url())
      .then((response) => response.data);
  }

}

export default class PagesModule extends Module<PagesItemsCollection> {

  routeName = 'pages';

  Collection() {
    return PagesItemsCollection;
  }

  url() {
    return `/pages/${this.id}`;
  }

  urlItem() {
    return `${this.url()}/page`;
  }

  urlCategory() {
    return `${this.url()}/categories`;
  }

  urlItems() {
    return `${this.url()}/items`;
  }

  fetchImageById(id: any) {
    return axiosInstance.get(`${this.urlItem()}/image/${id}`)
    .then((response) => {
      return response.data;
    });
  }

  updateCategory(id: any, data: any) {
    return axiosInstance.patch(`${this.urlCategory()}/${id}`, data)
    .then((response) => response.data);
  }

  getSpecificCategory(id: number|string) {
    return axiosInstance.get(`${this.urlCategory()}/${id}`)
    .then((response) => response.data);
  }

  // dprecated by getAllCategories?
  fetchCategoryData(index: number | null = null, search: string | null = null) {
    if (cancel !== undefined) {
      cancel();
    }

    if (search && index) {
      return axiosInstance.get(`${this.urlCategory()}?search=${search}&page=${index}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
          .then((response) => response.data);
    } else if (!search && index) {
      return axiosInstance.get(`${this.urlCategory()}?page=${index}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.urlCategory()}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data);
    }
  }

  getAllCategories() {
    if (cancel !== undefined) {
      cancel();
    }
    return axiosInstance.get(`${this.urlCategory()}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
    .then((response) => response.data);

  }

  fetchData(index: number = 1, search: string | null, perpage: number | null) {
    if (cancel !== undefined) {
      cancel();
    }

    if (search) {
      return axiosInstance.get(`${this.urlItems()}?search=${search}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.urlItems()}?page=${index}&perpage=${perpage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data);
    }
  }

  createNewItem(item: any) {
    return axiosInstance.post(`${this.urlItem()}`, item)
      .then((response) => response.data);
  }
  detachCategoryFromPage(page: IPagesItem, n: any) {
    return axiosInstance.post(`${this.urlItem()}/${page.id}/detach`, {name: n.tag.text})
    .then((response) => response.data);
  }

  attachCategoryToPage(page: IPagesItem, n: any) {
    return axiosInstance.post(`${this.urlItem()}/${page.id}/attach`, {name: n.tag.text})
    .then((response) => response.data);
  }

  getAutocompleteCategories(query: string, source: any) {
    return axiosInstance.get(`${this.urlCategory()}/unattached?query=${query}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  detachGallery(id: number, imageId: number) {
    return axiosInstance.post(`${this.urlItem()}/${id}/detach/gallery`, {id: imageId})
      .then((response) => response.data);
  }

  attachGallery(id: number, imageId: number) {
      return axiosInstance.post(`${this.urlItem()}/${id}/attach/gallery`, {id: imageId})
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.urlItem()}/${id}`)
      .then((response) => response.data);
  }

  updateItem(id: number, page: IPagesItem) {
    return axiosInstance.put(`${this.urlItem()}/${id}`, page)
      .then((response) => response.data);
  }

  getItem(id: number) {
    return axiosInstance.get(`${this.urlItem()}/${id}`)
    .then((response) => response.data);
  }

  createNewCategory(cat: IPagesCategory) {
    return axiosInstance.post(`${this.urlCategory()}`, {category: cat})
      .then((response) => response.data);
  }
  createRootCategory(cat: IPagesCategory, id: string) {
    return axiosInstance.post(`${this.urlCategory()}/root`, {parent: Number(id), category: cat})
      .then((response) => response.data);
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

  editPagesCategory(cat: IPagesCategory) {
    return axiosInstance.put(`${this.urlCategory()}/${cat.id}`, cat)
      .then((response) => response.data);
  }

  getSubItems(id: number, search: string) {
    return axiosInstance.get(`${this.urlCategory()}/${id}/content?search=${search}`)
      .then((response) => {
        console.log(`${this.urlCategory()}/${id}/content?search=${search}`);
        return response.data;
      });
  }

  import(data: any, tableName: string, id: any) {
    return axiosInstance.post(`import/${tableName}?type=module&module_id=${id}`, {data})
      .then((response) => response.data);
  }

  export(tableName: string, id: any) {
    return axiosInstance.get(`export/${tableName}?type=module&module_id=${id}`)
      .then((response) => response.data);
  }
}

export class PagesItem extends Item implements IPagesItem {
  moduleId!: number;
  name!: '';
  subname!: null;
  slug!: null;
  content!: string;
  active!: boolean;
  menuTitle!: string;
  imageId!: null;
  imagType!: string | null;
  summary!: string;
  gallery!: IMediaItem[];
  categories!: IPagesCategory[];
  parentId!: null;
  title!: null;
  price!: '';
  currency!: '';
  taxes!: false;
  shortDescription!: string | null;
  description!: string;
  showDescription!: boolean | false;
  orderable!: boolean | false;
  bookable!: boolean | false;
  orderDestination!: string | null;
  orderEmailTo!: string | null;
  deliveryOptions!: string | null;
  from!: any | null;
  to!: any | null;
  noDateLimit!: boolean | false;
  packages!: IPackage[];
  pmsId!: string | null;
  posId!: string | null;
  sort!: number | null;
  tags!: string[];
  productType!: string;
  vendor!: string | null;

  url() {
    return `/pages/${this.id}`;
  }

  urlItem() {
    return `${this.url()}/page`;
  }

}
