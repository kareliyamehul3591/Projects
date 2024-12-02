import Collection from '@/stores/abstract/Collection';
import Model from '@/stores/abstract/Model';
import axiosInstance from '@/helpers/axios';
import Axios from 'axios';
// @ts-ignore
import i18n from '../i18n.js';

const CancelToken: any = Axios.CancelToken;
let cancel: any;
const URL = '/languages';
const transURL = '/translations';

// @ts-ignore
export class Language extends Model<ILanguage> implements ILanguage {
  id!: number | null;
  name!: string;
  shortCode!: string;
  flag!: string;
  territoryCode!: string;
  localCode!: string;
  fidelioCode!: string;
  encoding!: string;
  direction!: string;
  font!: string;
  active!: boolean;
  sort!: number;
  translations!: [];
  neighbours!: {
    prev: null,
    next: null,
  };
  pivot!: {
    accountId: number | null;
    isDefault: boolean;
  };

  constructor(attributes: any = {}, parentCollection?: any) {
    super(
      {
        ...{
          id: null,
          name: '',
          shortCode: '',
          flag: '',
          territoryCode: '',
          localCode: '',
          fidelioCode: '',
          encoding: '',
          direction: '',
          font: '',
          active: false,
          sort: 0,
          neighbours: {prev: null, next: null},
          pivot: {accountId: null, isDefault: false},
        },
        ...attributes,
      }, // Default values
      parentCollection,
    );
  }
  urlRoot(): string {
    return URL;
  }
}

export default class LanguageCollection extends Collection<Language> {

  routeName = 'languages';

  url(): string {
    return URL;
  }

  translationUrl(): string {
    return transURL;
  }

  model(): Constructor<Language> {
    return Language;
  }

  fetchData(index: number | 1, search: string | '', perPage: number | 20) {
    if (cancel !== undefined) {
      cancel();
    }
    return axiosInstance.get(`${this.url()}?page=${index}&perpage=${perPage}&search=${search}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
    .then((response) => response.data);
  }

  activeLanguages() {
    return axiosInstance.get(`${this.url()}/active`)
    .then((resposne) => resposne.data);
  }

  localMessages() {
    return axiosInstance.get(`${this.url()}/translation/content`)
    .then((response) => response.data);
  }

  createNewItem(language: ILanguage) {
      return axiosInstance.post(`${this.url()}`, language)
      .then((response) => response.data);
  }

  show(id: any) {
    return axiosInstance.get(`${this.url()}/${id}`)
    .then((response) => response.data);
  }

  updateItem(id: string|number, language: ILanguage) {
    return axiosInstance.put(`${this.url()}/${id}`, language)
    .then((response) => response.data);
  }
  updateLanguage(id: any) {
    return axiosInstance.post(`${this.url()}/${id}`)
    .then((response) => response.data);
  }

  getDeviceValidationRules() {
    return axiosInstance.get(`language/validation`)
      .then((response) => response.data);
  }

  translationList(index: number, perpage: number, search: string) {
    if (cancel !== undefined) {
      cancel();
    }
    return axiosInstance.get(`${this.translationUrl()}?perpage=${perpage}&page=${index}&search=${search}`, { cancelToken: new CancelToken(function executor(c: any) { cancel = c; })})
    .then((response) => response.data);
  }

  translationLanguages() {
    return axiosInstance.get(`${this.translationUrl()}/languages`)
    .then((response) => response.data);
  }

  loadTranslations() {
    this.localMessages()
    .then((response: any) => {
      const locales = response;
      for (const key in locales) {
        if (key) {
          i18n.setLocaleMessage(key, locales[key]);
        }
      }
    });
  }

  createTranslation(translation: ITranslation) {
    return axiosInstance.post(`${this.translationUrl()}`, translation)
    .then((response) => response.data);
  }
  exportTranslations() {
    return axiosInstance.get(`${this.translationUrl()}/export`)
    .then((response) => response.data);
  }
  importTranslations(data: any) {
    return axiosInstance.post(`${this.translationUrl()}/import`, {data})
    .then((response) => response.data);
  }

  showTranslationsByKey(key: string) {
    return axiosInstance.get(`${this.translationUrl()}/${key}`)
    .then((response) => response.data);
  }

  checkKey(key: string, languageId: any) {
    return axiosInstance.get(`${this.translationUrl()}/check/${key}/${languageId}`)
    .then((response) => response.data);
  }
  languagesWithTranslation(key: string) {
    return axiosInstance.get(`/translation/${key}/languages`)
    .then((response) => response.data);
  }
}
