<template>
  <div class="container">
    <skeleton v-if="!response" />

    <div v-else>
      <list-nevron-header>
        <template #title>{{ translate(module.itemsName, module.itemsName)}}</template>
        <template #nav>
          <li class="nav-item" @click="$refs.file.click()">
            <a class="nav-link pl-0" href="#"><icon type="import" class="mr-2"/>{{ translate("import", "Import") }}</a>
            <input type="file" accept=".csv" ref="file" style="display: none" @change="importFile">
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" @click="exportCSV()"><icon type="export" class="mr-2" />{{ translate("export", "Export") }}</a>
          </li>
          <li class="nav-item">
              <a class="nav-link" href="#" @click="refresh()"><icon type="refresh" class="mr-2" />{{translate("refresh","Refresh")}}</a>
          </li>
        </template>
        <template #buttons>
          <button @click="$refs.create.$children[0].open()" class="btn btn-primary">{{translate("add","Add")}}</button>
        </template>
      </list-nevron-header>

      <div class="card p-4 border-top-0">
        <nevron-search>
                <input type="text" class="form-control" id="search" placeholder="Search ..." v-model="search.query" @keyup="fetchData(1, search.query)" />
        </nevron-search>

      <div class="card-body py-0">
        <div v-if="indexList.length > 0" class="check-count">
          <span>{{indexList.length}}</span> {{translate('selected', 'selected')}}
        </div>
        <per-page-dropdown v-model="perpage" @input="perPageChange" />
      </div>

        <div class="card-body" v-if="items && items.length > 0">
          <table class="table table-hover">
            <thead>
              <tr>
                <th width="15" class="border-top-0 text-center">
                  <div class="form-check">
                    <input class="form-check-input position-static" type="checkbox" v-model="allChecked" @click="checkAll" />
                  </div>
                </th>
                <th width="30" class="border-top-0"></th>
                <th class="border-top-0">{{translate("name","Name")}}</th>

                <!-- sort -->
                <th v-if="'channelNumber' in items[0]" class="border-top-0" >{{translate("number","Number")}}</th>
                <th v-else-if="'sort' in items[0]" class="border-top-0" >{{translate("sort","Sort")}}</th>

                <th class="border-top-0 text-center" >{{translate("active","Active")}}</th>
                <th class="border-top-0 text-center" >{{translate("actions","Actions")}}</th>


              </tr>
            </thead>
              <tr v-for="item in items" :key="item.id" :class="{'table-danger': item.active == 0 ? true : false}">
                <td class="p-2 align-middle text-center">
                  <label :for="'cb-'+item.id" class="m-0">
                    <input class="align-middle" type="checkbox" :name="'cb-'+item.id" :id="item.id" :value="item.id" v-model="indexList" @change="updateCheck" />
                  </label>
                </td>
                <td class="p-2 align-middle">
                  <img  class="img-background-color" v-if="item.imageId && item.image" :src="item.image.imageUrl" width="30" height="30" :alt="translate(item.name,item.name)" :title="translate(item.name,item.name)" />
                  <img v-else src="../../../../assets/no-image.png" width="30" height="30" :alt="translate(item.name,item.name)" :title="translate(item.name,item.name)" />
                </td>
                <td class="p-2 align-middle" @click="openItem(item.id)"><a href="javascript:void(0);">{{translate(item.name, item.name)}}</a></td>

                <td class="btn-drag" v-if="'sort' in items[0] || 'channelNumber' in items[0]">
                    <div :class="{'rounded': true, 'text-center': true, 'border': true, 'table-warning': false, 'table-info': false}" style="width: 35px">
                        {{item.sort || item.channelNumber}}
                    </div>
                </td>

                <td class="text-center">
                    <input class="align-middle" type="checkbox" :checked="item.active == 1" @change="toggleActive(item)">
                </td>

                <td>
                  <div class="d-flex justify-content-center">
                    <a class="nav-link text-primary p-0 mr-3" href="javascript: void(0)" @click="openItem(item.id)">
                      <icon type="edit"/>
                    </a>
                    <a class="nav-link text-danger p-0" href="javascript: void(0)" @click="deleteItem(item)">
                      <icon type="remove"/>
                    </a>
                  </div>
                </td>
              </tr>
          </table>
          <nav class="text-center">
            <v-pagination v-if="response" v-model="response.currentPage" :page-count="response.lastPage" :search="search.current" />
          </nav>
        </div>
        <div class="card-body" v-else>
          <nevron-search-no-results :type="translate(module.itemsName, module.itemsName).toLowerCase()"  />
        </div>
      </div>
    </div>
    <create ref="create" :module="module" />
  </div>
</template>

<!--
TODO bulk actions
<div v-if="!showMain">
  <bulk-edit :edit-selected="selected" @cancel="showMain = true"/>
</div>
-->

