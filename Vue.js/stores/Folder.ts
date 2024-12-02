import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const URL: string = 'media/folders';

export class Folders extends Model<IMediaItem> implements IMediaItem {

  id!: number | null;
  mediaFolderId!: number;
  updatedAt!: string;
  name!: string;
  path!: string;
  type!: string;
  active!: boolean;
  children!: any[];
  fileName!: string | null;
  folderName!: string | null;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{id: null, mediaFolderId: 0, updatedAt: '', name: '', path: '',  filename: '', type: '', active: true, children: [], fileName: null, folderName: null}, ...attributes}, // Default values
      parentCollection,
    );
  }
  urlRoot(): string {
    return URL;
  }
}

export default class FoldersCollection extends Collection<Folders> {

  model(): Constructor<Folders> {
    return Folders;
  }

  url(): string {
    return URL;
  }
  deleteFolders(ids: any) {
    return axiosInstance.delete(`${this.url()}/${ids}`)
      .then((response) => response.data);
  }
  getFolder(id: number, search: string = '') {
    return axiosInstance.get(`${this.url()}/${id}?search=${search}`)
      .then((response) => response.data);
  }
  createFolder(n: string, id: string) {
    return axiosInstance.post(`${this.url()}`, {mediaFolderId: Number(id), name: n})
      .then((response) => response.data);
  }
  editFolder(folder: any) {
    return axiosInstance.put(`${this.url()}/${folder.id}`, folder)
      .then((response) => response.data);
  }
}
