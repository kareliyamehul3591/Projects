import globalConfig from '../helpers/globalConfig';

export class GlobalConfig {
  config: any = {};

  constructor() {
    this.config = globalConfig;
  }
}

export default new GlobalConfig();
