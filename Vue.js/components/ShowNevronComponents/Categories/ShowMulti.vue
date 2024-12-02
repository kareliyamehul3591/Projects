<template>
  <div class="container">
    <skeleton v-if="!response" />
    <div v-else>

      <nevron-header :item="category"
        @remove="deleteCategory(category)"
        @refresh="refresh(category.id)"
        @save="onSubmit"
        @cancel="$router.go(-1)"
        >
        <template #breadcrum-title>
          <template v-for="(parentName, parentId, index) in category.parents">
            <router-link
              :to="{
                     name: `${module.path}.categories`,
                     params: { moduleId: String(module.id), id: parentId },
                     }"
              class="text-secondary"
              :key="index"
            >
              <template v-if="index > 0"> / </template>
              {{ translate(parentName, parentName) }}
            </router-link>
          </template>
        </template>
        <template #title>{{ categoryName }}</template>
      </nevron-header>
      <div class="row">
        <div class="col-lg-8 col-md-12">
          <form @submit.prevent="onSubmit">
            <div class="card mb-4">
              <div class="card-header container-fluid">
                <div class="row">
                  <div class="col-md-10">
                    {{ translate("general", "General") }}
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="form-group">
                  <label for="name">{{ translate("name", "Name") }}</label>
                  <nevron-input name="name" id="name" type="text" v-model="category.name" :reference-key="'module' + module.id + '.category' + category.id + '.name'" />
                </div>
                <div class="form-group">
                  <label for="sort-number">{{ translate("sort-number", "Sort number") }}</label>
                  <input class="col-sm-12 col-xl-12 form-control" name="sort-number" id="sort-number" type="number" v-model="category.sort" />
                </div>
                <span>
                  <label>{{ translate("description:", "Description:") }} </label>
                  <nevron-textbox
                    :referenceKey="
                              'module' + module.id + '.item' + category.id + '.description'
                              "
                    v-model="category.summary"
                    name="description"
                    id="description"
                  />
                </span>
              </div>
            </div>
          </form>
        </div>
        <!-- IMAGE -->
        <div class="col-lg-4 col-md-12">
          <nevron-image-field  :module="module" :image="category.image" @selected="imageSelected" />
        </div>
        <!-- SUBCATEGORIES -->
        <div class="col-lg-8 col-md-12 mb-4">
          <div class="card">
            <div class="card-header d-flex align-items-center py-2 px-4 justify-content-between">
              {{ translate(module.categoriesName, module.categoriesName) }}
              <button type="button" class="btn btn-primary text-white btn-sm" @click="$refs.create.$children[0].open()">
                <icon type="add" />
              </button>
            </div>
            <div class="card-body">
              <table-generic :instance="module" :items="category.subcategories" metaKey="categories" :hasRemove="true"
                :emitOpenItem="true" @openItem="openCategory" @remove="deleteCategory"/>
            </div>
          </div>
        </div>
        <!-- SUBITEMS -->
        <div class="col-lg-8 col-md-12 mb-4">
          <div class="card">
            <div class="card-header d-flex align-items-center py-2 px-4 justify-content-between">
              {{ translate(module.itemsName, module.itemsName) }}
              <button type="button" class="btn btn-primary text-white btn-sm" @click="getItemsForModule()">
                <icon type="add" />
              </button>
            </div>
            <div class="card-body">
              <table-generic :instance="module" :items="category.subitems" :hasUnlink="true"
                :emitOpenItem="true" @openItem="openItem" @unlink="detachItem"/>
            </div>
          </div>
        </div>
      </div>
      <nevron-footer>
        <template #cancel>
          <div class="text-right mr-3">
            <button @click="$router.go(-1)" class="btn btn-secondary" style="color: #637381;">{{ translate("cancel", "Cancel") }} </button>
          </div>
        </template>
        <template #save>
          <div class="text-right">
            <button class="btn btn-primary" @click="onSubmit">
              {{ translate("save", "Save") }}
            </button>
          </div>
        </template>
      </nevron-footer>
      <table-modal-generic ref="attach" :instance="module" metaKey="modalAttach" @save="addItemsToCategory" />
      <create-category ref="create" :module="module" :parentId="category.id" />
    </div>
  </div>
</template>


