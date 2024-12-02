import ItemsCollection, {Item} from './ItemsCollection';

import Axios from 'axios';
import Module from './Module';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;

export class SeriesModuleItem extends Item implements ISeries {
    active!: boolean;
    sort!: number | null;
    title!: string;
    originalTitle!: string;
    shortDescription!: string;
    fullDescription!: string;
    image?: string | null | undefined;
    // @ts-ignore
    imageId: number | null | undefined;
    releaseYear!: number | null;
    endYear!: number | null;
    studio!: string;
    price!: string;
    rating!: string;
    adult!: boolean;
    origin!: string;
    moduleId!: number | null;

}

export class SeriesItemsCollection extends ItemsCollection<SeriesModuleItem> {
  model() {
    return SeriesModuleItem;
  }

  url(): string {
    return this.module.url();
  }

  getSeries() {
    return axiosInstance.get(this.url())
      .then((response) => response.data);
  }

}

export default class SeriesModule extends Module<SeriesItemsCollection> {

  routeName = 'series';

  Collection() {
    return SeriesItemsCollection;
  }

  url() {
    return `/series/${this.id}`;
  }

  urlItem() {
    return `${this.url()}/series`;
  }
  crewurl() {
    return 'crews';
  }
  casturl() {
    return 'casts';
  }

  urlCategory() {
    return `${this.url()}/genres`;
  }

  urlItems() {
    return `${this.url()}/series`;
  }

  updateCategory(id: any, category: ICategories) {
    console.log('url', `${this.urlCategory()}/${id}`);
    return axiosInstance.put(`${this.urlCategory()}/${id}`, category)
      .then((response) => response.data);
  }
  createNewItem(item: any) {
    return axiosInstance.post(`series/${this.id}/series`, item)
      .then((response) => {
        return response.data;
      });
  }

  getAllCategories(search: string | null = null) {
    if (cancel !== undefined) {
      cancel();
    }
    const searchString = (Boolean(search)) ? `search=${search}` : '';
    return axiosInstance.get(`${this.urlCategory()}?${searchString}`, {
      cancelToken: new CancelToken(function executor(c: any) {cancel = c; }),
    })
      .then((response) => response.data);
  }

  getSpecificCategory(id: number) {
    return axiosInstance.get(`${this.urlCategory()}/${id}`)
      .then((response) => response.data);
  }

