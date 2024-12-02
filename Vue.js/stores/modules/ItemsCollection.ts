import Collection from '../abstract/Collection';
import Model from '../abstract/Model';
import Module from './Module';

export class Item extends Model<IItem> implements IItem {
  id!: any;

  urlRoot(): string {
    return '';
  }
}

export default class ItemsCollection<ItemType extends Item = Item> extends Collection<ItemType> {
  constructor(public module: Module, data?: any[]) {
    super(data);
  }

  model() {
    return Item as any;
  }

  url(): string {
    return `${this.module.url()}/items`;
  }
}
