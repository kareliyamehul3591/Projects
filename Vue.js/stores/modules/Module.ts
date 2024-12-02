import ItemsCollection from './ItemsCollection';
import Model from '../abstract/Model';
import {ModuleTypePathMap} from '@/helpers/constants';

// It reads models type from collection
type CollectionItemsType<T> = T extends { models: infer U } ? U : any;

export default class Module<CollectionType extends ItemsCollection = ItemsCollection>
 extends Model<IModule> implements IModule {

  id!: number;
  type!: ModuleType;
  status!: ModuleStatus;
  note!: string;
  name!: string;
  description!: string;
  settings?: ISetting[];

  itemsCollection: CollectionType;

  constructor(attributes: IModule, parent: any) {
    super(attributes, parent);
    const collectionClass = this.Collection();
    this.itemsCollection = new collectionClass(this);
  }

  getData() {
    return this.itemsCollection.getData();
  }

  urlRoot() {
    return `/modules`;
  }

  get items() {
    return this.itemsCollection.models;
  }

  get itemsName(): string  {
    if (!this.settings) {
      return 'Items';
    }
    return this.settings.find((item: ISetting) => item.key === 'item_name')?.value || 'Items';
  }

  get categoriesName(): string  {
    if (!this.settings) {
      return 'Categories';
    }
    return this.settings.find((item: ISetting) => item.key === 'category_name')?.value || 'Categories';
  }

  get path(): string {
    return  ModuleTypePathMap[this.type as keyof typeof ModuleTypePathMap];
  }

  /**
   * Collection constructor
   */
  protected Collection(): Constructor<CollectionType> {
    return ItemsCollection as any;
  }

}
