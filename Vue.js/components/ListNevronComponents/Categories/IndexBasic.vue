<template>
  <div class="container">
    <skeleton v-if="!response" />
    <div v-else>
      <list-nevron-header>
        <template #title>
          {{translate(module.name, module.name)}} {{ translate(module.categoriesName, module.categoriesName)}}
        </template>
        <template #nav>
           <li class="nav-item">
            <a class="nav-link pl-0 disabled" href="#"><icon type="import" class="mr-2"/>{{ translate("import", "Import") }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" href="#"><icon type="export" class="mr-2" />{{ translate("export", "Export") }}</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="#" @click="refresh()"><icon type="refresh" class="mr-2" />{{translate("refresh","Refresh")}}</a>
          </li>
        </template>
        <template #buttons><button @click="$refs.create.$children[0].open()" class="btn btn-primary">{{translate("add","Add")}}</button></template>
      </list-nevron-header>

        <div class="card p-4 border-top-0">
        <nevron-search>
          <input type="text" class="form-control" id="search" placeholder="Search ..."
                 v-model="search.query" @keyup="searchItems"/>
          <template v-if="search.current.length > 0">
            <span @click="search.query = ''; refresh()" class="search-clear fas fa-times"></span>
          </template>
        </nevron-search>

        <div class="card-body py-0">
          <div class="check-count" :class="{'invisible': indexList.length === 0}">
            <span>{{ indexList.length }}</span> {{ translate('selected', 'selected') }}
          </div>
        </div>

        <div class="card-body pt-1" v-if="items && items.length > 0" >
          <table class="table table-hover">
            <thead>
              <tr>
                <th  width="15" class="border-top-0 text-center">
                  <div class="form-check">
                    <input class="form-check-input position-static" type="checkbox" v-model="allChecked" @click="checkAll" />
                  </div>
                </th>
                <th class="border-top-0">{{ translate(module.categoriesName, module.categoriesName)}}</th>
                <th v-if="'sort' in items[0]" class="border-top-0">{{ translate('sort', 'Sort')}}</th>
                <th class="border-top-0 text-center" >{{translate("actions","Actions")}}</th>
              </tr>
            </thead>
            <!--  <draggable v-model="items" :tag="'tbody'" :options="{handle: '.btn-drag'}"  @end="sortMove" :move="sortMoved"> -->
                <tr v-for="category in items" :key="category.id">
                  <td class="p-2 align-middle text-center">
                    <label :for="'cb-'+category.id" class="m-0">
                      <input class="align-middle" type="checkbox" :name="'cb-'+category.id" :id="'cb-'+category.id" :value="category.id" v-model="indexList" @change="updateCheck" />
                      <span><span class="sr-only">{{translate("select-item","Select Item")}}</span></span>
                    </label>
                  </td>
                  <td>
                    <router-link class="p-2 align-middle" :to="{name: `${module.path}.categories.show`, params: {id: category.id}}">
                      {{translate(category.name, category.name)}}
                    </router-link>
                  </td>
                  <td class="btn-drag" style="cursor: grab" v-if="category.sort">
                    <div :class="{'rounded': true, 'text-center': true, 'border': true, 'table-warning': sortCheck(category.id, 'moved'),
                      'table-info': sortCheck(category.id, 'changed')}" style="width: 35px">{{category.sort}}
                    </div>
                  </td>
                <td>
                  <div class="d-flex justify-content-center">
                    <a class="nav-link text-primary p-0 mr-3" href="javascript: void(0)" @click="$router.push({name: `${module.path}.categories.show`, params: {id: category.id}})">
                      <icon type="edit"/>
                    </a>
                    <a class="nav-link text-danger p-0" href="javascript: void(0)" @click="deleteCategory(category)">
                      <icon type="remove"/>
                    </a>
                  </div>
                </td>
                </tr>
            <!-- </draggable> -->
          </table>
        </div>
        <div class="card-body" v-else>
          <nevron-search-no-results :type="translate(module.categoriesName, module.categoriesName).toLowerCase()"/>
        </div>
      </div>
    </div>
    <create-category ref="create" :module="module" @created="fetchData()" />
  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
// @ts-ignore
import draggable from 'vuedraggable';
import virtualList from 'vue-virtual-scroll-list';
import ListNevronHeader from '@/components/ListNevronComponents/ListNevronHeader.vue';
import Skeleton from '@/modules/Skeleton.vue';
import NevronEmpty from '@/components/NevronEmpty.vue';
import NevronSearch from '@/components/NevronSearch.vue';
import NevronSearchNoResults from '@/components/NevronSearchNoResults.vue';
import {sortCheck, sortMoved, sortMove} from '@/helpers/VueDraggableResorting';
import CreateCategory from './CreateBasic.vue';
import _ from 'lodash';

@Component({
  components: {
    draggable,
    virtualList,
    ListNevronHeader,
    Skeleton,
    NevronEmpty,
    NevronSearch,
    CreateCategory,
    NevronSearchNoResults,

  },
})

export default class CategoriesList extends Vue {
  @Prop()
  module!: IModule;

  response: any = null;
  items: ICategories[] = [];

  search: any = {
    query: '',
    current: '',
  };

  sort: any = {
    moved: [],
    changed: [],
    element: 0,
  };

  indexList: number[] = [];
  allChecked: boolean = false;
  showMain: boolean = true;
  selected: IBulkResponse | null = null;
  searchItems = _.debounce(() => {
    this.fetchData();
  }, 400);

  sortMoved(e: any) {
    console.log(2);
    return sortMoved(e, this.sort);
  }

  sortCheck(id: number | null, type: string): boolean {
    return sortCheck(id, type, this.sort);
  }

  checkAll() {
    if (!this.allChecked) {
      this.indexList = Array.from(new Set([...this.indexList, ...this.itemIds]));
      this.allChecked = true;
    } else {
      this.indexList = this.indexList.filter((id) => !this.itemIds.includes(id));
      this.allChecked = false;
    }
  }

  /* when item is checked */
  updateCheck() {
    if (this.itemIds.every((id: number) => this.indexList.includes(id))) {
      this.allChecked = true;
    } else {
      this.allChecked = false;
    }
  }

  get itemIds(): number[] {
    return this.items.map((item: any) => item.id);
  }

  fetchData() {
    return this.module.getAllCategories!(this.search.query)
      .then((response) => {
        this.response = response;
        this.items = response.data;
        this.search.current = this.search.query;
        this.updateCheck();
      })
      .catch((error) => {
          console.log(error);
      });
    }
  /**
   * Called when changes need to re-render
   */
  mounted() {
    // set search query from URL
    if (this.$route.query.search) {
      this.search.query = this.$route.query.search;
      this.search.current = this.$route.query.search;
    }

    this.fetchData();
  }

  refresh() {
    this.fetchData();
  }

  deleteCategory(category: any) {
    if (confirm(`Permanently delete "${category.name}"`)) {
      this.module.deleteCategory!(Number(category.id)).then((response) => this.fetchData());
    }
  }

}
</script>
