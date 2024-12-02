import ItemsCollection, {Item} from './ItemsCollection';

import Axios from 'axios';
import Module from './Module';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;

export class VODModuleItem extends Item implements IVOD {
  id!: number | null;
  name!: string;
  sources!: IVODSource[];
  trailers!: IVODSource[];
  description!: string;
  details!: any;
  cast!: ICast[];
  type!: string;
  imageId!: number | null;
  year!: number | null;
  adult!: boolean;
  active!: boolean;
  seriesId!: number | null;
  episode!: number | null;
  season!: number | null;
  categories!: ICategories[];
  parentId!: number | null;
  gallery!: IMediaItem[];
}

export class VODItemsCollection extends ItemsCollection<VODModuleItem> {
  model() {
    return VODModuleItem;
  }

  url(): string {
    return this.module.url();
  }

  getMovies() {
    return axiosInstance.get(this.url())
      .then((response) => response.data);
  }

}

export default class VODModule extends Module<VODItemsCollection> {

  routeName = 'vod';

  Collection() {
    return VODItemsCollection;
  }

  url() {
    return `/vod/${this.id}`;
  }

  urlMovie() {
    return `${this.url()}/movies`;
  }

  urlCategory() {
    return `${this.url()}/categories`;
  }

  updateItem(id: any, data: any) {
    return axiosInstance.patch(`${this.urlMovie()}/${id}`, data)
      .then((response) => response.data);
  }

  updateCategory(id: any, data: any) {
    return axiosInstance.patch(`${this.urlCategory()}/${id}`, data)
      .then((response) => response.data);
  }

  detachGallery(id: number, imageId: number) {
    return axiosInstance.post(`${this.urlMovie()}/${id}/detach/gallery`, {id: imageId})
      .then((response) => response.data);
  }

  attachGallery(id: number, imageId: number) {
    return axiosInstance.post(`${this.urlMovie()}/${id}/attach/gallery`, {id: imageId})
      .then((response) => response.data);

  }

  sortCategories(id: number, newPosition: number) {
    return axiosInstance.patch(`${this.urlCategory()}/${id}/resort`, {sort: newPosition})
      .then((response) => response.data);
  }

  sortMovies(id: number, newPosition: number) {
    return axiosInstance.patch(`${this.urlMovie()}/${id}/resort`, {sort: newPosition})
      .then((response) => response.data);
  }

  getAutocompleteSeries(query: string, source: any) {
    return axiosInstance.get(`${this.urlMovie()}/series?query=${query}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  deleteSource(movieId: number, sourceID: number) {
    return axiosInstance.delete(`${this.urlMovie()}/${movieId}/sources/${sourceID}`)
      .then((response) => response.data);
  }

  createNewSource(id: number, sources: any) {
    return axiosInstance.post(`${this.urlMovie()}/${id}/sources`, sources)
      .then((response) => response.data);
  }

  editExistingSource(id: number, source: any) {
    return axiosInstance.put(`${this.urlMovie()}/${id}/sources/${source.id}`, source)
      .then((response) => response.data);
  }

  detachCategoryFromMovie(channel: IVOD, n: any) {
    return axiosInstance.post(`${this.urlMovie()}/${channel.id}/detach`, {name: n.tag.text})
      .then((response) => response.data);
  }

  attachMovieToCategory(category: IVODCategories, ids: number[]) {
    return axiosInstance.post(`${this.urlCategory()}/${category.id}/attach`, ids)
      .then((response) => response.data);
  }

  editVODMovie(tempMovie: IVOD) {
    return axiosInstance.put(`${this.urlMovie()}/${tempMovie.id}`, tempMovie)
      .then((response) => response.data);
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.urlMovie()}/${id}`)
      .then((response) => response.data);
  }

  createNewItem(newVOD: IVOD) {
    return axiosInstance.post(`${this.urlMovie()}`, newVOD)
      .then((response) => {
        return response.data;
      });
  }

  detachCategoryToMovie(movie: IVOD, n: any) {
    return axiosInstance.post(`${this.urlMovie()}/${movie.id}/detach`, {name: n})
      .then((response) => response.data);
  }

  attachCategoryToMovie(movie: IVOD, n: any) {
    return axiosInstance.post(`${this.urlMovie()}/${movie.id}/attach`, {name: n})
      .then((response) => response.data);
  }

  attachPackageToMovie(movie: IVOD, n: any) {
    return axiosInstance.post(`${this.urlMovie()}/${movie.id}/package`, {name: n})
      .then((response) => response.data);
  }

  detachPackageToMovie(movie: IVOD, n: any) {
    return axiosInstance.post(`${this.urlMovie()}/${movie.id}/detachpackage`, {name: n})
      .then((response) => response.data);
  }

  getAutocompleteCategories(query: string, source: any) {
    return axiosInstance.get(`${this.urlCategory()}?query=${query}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  editVODCategory(cat: IVODCategories) {
    return axiosInstance.put(`${this.urlCategory()}/${cat.id}`, cat)
      .then((response) => response.data);
  }

  getSubItems(id: number, search: string) {
    return axiosInstance.get(`${this.urlCategory()}/${id}/content?search=${search}`)
      .then((response) => response.data);
  }

  fetchData(index = 1, search: string | null, perpage: number | null) {
    if (cancel !== undefined) {
      cancel();
    }

    if (search) {
      return axiosInstance.get(`${this.urlMovie()}?search=${search}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data);
    } else {
      return axiosInstance.get(`${this.urlMovie()}?page=${index}&perpage=${perpage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data);
    }
  }

  getSelectedVODMovie(id: number) {
    return axiosInstance.get(`${this.urlMovie()}/${id}`)
      .then((response) => response.data);
  }

  getSpecificCategory(id: number) {
    return axiosInstance.get(`${this.urlCategory()}/${id}`)
      .then((response) => response.data);
  }

  getAllCategories(search: string | null = null) {
    if (cancel !== undefined) {
      cancel();
    }
    const searchString = (Boolean(search)) ? `search=${search}` : '';
    return axiosInstance.get(`${this.urlCategory()}?${searchString}`, {
      cancelToken: new CancelToken(function executor(c: any) {cancel = c; }),
    }).then((response) => response.data);
  }

  createNewCategory(cat: ICategories) {
    return axiosInstance.post(`${this.urlCategory()}`, {category: cat})
      .then((response) => response.data);
  }

  createCategory(category: IVODCategories, parentId: string) {
    const data = category;
    if (parentId !== '') {
      data.parentId = Number(parentId);
    }
    return axiosInstance.post(`${this.urlCategory()}`, data)
      .then((response) => response.data);
  }

  deleteCategory(id: number) {
    return axiosInstance.delete(`${this.urlCategory()}/${id}`)
      .then((response) => response.data);
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
