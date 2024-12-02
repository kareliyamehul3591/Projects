// Session data saved locally and not on server!

import routerBase from '@/helpers/routerBase';
import globalConfig from '@/helpers/globalConfig';
export class Session {
  projectId: string|null = null;
  project: IManagerProject | null = null;

  // project name and logo are loaded in app.vue
  constructor() {
    if (routerBase !== '/') {
      this.projectId = routerBase;
      this.project = {
        id: routerBase,
        url: `${globalConfig.url}/${routerBase}/${globalConfig.path}`,
        contentUrl: `${globalConfig.url}/${routerBase}`,
      };
    }
  }
}

export default new Session();
