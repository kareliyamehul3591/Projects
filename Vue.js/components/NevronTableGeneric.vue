<template>
<div v-if="localItems && localItems.length != 0" class="table-wrapper mb-1">
  <table class="table table-hover">
    <thead>
      <tr>
        
        <th v-if="tableMeta.selectOne" class="border-top-0 text-center">
          {{ translate("select", "Select") }}
        </th>

        <th v-else-if="hasSelect" class="border-top-0 p-2 align-middle text-center">
          <input type="checkbox" class="align-middle" v-model="allChecked" @click="checkAll" />
        </th>

        <!-- Titles for dynamic fields -->
        <th v-for="(field, index) in fields" class="border-top-0" :class="field.type" :key="index">
          {{ field.displayName }}
        </th>

        <th class="border-top-0 text-center" v-if="hasActiveSwitch">
          {{ translate("active", "Active") }}
        </th>

        <th class="border-top-0 text-center">
          {{ translate("actions", "Actions") }}
        </th>

      </tr>
    </thead>
    
    <tr v-for="item in localItems" :key="item.id" :class="{ 'table-danger': item.active == 0 ? true : false }" >
      <!-- Radio select -->
      <td v-if="tableMeta.selectOne" class="p-2 align-middle text-center">
        <label :for="'cb-' + item.id" class="m-0">
          <input class="align-middle" type="radio" 
            :name="'cb-' + item.id"
            :id="item.id"
            :value="item"
            v-model="selectedItem"
          />
        </label>
      </td>
      <!-- Checkbox select -->
      <td v-else-if="hasSelect" class="p-2 align-middle text-center" style="width: 30px;">
        <label :for="'cb-' + item.id" class="m-0">
          <input class="align-middle" type="checkbox"
            :name="'cb-' + item.id"
            :id="item.id"
            :value="item"
            v-model="selectedArray"
            @change="updateCheck"
          />
        </label>
      </td>

      <!-- DYNAMIC FIELDS -->
      <template v-for="(field, index) in fields">

        <!-- image -->
        <td v-if="field.type === 'image'" :key="index" class="p-2 px-0 align-middle" @click="openItem(item)">
          <img v-if="item.imageId && item.image" :src="item.image.imageUrl" @error="$event.target.src = noImageSrc"
            max-width="50px" class="img-background-color"/>
          <img v-else :src="noImageSrc"/>
        </td>

        <!-- sort -->
        <td v-else-if="field.type === 'sort'" class="btn-drag align-middle text-center"
            :key="index">
          <div class="rounded text-center border m-auto" style="width: 35px">
            {{ item[field.name] }}
          </div>
        </td>

        <td v-else-if="field.type === 'radio'" class="text-center" :key="index" >
          <label :for="'cb-' + item.id" class="m-0">
            <input class="align-middle" type="radio" 
              name="radioButton"
              :id="item.id"
              :checked="Boolean(item[field.name])"
              @change="$emit('radioValueChanged', item, field.name)"
            />
          </label>
        </td>

        <!-- ITEM LINK -->
        <td v-else :key="index" @click="shouldOpen(index) && openItem(item)" class="align-middle">
          <a href="javascript: void(0);" :is="shouldOpen(index) ? 'a' : 'span'">

          <!-- connected object -->
          <template v-if="field.subFields && item[field.name]">
            <template v-for="subField in field.subFields">
              <router-link v-if="field.route" :to="{name: field.route, params: {id: item[field.name].id}}" :key="subField">
                {{ translate(item[field.name][subField], item[field.name][subField]) }}
              </router-link>
              <span v-else :key="subField">
                    {{ translate(item[field.name][subField], item[field.name][subField]) }}
              </span>
            </template>
          </template>

          <!-- date -->
          <template v-else-if="field.type === 'date'">
            {{ formatDate(item[field.name]) }}
          </template>

          <!-- dual -->
          <template v-else-if="field.type === 'dual'">
            <span v-if="item[field.name]">{{ translate(item[field.name], item[field.name]) }}</span>&nbsp;
            <span v-if="item[field.backupName]">{{ translate(item[field.backupName], item[field.backupName]) }}</span>
          </template>

          <!-- not translatable -->
          <template v-else-if="field.notTranslatable && item[field.name]">
              {{ item[field.name] }}
          </template>

          <!-- regular field -->
          <template v-else-if="field.name && item[field.name]">
            <span>{{ translate(item[field.name], item[field.name]) }}</span>
          </template>

          <!-- backup field -->
          <template v-else-if="field.backupName && item[field.backupName]">
            <span>{{ translate(item[field.backupName], item[field.backupName]) }}</span>
          </template>

          <!-- empty error -->
          <template v-else-if="field.emptyError">
            <span>{{ translate(field.emptyError, field.emptyError) }}</span>
          </template>

          </a>
        </td>

      </template>

      <!-- Active -->
      <td v-if="hasActiveSwitch" class="text-center align-middle">
        <input v-if="toggableActive" class="align-middle" type="checkbox" :checked="item.active == 1" @change="toggleActive(item)">
        <input v-else class="align-middle" type="checkbox" :checked="item.active == 1" onclick="return false">
      </td>
      <!-- Actions -->
      <td class="align-middle">
        <div class="d-flex justify-content-center align-items-center" style="gap: 15px;">
          <!-- edit link -->
          <a class="nav-link text-primary p-0" href="javascript: void(0)"  @click="openItem(item, true)">
            <icon type="edit" />
          </a>
          <!-- unlink action -->
          <a v-if="hasUnlink" class="nav-link text-danger p-0" href="javascript: void(0)" @click="$emit('unlink', item)">
            <icon type="unlink" />
          </a>
          <!-- delete action -->
          <nevron-click-confirm v-if="hasRemove">
            <a class="nav-link text-danger p-0" href="javascript: void(0)" @click="$emit('remove', item)">
              <icon type="remove" />
            </a>
            <template #confirm-message>
              {{ translate('permanently-delete', 'Permanetly delete') }}&nbsp;<strong>{{ getTranslatedDisplayName(item) }}</strong>?
            </template>
          </nevron-click-confirm>
        </div>
      </td>

    </tr>
  </table>
  </div>
  <p v-else> {{ translate('no-data.-add-some?', 'No data. Add some?') }} </p>
