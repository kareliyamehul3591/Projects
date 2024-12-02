import {AxiosResponse} from 'axios';

import Model from './Model';
import axiosInstance from '../../helpers/axios';

// TODO: Add upload, remove etc...
export default abstract class Collection<T extends Model = any> {
  models: T[] = [];
  fetching = false;
  private fetchPromise: Promise<any> | null = null;

  constructor(data: any[] = []) {
    this.set(data);
  }

  abstract url(): string;

  /**
   * Specifies the model class for that collection
   */
  abstract model(): Constructor<T>;

  /**
   * Creates a new model instance with the given attributes
   */
  build(attributes: { [key: string]: any } = {}): T {
    const modelClass = this.model();
    if (attributes instanceof modelClass) {
      attributes.collection = this;
      return attributes;
    }
    return new modelClass(attributes, this);
  }

  /**
   * Sets a collection of models.
   * Returns the set models.
   * TODO: Make smarter (don't recreate models etc..)
   * https://github.com/masylum/mobx-rest/blob/master/src/Collection.js#L201
   */
  set(data: any[]) {
    if (Array.isArray(data)) {
      const models = data.map((d) => this.build(d));
      this.models.length = 0; // Clearam array
      this.models.push.apply(this.models, models);
      // this.models.push(models);
      return models;
    } else {
      return [];
    }
  }

  /**
   * Adds a model or collection of models.
   */
  add(data: Array<{ [key: string]: any } | T> | { [key: string]: any } | T): void {
    if (!Array.isArray(data)) {
      data = [data];
    }

    const {build: build1} = this;
    // @ts-ignore
    this.models.push(...data.map(build1));
  }

  /**
   * Fetches the models from the backend
   */
  fetch(): Promise<boolean> {
    if (this.fetchPromise) {
      return this.fetchPromise;
    }
    this.fetching = true;
    this.fetchPromise = axiosInstance.get(this.url())
      .then((response: AxiosResponse<object[]>) => {
        this.set(response.data);
        this.fetchPromise = null;
        return true;
      })
      .finally(() => {
        this.fetching = false;
      });

    return this.fetchPromise;
  }

  getData() {
    return this.fetch();
  }

  // Functions for working with models / collection

  get length() {
    return this.models.length;
  }

  isEmpty() {
    return this.length === 0;
  }

  // Returns copy of models, is not reactive!
  toArray(): T[] {
    return this.models.slice(0);
  }

  // Returns models array
  getArray(): T[] {
    return this.models;
  }

  at(index: number): T | undefined {
    return this.models[index];
  }

  remove(ids: number | T | Array<number | T>): void {
    if (!Array.isArray(ids)) {
      ids = [ids];
    }

    const modelConstructor = this.model();
    ids.forEach((id) => {
      let model;

      if (id instanceof modelConstructor && id.collection === this) {
        model = id;
      } else if (typeof id === 'number') {
        model = this.get(id);
      }

      if (!model) { return; }

      this.models.splice(this.models.indexOf(model), 1);
      model.collection = null;
    });
  }

  // return -1 if not found
  findIndex(id: any): number {
    for (let i = 0; i < this.length; i++) {
      if (this.models[i].id === id) {
        return i;
      }
    }
    return -1;
  }

  // Returns model with id
  get(id: any): T | undefined {
    for (let i = 0; i < this.length; i++) {
      if (this.models[i].id === id) {
        return this.models[i];
      }
    }
    return undefined;
  }

  // Returns found items, can be optimized
  getIds(ids: any[]): T[] {
    const items = [];
    for (const id of ids) {
      const item = this.get(id);
      if (item) {
        items.push(item);
      }
    }
    return items;
  }
  // Finds with function and returns first element
  find(findFn: (value: T, index: number, array: ReadonlyArray<T>) => boolean): T | undefined {
    for (let i = 0; i < this.length; i++) {
      if (findFn(this.models[i], i, this.models)) {
        return this.models[i];
      }
    }
    return undefined;
  }

  // Tells if model is in collection
  has(id: any) {
    return this.get(id) !== undefined;
  }
}
