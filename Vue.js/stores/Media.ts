import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import stores from '@/stores/index';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/rating-platform';
const socialURL: string = '/social-media-platform';
const commentProposals: string = '/comment-proposal';

export class Media extends Model<IMedia> implements IMedia {

  id!: number | null;
  name!: string ;
  description!: string;
  active!: boolean;
  sort!: number | null;
  imageId!: number | null;
  webUrl!: string ;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{id: null, active: '', description: '', imageId: '', name: '', sort: '', webUrl: ''}, ...attributes}, // Default values
      parentCollection,
    );
  }
  urlRoot(): string {
    return URL;
  }

}

export default class MediaCollection extends Collection<Media> {

  model(): Constructor<Media> {
    return Media;
  }

  url(): string {
    return URL;
  }
  socialURL(): string {
    return socialURL;
  }
  commentProposals(): string {
    return commentProposals;
  }

  fetchRatingPlateformData(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    if (search) {
      return axiosInstance.get(`${this.url()}?search=${search}&page=${index}&perpage=${perPage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data.data);
    } else {
      return axiosInstance.get(`${this.url()}?page=${index}&perpage=${perPage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data.data);
    }
  }

  fetchSocialMediaData(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    if (search) {
      return axiosInstance.get(`${this.socialURL()}?search=${search}&page=${index}&perpage=${perPage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data.data);
    } else {
      return axiosInstance.get(`${this.socialURL()}?page=${index}&perpage=${perPage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data.data);
    }
  }
  fetchCommentProposalData(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    if (search) {
      return axiosInstance.get(`${this.commentProposals()}?search=${search}&page=${index}&perpage=${perPage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data.data);
    } else {
      return axiosInstance.get(`${this.commentProposals()}?page=${index}&perpage=${perPage}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
        .then((response) => response.data.data);
    }
  }
  updateRatingPlateform(id: any, data: any) {
    return axiosInstance.put(`${this.url()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }
  createNewRatingPlateform(item: any) {
    return axiosInstance.post(`${this.url()}`, item)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }
  getRatingPlateformDetail(mediaId: any) {
    return axiosInstance.get(`${this.url()}/${mediaId}`)
      .then((response) => response.data);
  }
  deletemedia(id: number) {
    return axiosInstance.delete(`${this.socialURL()}/${id}`)
      .then((response) => response.data);
  }
  deleteRatingPlatform(id: number) {
    return axiosInstance.delete(`${this.url()}/${id}`)
      .then((response) => response.data);
  }
  deleteCommentProposal(id: number) {
    return axiosInstance.delete(`${this.commentProposals()}/${id}`)
      .then((response) => response.data);
  }
  createNewSocialMediaPlateform(item: any) {
    return axiosInstance.post(`${this.socialURL()}`, item)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }
  getSocialMediaDetail(mediaId: any) {
    return axiosInstance.get(`${this.socialURL()}/${mediaId}`)
      .then((response) => response.data);
  }
  updateSocialMedia(id: any, data: any) {
    return axiosInstance.put(`${this.socialURL()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }
  createNewcommentProposalPlateform(item: any) {
    return axiosInstance.post(`${this.commentProposals()}`, item)
      .then((response) => {
        stores.Language.loadTranslations();
        return response.data;
      });
  }

  getcommentProposalDetail(mediaId: any) {
    return axiosInstance.get(`${this.commentProposals()}/${mediaId}`)
      .then((response) => response.data);
  }

  updatecommentProposal(id: any, data: any) {
    return axiosInstance.put(`${this.commentProposals()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }
  editMedia(id: any, media: any) {
    return axiosInstance.put(`${this.url()}/${id}`, media)
      .then((response) => response.data);
  }

  getSelectedMedia(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data)
      .catch((reason) => console.log('error reasone', reason));
  }

  searchMedia(input: string, page: number, source: any, perPage: number | 10) {
    return axiosInstance.get(`${this.url()}?query=${input}&page=${page}&perPage=${perPage}`, {cancelToken: source.token})
      .then((response) => response.data);
  }

  getMediaOnPage(index: number) {
    return axiosInstance.get(`${this.url()}?page=${index}`)
      .then((response) => response.data);
  }

  getAllMedia() {
    return axiosInstance.get(`${this.url()}`)
      .then((response) => response.data);
  }

  getMediaValidationRules() {
    return axiosInstance.get(`media/validation`)
      .then((response) => response.data);
  }

  importMedia(data: any, tableName: string) {
    return axiosInstance.post(`import/${tableName}`, {data})
      .then((response) => response.data);
  }

  exportMedia(tableName: string) {
    return axiosInstance.get(`export/${tableName}`)
      .then((response) => response.data);
  }
  listofSocialMedia() {
    return axiosInstance.get(`${this.socialURL()}?page=${1}&perpage=${100}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
      .then((response) => response.data.data);
  }
}