  fetchList(index: number = 1, search: string | null, perpage: number | null) {
    // tslint:disable-next-line:no-shadowed-variable
    const  CancelToken: any = Axios.CancelToken;
    // tslint:disable-next-line:no-shadowed-variable
    let cancel: any;

    if (Boolean(search)) {
      return axiosInstance.get(`series/${this.id}/series?search=${search}`, { cancelToken: new CancelToken(function executor(c: any) {cancel = c; })})
        .then((response) => response.data);
    } else { return axiosInstance.get(`series/${this.id}/series?page=${index}&perpage=${perpage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
      .then((response) => response.data);
    }
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
  getSeasons(id: number) {
    return axiosInstance.get(`series/${this.id}/series/${id}/seasons`)
      .then((response) => response.data);
  }
  getSeasonsDetails(id: number, seriesId: number) {
    return axiosInstance.get(`series/${this.id}/series/${seriesId}/seasons/${id}`)
      .then((response) => response.data);
  }
  getEpisodesDetails(id: number, seasonId: number, parentType: string) {
    return axiosInstance.get(`series/${this.id}/${parentType}/${seasonId}/episodes/${id}`)
      .then((response) => response.data);
  }

  getCrewDetails(id: number) {
    console.log(id);
    console.log(`${this.crewurl()}/${id}`);
    return axiosInstance.get(`${this.crewurl()}/${id}`)
      .then((response) => response.data);
  }
  getCastDetails(id: number) {
    console.log(id);
    console.log(`${this.casturl()}/${id}`);
    return axiosInstance.get(`${this.casturl()}/${id}`)
      .then((response) => response.data);
  }

  attachCategoryToPage(series: SeriesModule, n: any) {
    return axiosInstance.post(`${this.urlItem()}/${series.id}/attach`, {name: n.tag.text})
      .then((response) => response.data);
  }

  getAutocompleteCategories(query: string, source: any) {
    return axiosInstance.get(`${this.urlCategory()}/unattached?query=${query}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  createNewSource(id: number, sources: any) {
    return axiosInstance.post(`/vod/${this.id}/movies/${id}/sources`, sources)
      .then((response) => response.data);
  }
  createNewCategory(category: ISeriesCategories) {
    return axiosInstance.post(`series/${this.id}/genres`, category)
      .then((response) => {
        return response.data;
      });
  }
  deleteItem(id: number) {
    return axiosInstance.delete(`${this.urlItem()}/${id}`)
      .then((response) => response.data);
  }
  getCategoryItems(id: number, search: string) {
    return axiosInstance.get(`series/${this.id}/genres`)
      .then((response) => response.data);
  }
  updateItem(id: number, series: ISeries) {
    return axiosInstance.put(`${this.urlItem()}/${id}`, series)
      .then((response) => response.data);
  }

  getSeriesDetails(id: number) {
    return axiosInstance.get(`series/${this.id}/series/${id}`)
      .then((response) => response.data);
  }

  createEpisodesItem(item: any, checkParentType: string, parentId: number ) {
    return axiosInstance.post(`series/${this.id}/${checkParentType}/${parentId}/episodes`, item)
      .then((response) => {
        return response.data;
      });
  }
  deleteCategory(id: number) {
    return axiosInstance.delete(`${this.urlCategory()}/${id}`)
      .then((response) => response.data);
  }
  fetchCrewCastData(index: number | null, search: string | null, perPage: number | 10) {
    // if (cancel !== undefined) {
    //   this.cancel();
    // }
    return axiosInstance.get(`crews-casts?search=${search}`)
      .then((response) => response.data);
  }
  sortCategories(id: number, newPosition: number) {
    return axiosInstance.patch(`${this.urlCategory()}/${id}/resort`, {sort: newPosition})
      .then((response) => response.data);
  }
  fetchSeries(index: number | null, search: string | null, perpage: number | null) {

    if (search) {
      return axiosInstance.get(`series/${this.id}/series` )
        .then((response) => response.data);
    } else {
      return axiosInstance.get(`series/${this.id}/series?page=${index}&perpage=${perpage}`)
        .then((response) => response.data);
    }
  }
  autocompleteCategories(query: string, source: any) {
    return axiosInstance.get(`series/${this.id}/genres?type=ajax`)
      .then((response) => response.data);
  }
  sortMovies(id: number, newPosition: number) {
    return axiosInstance.patch(`${this.urlItem()}/${id}/resort`, {sort: newPosition})
      .then((response) => response.data);
  }
  addGenre(selectedCategory: any, seriesId: number) {
    return axiosInstance.post(`series/${this.id}/series/${seriesId}/genres/attach`, {  name: selectedCategory })
      .then((response) => response.data);
  }
  removeGenre(selectedCategory: any, seriesId: number) {
    return axiosInstance.post(`series/${this.id}/series/${seriesId}/genres/detach`, {  name: selectedCategory })
      .then((response) => response.data);
  }
  deleteSeries(seriesId: number) {
    return axiosInstance.delete(`series/${this.id}/series/${seriesId}`)
      .then((response) => response.data);
  }
  deleteSeason(seriesId: number, seasonId: number) {
    return axiosInstance.delete(`series/${this.id}/series/${seriesId}/seasons/${seasonId}`)
      .then((response) => response.data);
  }
  deleteEpisode(seriesId: number, episdoeId: number) {
    return axiosInstance.delete(`series/${this.id}/series/${seriesId}/episodes/${episdoeId}`)
      .then((response) => response.data);
  }
  deleteSource(episodeId: number, sourceID: number) {
    return axiosInstance.delete(`${this.url()}/episodes/${episodeId}/sources/${sourceID}`)
      .then((response) => response.data);
  }
  editExistingSource(episodeId: number, source: any) {
    return axiosInstance.put(`${this.url()}/episodes/${episodeId}/sources/${source.id}`, source)
      .then((response) => response.data);
  }
  detachCrew(selectedCrew: any, seriesId: number) {
    console.log(selectedCrew);
    // @ts-ignore
    return axiosInstance.post(`series/${this.id}/series/${seriesId}/crews/detach`, {id: selectedCrew})
      .then((response) => response.data);
  }
  detachCast(selectedCast: any, seriesId: number) {
    console.log(selectedCast);
    // @ts-ignore
    return axiosInstance.post(`series/${this.id}/series/${seriesId}/casts/detach`, {id: selectedCast})
      .then((response) => response.data);
  }
  detachTrailer(parentType: string, seriesId: number | null, episodeId: number) {
    // @ts-ignore
    return axiosInstance.delete(`/series/${this.id}/${parentType}/${seriesId}/episodes/${episodeId}`)
      .then((response) => response.data);
  }
  detachSeason(seriesId: number, seasonId: number) {
    // @ts-ignore
    return axiosInstance.delete(`/series/${this.id}/series/${seriesId}/seasons/${seasonId}`)
      .then((response) => response.data);
  }
  attachCast(idList: number[], seriesId: number) {
    console.log(idList);
    return axiosInstance.post(`series/${this.id}/series/${seriesId}/casts/attach`, {ids: idList})
      .then((response) =>  response.data );
  }
  attachCrew(idList: number[], seriesId: number) {
    return axiosInstance.post(`series/${this.id}/series/${seriesId}/crews/attach`, {ids: idList})
      .then((response) => response.data);
  }
  getPagesCategories() {
    return axiosInstance.get(this.urlCategory())
      .then((response) => response.data);
  }
  getSubItems(id: number, search: string) {
    return axiosInstance.get(`${this.urlCategory()}/${id}/content?search=${search}`)
      .then((response) => response.data);
  }

  saveCrewItems(crew: ICrew) {
    return axiosInstance.put(`crews/${crew.id}`, crew)
      .then((response) => response.data);
  }

  saveCastItems(cast: ICast) {
    return axiosInstance.put(`casts/${cast.id}`, cast)
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
