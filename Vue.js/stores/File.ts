import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import session from '@/stores/Session';

const URL: string = '/media/files';

export class Files extends Model<IMediaItem> implements IMediaItem {

  id!: number | null;
  mediaFolderId!: number;
  updatedAt!: string;
  name!: string;
  path!: string;
  type!: string;
  children!: any[];
  folderName!: string | null;
  fileName!: string | null;
  active!: boolean;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          mediaFolderId: 0,
          updatedAt: '',
          name: '',
          path: '',
          fileName: '',
          type: '',
          children: [],
          folderName: '',
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

export default class FileCollection extends Collection<Files> {

  model(): Constructor<Files> {
    return Files;
  }

  url(): string {
    return URL;
  }

  uploadUrl(): string {
    return URL + '/upload';
  }

  getHeader() {
    return {
      'X-Project': session.project!.id,
      'Authorization': `Bearer ${sessionStorage.getItem(session.project!.id + '-token')}`,
    };
  }

  updateImage(image: IMediaItem) {
    return axiosInstance.put(`${this.url()}/${image.id}`, image)
      .then((response) => response.data);
  }

  getImage(id: number) {
    return axiosInstance.get(`${this.url()}/${id}`)
      .then((response) => response.data);
  }

  deleteFiles(ids: any) {
    return axiosInstance.delete(`${this.url()}/${ids}`)
      .then((response) => response.data);
  }

  getImageID(id: number) {
    return axiosInstance.get(`${this.url()}/${id}/id`)
      .then((response) => response.data);
  }
}
