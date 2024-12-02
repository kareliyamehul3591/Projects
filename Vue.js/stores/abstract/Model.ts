import { AxiosInstance } from 'axios';
import Collection from './Collection';
import axiosInstance from '@/helpers/axios';

// Use https://github.com/masylum/mobx-rest/blob/master/src/Model.js

export default abstract class Model<T = {}, CollectionType extends Collection = Collection> {
  // Every model has unique id
  abstract id: any;

  // Every model knows in which collection it is in
  collection: CollectionType | null;

  // @ts-ignore
  _original: any;

  objectAssign = require('object-assign');

  constructor(attributes: { [key in (keyof T)]?: any } = {}, parentCollection?: CollectionType) {
    this.set(attributes);
    this.collection = parentCollection || null;
    this._original = attributes;
  }

 abstract urlRoot(): string;

  url(): string {
    let urlRoot = this.urlRoot();

    if (!urlRoot && this.collection) {
      urlRoot = this.collection.url();
    }

    if (!urlRoot) {
      throw new Error('implement `urlRoot` method or `url` on the collection');
    }

    if (this.isNew) {
      return urlRoot;
    } else {
      return `${urlRoot}/${this.id}`;
    }
  }

  fetch() {
    return axiosInstance.get(this.url())
      .then((response) => {
        this.set(response.data);
      });
  }

  reset() {
    this.set(this._original);
  }

  copy() {
    // @ts-ignore
    return new this.constructor(this._original, this.collection);
  }

  save(): AxiosInstance {
    let method = 'post';
    if (!this.isNew) {
      method = 'put';
    }
    // else if (patch) {
    //   method = 'patch'
    // } else {
    //   method = 'put'
    // }
    if (typeof Object.assign !== 'function') {
      this._original = this.objectAssign({}, this);
    } else {
      this._original = Object.assign({}, this);
    }
    delete this._original._original;
    // @ts-ignore
    return axiosInstance[method](this.url())
      .then((response: any) => {
        this.set(response.data);
      });
  }

  destroy() {
    if (this.collection && this.isNew) {
      this.collection.remove(this);
      return Promise.resolve(true);
    }

    return axiosInstance.delete(this.url())
      .then((response) => {
        if (this.collection) {
          this.collection.remove(this);
        }
      });
  }

  set(attributes: { [key in (keyof T)]?: any } = {}) {
    if (typeof Object.assign !== 'function') {
      this.objectAssign(this, attributes);
    } else {
      Object.assign(this, attributes);
    }
  }

  get isNew(): boolean {
    return !this.id;
  }

  toJSON(): any {
    // @ts-ignore
    const { collection, ...tmp } = this;
    return tmp;
  }
}