<script lang="ts">
  import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
  import virtualList from 'vue-virtual-scroll-list';
  import stores from '@/stores';
  import Axios from 'axios';
  import NevronHeader from '@/components/ShowNevronComponents/NevronHeader.vue';
  import TableModalGeneric from '@/components/NevronTableModalGeneric.vue';
  import NevronInput from '@/components/NevronInput.vue';
  import axiosInstance from '@/helpers/axios';
  import session from '@/stores/Session';
  import NevronTextbox from '@/components/NevronTextbox.vue';
  import Skeleton from '@/modules/Skeleton.vue';
  import NevronFooter from '@/components/ShowNevronComponents/NevronFooter.vue';
  import NevronSearchNoResults from '@/components/NevronSearchNoResults.vue';
  import CreateCategory from './CreateMulti.vue';
  import NevronImageField from '@/components/NevronImageField.vue';
  import TableGeneric from '@/components/NevronTableGeneric.vue';

  @Component({
    components: {
      virtualList,
      NevronHeader,
      NevronInput,
      NevronTextbox,
      Skeleton,
      NevronFooter,
      NevronSearchNoResults,
      CreateCategory,
      NevronImageField,
      TableModalGeneric,
      TableGeneric,
    },
  })
  export default class ShowMulti extends Vue {
    @Prop()
    module!: IModule;

    response: any = null;
    category: ICategoriesMulti | null = null;
    searchQuery: string = '';
    indexList: number[] = [];
    allChecked: boolean = false;

    source: any;
    fullResponse: any = null;
    moduleItems: any[] = [];

    get categoryName() {
      if (!this.category?.parentId) {
        return Vue.prototype.translate(this.module.name, this.module.name) + ' ' + Vue.prototype.translate('root', 'Root');
      }
      return Vue.prototype.translate(this.category?.name, this.category?.name);
    }

    mounted() {
      this.refresh(this.$route.params.id);
    }

    refresh(id: number | string) {
      return this.module.getSpecificCategory!(Number(id) || 0)
        .then((response) => {
          this.response = response;
          this.category = response.data;
          // @ts-ignore
          this.name = Vue.prototype.translate(this.category.name, this.category.name);
          window.scrollTo(0, 0);
        })
        .catch((e) => {
          console.log(e);
        });
    }

    @Watch('$route', { immediate: true, deep: true })
    changePage() {
      this.refresh(this.$route.params.id);
    }

    onSubmit() {
      if (this.category) {
        return this.module.updateCategory!(this.category.id!, this.category)
          .then((response: any) => {
            this.refresh(this.category?.id!);
          })
          .catch((e: any) => {
            console.log(e);
          });
      }
    }

    imageSelected(image: IMediaItem) {
      if (this.category) {
        this.category.imageId = (image) ? image.id : null;
      }
    }

    addItemsToCategory(newItemsList: any) {
      if (this.category && this.category.subitems) {
        const subitemIds = this.category.subitems.map((item: any) => item.id);
        const uniqueNewItemsList = newItemsList.filter((item: any) => !subitemIds.includes(item.id));
        this.category.subitems = [...this.category.subitems, ...uniqueNewItemsList];
      }
    }

    deleteCategory(cat: IPagesCategory) {
        return this.module.deleteCategory!(cat.id!)
          .then((response: any) => {
            if (cat.id === this.category?.id) {
              this.$router.push({
                name: `${this.module.path}.categories`,
                params: {
                  moduleId: String(this.module.id),
                  id: String(this.category.parentId),
                },
              });
            } else {
              this.refresh(this.$route.params.id);
            }
          })
          .catch((error: any) => {
            console.log(error);
          });
    }

    detachItem(item: IItem) {
      this.category!.subitems = this.category!.subitems!.filter((el: any) => el !== item);
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

    updateCheck() {
      if (this.itemIds.every((id: number) => this.indexList.includes(id))) {
        this.allChecked = true;
      } else {
        this.allChecked = false;
      }
    }

    get itemIds(): number[] {
      return (this.category as ICategories).children!.map((item: IItem) => item.id);
    }

    getItemsForModule() {
      // @ts-ignore
      this.$refs.attach.$children[0].open();
    }

    openItem(item: IItem) {
      this.$router.push({name: `${this.module.path}.items.show`, params: { id: String(item.id) }});
    }

    openCategory(category: ICategories) {
      this.$router.push({name: `${this.module.path}.categories`, params: { id: String(category.id) }});
    }

  }
  </script>