</template>

<script lang='ts'>
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import stores from '@/stores';
import Axios from 'axios';
import { mixin as Clickaway } from 'vue-clickaway';
import NevronSearchNoResults from '@/components/NevronSearchNoResults.vue';
import _ from 'lodash';
import { modulesMetaData } from '@/helpers/constants';
import moment from 'moment';
import NevronClickConfirm from '@/components/NevronClickConfirm.vue';
import {translatedDisplayName} from '@/helpers/functions';

@Component({
  components: {
    NevronSearchNoResults,
    NevronClickConfirm,
  },
  mixins: [Clickaway],
})
export default class NevronTableModalGeneric extends Vue {
  @Prop({ required: true })
  instance!: IModule;

  @Prop({required: true})
  items!: IItem[] | IItem;

  @Prop({ default: null })
  metaKey!: string | null;

  // show active switch
  @Prop({default: true})
  hasActive!: boolean;

  // allow toggling active
  @Prop({default: false})
  toggableActive!: boolean;

  // show select checkboxes
  @Prop({default: true})
  hasSelect!: boolean;

  // emit item when clicked, instead of opening it
  @Prop({default: false})
  emitOpenItem!: boolean;

  // select item when clicked, instead of opening it
  @Prop({default: false})
  selectOpenItem!: boolean;

  // when this value is changed, selected is cleared
  @Prop({default: false})
  resetSelected!: boolean;

  // ACTIONS
  @Prop({default: false})
  hasUnlink!: boolean;
  @Prop({default: false})
  hasRemove!: boolean;

