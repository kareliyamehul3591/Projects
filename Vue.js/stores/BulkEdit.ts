import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const URL: string = '/modules/';

export class BulkEdit extends Model<IBulkResponse> implements IBulkResponse {

    fields!: IFieldsData[];
    data!: any[];
    id!: number;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {...{fields: [], data: [] }, ...attributes}, // Default values
      parentCollection,
    );
  }
  urlRoot(): string {
    return URL;
  }
}

export default class BulkEditCollection extends Collection<BulkEdit> {

  model(): Constructor<BulkEdit> {
    return BulkEdit;
  }

  url(): string {
    return URL;
  }

  bulkEdit(indexList: number[], modulID: number) {
    return axiosInstance.post(`${this.url()}/${modulID}/bulk`, indexList)
      .then((response) => response.data);
  }

}
