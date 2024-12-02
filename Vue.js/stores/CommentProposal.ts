import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL: string = '/rating-platform';
const socialURL: string = '/social-media-platform';
const commentProposals: string = '/comment-proposal';

export class CommentProposal extends Model<IMedia> implements IMedia {

  id!: number | null;
  name!: string;
  description!: string;
  active!: boolean;
  sort!: number | null;
  imageId!: number | null;
  webUrl!: string;

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
          webUrl: '',
        }, ...attributes,
      }, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }

}

export default class CommentProposalCollection extends Collection<CommentProposal> {

  routeName = 'commentProposal';

  model(): Constructor<CommentProposal> {
    return CommentProposal;
  }

  url(): string {
    return URL;
  }

  commentProposals(): string {
    return commentProposals;
  }

  fetchData(index: number | null, search: string | null, perPage: number | 10) {
    if (cancel !== undefined) {
      cancel();
    }

    if (index === null) {
      index = 1;
    }

    if (search) {
      return axiosInstance.get(`${this.commentProposals()}?search=${search}&page=${index}&perpage=${perPage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data.data);
    } else {
      return axiosInstance.get(`${this.commentProposals()}?page=${index}&perpage=${perPage}`, {
        cancelToken: new CancelToken(function executor(c: any) {
          cancel = c;
        }),
      })
        .then((response) => response.data.data);
    }
  }

  deleteItem(id: number) {
    return axiosInstance.delete(`${this.commentProposals()}/${id}`)
      .then((response) => response.data);
  }

  createNewItem(item: any) {
    return axiosInstance.post(`${this.commentProposals()}`, item)
      .then((response) => response.data);
  }

  updateItem(id: any, data: any) {
    return axiosInstance.put(`${this.commentProposals()}/${id}`, data)
      .then((response) => response.data)
      .catch((error) => error);
  }
}