  selectedArray: IItem[] = [];
  selectedItem: IItem | null = null;
  allChecked = false;

  get instanceMeta(): any {
    // @ts-ignore
    return modulesMetaData[this.instance.routeName];
  }

  get tableMeta(): any {
    if (this.metaKey && this.instanceMeta[this.metaKey]) {
      return this.instanceMeta[this.metaKey];
    }
    return this.instanceMeta;
  }

  get fields(): any {
    if (!this.tableMeta.fields) {
      return this.instanceMeta.fields;
    }
    return this.tableMeta.fields;
  }

  get localItems() {
    if (Array.isArray(this.items)) {
      return this.items;
    } else if (this.items) {
      return [this.items];
    }
    return [];
  }

  get hasActiveSwitch() {
    if (this.instanceMeta.noActive || this.tableMeta.noActive) {
      return false;
    }
    return this.hasActive;
  }

  shouldOpen(fieldIndex: number) {
    const hasImage = this.fields[0].type === 'image';
    return (!hasImage && fieldIndex === 0) || (hasImage && fieldIndex === 1);
  }

  openItem(item: IItem, force = false) {
    if (this.emitOpenItem) {
      this.$emit('openItem', item);
      return;
    }
    if (!force && this.selectOpenItem) {
      this.selectItem(item);
      return;
    }
    const showPath = (this.instanceMeta.dynamicModule) ? 'items.show' : 'show';
    this.$router.push({
      name: `${this.instance.routeName}.${showPath}`,
      params: {id: String(item.id)},
    });
  }

  selectItem(item: IItem) {
    if (this.tableMeta.selectOne) {
      this.selectedItem = item;
    } else {
      if (this.selectedArray.filter((el: IItem) => el.id === item.id).length === 0) {
        this.selectedArray.push(item);
      } else {
        this.selectedArray = this.selectedArray.filter((it: IItem) => it.id !== item.id);
      }
    }
    this.updateCheck();
  }

  toggleActive(item: IItem) {
    console.log('Togle active');
    item.active = Number(!item.active);
    // @ts-ignore
    this.instance.updateItem(item.id, {id: item.id, active: item.active});
  }

  /* removes or appends all items ids on current page to selectedArray */
  checkAll() {
    if (!this.allChecked) {
      const notCheckedItems = this.localItems.filter((item: IItem) => !this.selectedArray.includes(item));
      this.selectedArray = this.selectedArray.concat(notCheckedItems);
      this.allChecked = true;
    } else {
      this.selectedArray = this.selectedArray.filter((item: IItem) => !this.itemIds.includes(item.id));
      this.allChecked = false;
    }
  }
  /* when item is checked */
  @Watch('items')
  updateCheck() {
    this.allChecked = this.itemIds.every((id: number) => this.selectedIds.includes(id));
  }

  @Watch('resetSelected')
  clearSelected() {
    this.selectedArray = [];
    this.selectedItem = null;
    this.allChecked = false;
  }

  @Watch('selectedArray')
  emitSelectedArray() {
    this.$emit('input', this.selectedArray);
  }
  @Watch('selectedItem')
  emitSelectedItem() {
      this.$emit('input', this.selectedItem);
  }

  getTranslatedDisplayName(item: IItem) {
    return translatedDisplayName(item);
  }

  get itemIds() {
    return this.localItems.map((item: IItem) => item.id);
  }

  get selectedIds() {
    return this.selectedArray.map((item: IItem) => item.id);
  }

  get noImageSrc() {
    const noImageSetting = stores.Setting.models.find((setting: ISetting) => setting.key === 'no_image_icon' );
    return noImageSetting?.value;
  }

  formatDate(date: any) {
    return moment(date).format('DD-MM-YYYY');
  }

}
</script>
<style scoped>

.table-wrapper {
  display: block;
  overflow-x: auto;
}

th.image {
  width: 35px;
}

th.sort, th.radio {
  text-align: center;
}

img {
  width: 35px;
  height: 35px;
  object-fit: cover;
}
</style>