<script lang="ts">
import { Component, Vue, Watch, Prop } from 'vue-property-decorator';
// @ts-ignore
// import draggable from 'vuedraggable';
import BulkEdit from '@/modules/BulkEdit/BulkEdit.vue';
import stores from '@/stores';
import PerPageDropdown from '@/components/PerPageDropdown.vue';
import vPagination from '@/components/VuePlainPagination.vue';
import TvModule , { TvModuleItem } from '@/stores/modules/TvModule';
import Axios from 'axios';
import ListNevronHeader from '@/components/ListNevronComponents/ListNevronHeader.vue';
import Skeleton from '@/modules/Skeleton.vue';
import NevronEmpty from '@/components/ListNevronComponents/EmptyNevronHeader.vue';
import NevronSearch from '@/components/NevronSearch.vue';
import NevronSearchNoResults from '@/components/NevronSearchNoResults.vue';
import {sortCheck, sortMoved, sortMove} from '@/helpers/VueDraggableResorting';
import draggable from 'vuedraggable';
import Create from './CreateDynamicModules.vue';

@Component({
  components: {
    BulkEdit,
    draggable,
    ListNevronHeader,
    Skeleton,
    NevronEmpty,
    NevronSearch,
    NevronSearchNoResults,
    PerPageDropdown,
    vPagination,
    Create,
  },
})

export default class ItemsIndex extends Vue {
  @Prop()
  module!: TvModule;

  response: any = null;
  items: TvModuleItem[] = [];

  search: any = {
    query: '',
    current: '',
  };

  sort: any = {
    moved: [],
    changed: [],
    element: 0,
  };

  // TODO
  firstLoad = true;
  perpage = 20;
  indexList: number[] = [];
  allChecked: boolean = false;
  showMain: boolean = true;
  selected: IBulkResponse | null = null;

  file: any = '';
  uploadResponse: any = '';
  exportData: any = '';

  message: any = {
    import: '',
    export: '',
    message: '',
  };

  /*
   * Watch for module
   */
  @Watch('module', { immediate: true })
  setModuleFromProps() {
    this.items = this.module.itemsCollection.models;
    this.module.getData();
  }

  perPageChange() {
    this.fetchData(1);
  }
  @Watch('response.currentPage')
  ChangedPage() {
    if (this.firstLoad) {
      this.firstLoad = false;
      return;
    }
    this.search.query = this.search.current;
    this.fetchData(this.response.currentPage, this.search.current);
  }
  /**
   * Called when changes need to re-render
   */
  mounted() {
    if (this.$route.query.search) {
      this.search.query = this.$route.query.search;
      this.search.current = this.$route.query.search;
    }

    // set page from URL
    let index = 1;
    if (this.$route.query.page) {
      index = Number(this.$route.query.page);
    }
    if (this.$route.query.perpage) {
      this.perpage = Number(this.$route.query.perpage);
    }

    this.fetchData(index, this.search.query);
  }

  refresh() {
    this.fetchData(this.response.currentPage);
  }

  /*
   * Fetch data
   */
  fetchData(index = 1, search = null) {
    return this.module.fetchData(index, search, this.perpage)
      .then((response) => {
        this.response = response;
        this.items = this.response.data;

        this.search.current = this.search.query;
        this.updateCheck();
      })
      .catch((error) => {
          console.log(error);
      });
  }

  openItem(itemId: string|number) {
    this.$router.push({name: `${this.module.path}.items.show`, params: {id: String(itemId)}, query: {lastpage: String(this.response.currentPage), perpage: String(this.perpage)}});
  }

  /* removes or appends all items ids on current page to indexList */
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
    // @ts-ignore
    return this.items.map((item: any) => item.id);
  }

  bulkEdit() {
    return stores.BulkEdit.bulkEdit(this.indexList, this.module.id)
      .then((response: any) => {
        this.showMain = false;
        this.selected = response;
      })
      .catch((e: any) => {
        console.log(e);
      });
  }

  @Watch('uploadResponse')
  importCsv() {
    this.module.import(this.uploadResponse, 'series', this.module.id)
      .then((response) => {
        this.message.import = response.result;
        this.message.message = response.message;
        if (response.result === true) {
          this.refresh();
          console.log(response.message);
        } else {
          console.log(response.message);
        }
      });
  }

  exportCSV() {
    this.module.export('series', this.module.id)
      .then((response) => {
        this.exportData = response.data;
        if (response.result === true) {
          const csv = this.exportData;
          const anchor = document.createElement('a');
          anchor.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
          anchor.target = '_blank';
          anchor.download = 'NevronSeriesItems.csv';
          anchor.click();
        }
        this.message.message = response.message;
        this.message.export = response.result;
      });
  }

  importFile(event: any) {
    const file = event.target.files[0];
    const reader = new FileReader();
    reader.onload = (e) => {
      this.uploadResponse = e.target ? e.target.result : '';
    };
    reader.readAsText(file);
  }

  toggleActive(item: any) {
    item.active = Number(!item.active);
    this.module.updateItem!(Number(item.id), {id: item.id, active: item.active})
      .then((response) => {
        console.log(response);
      });
  }

  deleteItem(item: any) {
    if (confirm(`Permanently delete "${item.name}"`)) {
      this.module.deleteItem!(Number(item.id)).then((response) => this.fetchData(this.response.currentPage));
    }
  }

}
</script>
