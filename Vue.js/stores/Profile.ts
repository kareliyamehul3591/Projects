import Axios from 'axios';
import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';

const URL: string = '/profiles';

export class Profile extends Model<IProfile> implements IProfile {
id!: null;
imageId!: string;
name!: string;
pin!: string;
pinRequired!: boolean;
isAdult!: boolean;
active!: boolean;
accountId!: number | null;
dashboardId!: number | string;

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      // {...{id: 0, email: '', enabled: false, languageId: 0}, ...attributes}, // Default values
      {...{id: 0, imageId: '', name: '', pin: '', pinRequired: false, isAdult: false, active: false, accountId: null, dashboard: null}, ...attributes}, // Default values
      parentCollection,
    );
  }

  urlRoot(): string {
    return URL;
  }
}

export default class ProfileCollection extends Collection<Profile> {

  model(): Constructor<Profile> {
    return Profile;
  }

  url(): string {
    return URL;
  }

  attachDashboard(id: any, dashboardId: any) {
     return axiosInstance.post(`${this.url()}/${id}/dashboard/${dashboardId}/attach`)
      .then((response) => response.data);
  }
}
