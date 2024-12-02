<template>
  <div class="container">
    <skeleton v-if="loading"/>

    <div v-else>
      <list-nevron-header>
        <template #title v-if="instance.name">{{ translate(instance.name, instance.name) }}
        </template>
        <template #title v-else>
          {{
            translate(instanceMeta.displayName.toLowerCase().replace(' ', '-'), instanceMeta.displayName)
          }}
        </template>
        <template #nav>
          <li class="nav-item">
            <a class="nav-link pl-0" href="#">
              <icon type="import" class="mr-2"/>
              {{ translate("import", "Import") }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" @click="exportSelected()"
               :class="{'text-secondary': selectedArray.length === 0}">
              <icon type="export" class="mr-2"/>
              {{ translate("export", "Export") }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" @click="deleteSelected"
               :class="{'text-secondary': selectedArray.length === 0}">
              <icon type="remove" class="mr-2"/>
              {{ translate("delete", "Delete") }}</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" @click="refresh()">
              <icon type="refresh" class="mr-2"/>
              {{ translate("refresh", "Refresh") }}</a>
          </li>
        </template>
        <template #buttons>
          <button @click="$refs.create.$children[0].open()" class="btn btn-primary">
            {{ translate("add", "Add") }}
          </button>
        </template>
      </list-nevron-header>

      <div class="card p-4 border-top-0">
        <nevron-search>
          <input type="text" class="form-control" id="search" placeholder="Search ..."
                 v-model="search.query" @keyup="fetchData(1)"/>
        </nevron-search>

        <div class="card-body py-0">
          <div v-if="selectedArray.length > 0" class="check-count">
            <span>{{ selectedArray.length }}</span> {{ translate('selected', 'selected') }}
          </div>
          <per-page-dropdown v-model="perpage" @input="fetchData(1)"/>
        </div>

        <div class="card-body" v-if="items && items.length > 0">
          <!-- TABLE -->
          <table-generic :instance="instance" :items="items" :metaKey="instance.routeName"
              :hasRemove="true" :emitOpenItem="true" :resetSelected="resetSelected" :toggableActive="true"
              @openItem="openItem" @remove="deleteItem" @radioValueChanged="updateRadioValue"
              v-model="selectedArray" />

          <nav class="text-center">
            <v-pagination v-if="response" v-model="response.currentPage" :page-count="response.lastPage" :search="search.current"/>
          </nav>

        </div>
        <div class="card-body" v-else>
          <nevron-search-no-results type="items"/>
        </div>
      </div>
    </div>

    <component ref="create" :is="createComponent" :module="instance" @save="refresh()"/>
    <component ref="show" v-if="showComponent" :is="showComponent" :module="instance" :item="selectedItem" @save="refresh()"/>

  </div>
</template>

<script lang="ts">
import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
import {modulesMetaData} from '@/helpers/constants';
import PerPageDropdown from '@/components/PerPageDropdown.vue';
import vPagination from '@/components/VuePlainPagination.vue';
import ListNevronHeader from '@/components/ListNevronComponents/ListNevronHeader.vue';
import Skeleton from '@/modules/Skeleton.vue';
import NevronEmpty from '@/components/ListNevronComponents/EmptyNevronHeader.vue';
import NevronSearch from '@/components/NevronSearch.vue';
import NevronSearchNoResults from '@/components/NevronSearchNoResults.vue';
import _ from 'lodash';
import {exportData} from '@/helpers/functions';
// @ts-ignore
import EventBus from '../../EventBus';
import TableGeneric from '@/components/NevronTableGeneric.vue';

@Component({
  components: {
    ListNevronHeader,
    Skeleton,
    NevronEmpty,
    NevronSearch,
    NevronSearchNoResults,
    PerPageDropdown,
    vPagination,
    TableGeneric,
  },
})

export default class IndexStaticModules extends Vue {
  @Prop()
  instance: any;

  response: any = null;
  items: IItem[] = [];

  selectedArray: number[] = [];
  allChecked = false;
  resetSelected = false;

  loading = true;

  firstLoad = true;
  perpage = 20;

  search: any = {
    query: '',
    current: '',
  };

  createComponent: any = null;
  showComponent: any = null;
  selectedItem: IItem | null = null;

  searchItems = _.debounce(() => {
    this.fetchData(1);
  }, 400); // fetches data with debounce (ms)

  get instanceMeta(): any {
    // @ts-ignore
    return modulesMetaData[this.instance.routeName];
  }

  get fields() {
    return this.instanceMeta.fields;
  }

  mounted() {
    // set search query from URL
    if (this.$route.query.search) {
      this.search.query = this.$route.query.search;
      this.search.current = this.$route.query.search;
    }
    // set perpage from URL
    if (this.$route.query.perpage) {
      this.perpage = Number(this.$route.query.perpage);
    }
    /* set page from URL - implemented in moduleChanged
    let index = 1;
    if (this.$route.query.page) {
    index = Number(this.$route.query.page);
    }
    */

    this.moduleChanged();

  }

  @Watch('instance')
  moduleChanged() {
    const index = (this.firstLoad && this.$route.query.page) ? Number(this.$route.query.page) : 1;
    this.perpage = 20;
    this.search.current = '';
    this.firstLoad = true;
    this.fetchData(index);
    this.createComponent = () => import(`@/${this.instanceMeta.createComponent}.vue`);
    if (this.instanceMeta.showComponent) {
      this.showComponent = () => import(`@/${this.instanceMeta.showComponent}.vue`);
    }
  }

  fetchData(index: number | null) {
    if (this.firstLoad) {
      this.loading = true;
    }
    return this.instance.fetchData(index, this.search.query, this.perpage)
      .then((response: any) => {
        this.response = response;
        this.items = this.response.data;
        this.search.current = this.search.query;
      })
      .catch((error: any) => {
        console.log(error);
      })
      .finally(() => {
        this.loading = false;
        this.firstLoad = false;
      });
  }

  deleteSelected() {
    if (this.selectedArray.length === 0) {
      return;
    }
    const ids = this.selectedArray.map((el: any) => el.id);
    this.instance.deleteItems(ids)
      .then((response: any) => {
        this.resetSelected = !this.resetSelected;
        this.refresh();
      });
  }

  openItem(item: IItem) {
    if (this.showComponent) {
      this.selectedItem = item;
      // @ts-ignore
      this.$refs.show.$children[0].open();
      return;
    }
    const showPath = (this.instanceMeta.dynamicModule) ? 'items.show' : 'show';
    this.$router.push({
      name: `${this.instance.routeName}.${showPath}`,
      params: {id: String(item.id)},
      query: {lastpage: String(this.response.currentPage), perpage: String(this.perpage)},
    });
  }

  isItemLink(fieldIndex: number) {
    const hasImage = this.fields[0].type === 'image';
    return (!hasImage && fieldIndex === 0) || (hasImage && fieldIndex === 1);
  }

  deleteItem(item: any) {
    this.instance.deleteItem(item.id).then((response: any) => {
      this.refresh();
      if (this.instance.routeName === 'modules') {
        // @ts-ignore
        this.$router.go();
      }
    });
  }

  updateRadioValue(item: IItem, fieldName: string) {
    const data = {id: item.id};
    // @ts-ignore
    data[fieldName] = true;
    this.instance.updateItem(item.id, data);
  }

  refresh() {
    this.fetchData(this.response.currentPage);
  }

  redirect(value: any) {
    if (this.instance.routeName === 'clock' || this.instance.routeName === 'locations') {
      this.refresh();
    } else {
      const showPath = (this.instanceMeta.dynamicModule) ? 'items.show' : 'show';
      this.$router.push({
        name: `${this.instance.routeName}.${showPath}`,
        params: {id: String(value)},
        query: {lastpage: String(this.response.currentPage), perpage: String(this.perpage)},
      });
    }
  }

  @Watch('response.currentPage')
  pageChange() {
    if (this.firstLoad) {
      this.firstLoad = false;
      return;
    }
    this.search.query = this.search.current;
    this.fetchData(this.response.currentPage);
  }

  exportSelected() {
    exportData(this.instanceMeta.tableName, this.selectedArray);
  }

}
</script>
<style scoped>
img {
  width: 35px;
  height: 35px;
  object-fit: cover;
}

th.image {
  width: 35px;
}

th.sort {
  text-align: center;
}
</style>
