<template>
  <div class="container">
    <skeleton v-if="!response" />

    <div v-else-if="category">

      <nevron-header :item="category"
        @remove="deleteCategory"
        @refresh="refresh(category.id)"
        @save="onSubmit"
        @cancel="$router.push({name: `${module.path}.categories`, params: {moduleId: String(module.id)}})"
        @breadcrumClick="$router.push({name: `${module.path}.categories`, params: {moduleId: String(module.id)}})"
        @next="$router.push({name: `${module.path}.categories.show`, params: {id: category.neighbours.next}})"
        @prev="$router.push({name: `${module.path}.categories.show`, params: {id: category.neighbours.prev}})"
        >
        <template #breadcrum-title>
            {{translate(module.name, module.name)}} {{ translate(module.categoriesName, module.categoriesName)}}
        </template>
      </nevron-header>

    <div class="row">
        <div class="col-lg-8 col-md-12">
          <form @submit.prevent="onSubmit">
            <div class="card mb-4">

              <div class="card-header container-fluid">
                <div class="row">
                  <div class="col-md-10">
                    {{translate("general","General")}}
                  </div>
                  <div class="col-md-2 float-right text-right">

                    <div class="custom-control custom-switch">
                      <input type="checkbox" class="custom-control-input" id="active" v-model="category.active">
                      <label class="custom-control-label" for="active"></label>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-body">
                <div class="form-group">
                  <label for="name">{{translate("name","Name")}}</label>
                  <nevron-input name="name" id="name" type="text" v-model="category.name"
                                :reference-key="'module' + module.id + '.category' + category.id + '.name'"/>
                </div>
                <div class="form-group">
                  <label for="sort-number">{{translate("sort-number","Sort number")}}</label>
                  <input class="col-sm-12 col-xl-12 form-control" name="sort-number" id="sort-number" type="number"
                         v-model="category.sort"/>
                </div>
                <span v-if="category.description">
                <label>{{translate("description:","Description:")}} </label>
                <nevron-textbox :referenceKey="'module' + module.id + '.category' + category.id + '.description'"
                                v-model="category.description"  name="description" id="description"/>
                </span>
              </div>

            </div>
          </form>
        </div>

        <!-- IMAGE -->
        <div class="col-lg-4 col-md-12">
            <div class="card mb-4" @dragenter="dragEnter(1)" @dragleave="dragLeave(1)">
                <div class="card-header d-flex align-items-center py-2 px-4 justify-content-between">
                    <span>
                        {{translate("image","Image")}}
                    </span>
                    <button type="button" class="btn btn-primary text-white btn-sm"
                            @click="$refs.attachImage.$children[0].open()"><icon type="edit" :hasTooltip="false"></icon>
                    </button>
                </div>
                <div class="upload">
                    <div class="card-body upload">
                    <vue-transmit id="upload" :class="{'upload-drag-and-drop': dragOver}" @success="transmitFinish"
                                    :clickable="false" tag="section" ref="uploader" :url="getFilePath()" :headers="getHeaders()">
                        <!-- TODO če namesto slike "drag & drop"-aš svojo sliko, se ti pojavi -->
                        <!-- Spodaj je gumbek, za zamenjavo, desno zgoraj mora biti X, ki ti sliko odstrani -->
                        <div>
                        <div class="instructions" @dragenter="dragEnter(1)"></div>
                        <div v-if="category.image && category.image.imageUrl" class="text-center mb-3">
                            <div class="detach-img">
                            <span @click="attachWithImage(null)" class="fas fa-times"></span>
                            </div>
                            <img class="img-fluid img-background-color" :src="category.image.imageUrl"
                                height="250px" width="250px" style="width: 250px; height: 250px; " />
                        </div>
                        <div v-else class="text-center mb-3" >
                            <img class="img-fluid" src="@/assets/images/upload-png.png"  width="50%"/>
                        </div>
                        <p class="text-center">{{translate("drag-photos-here","Drag photos here")}} <br> {{translate("or","or")}} <br> <button class="btn btn-link p-0 b-0" type="button"
                        @click="$refs.attachImage.$children[0].open()">{{translate("find-images-in-media-library","find images in Media library")}}</button>.</p>
                        </div>
                    </vue-transmit>
                    </div>
                </div>
            </div>
        </div>

        <!-- ITEMS -->
        <div class="col-lg-8 col-md-12 mb-4" >
            <div class="card">
                <div class="card-header d-flex align-items-center py-2 px-4 justify-content-between">
                    {{translate(module.itemsName, module.itemsName)}}
                    <button type="button" class="btn btn-primary text-white btn-sm" @click="openAttachModal(); ">
                        <icon type="add" :hasTooltip="false"></icon>
                    </button>
                </div>
                <div class="card-body">
                {{translate("short-explanation-of-sources.","Short explanation of sources.")}}
                </div>
                <div class="card-body" v-if="category.children.length === 0">
                {{translate("no-data.-add-some?","No data. Add some?")}}
                </div>
                <div class="card-body" v-else>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                              <th class="align-middle text-center">
                                  <input type="checkbox" v-model="allChecked" @click="checkAll" />
                                </th>
                                <th class="align-middle text-center">{{translate("image","Image")}}</th>
                                <th class="align-middle text-center">{{translate("name","Name")}}</th>
                                <th class="align-middle text-center">{{translate("id","ID")}}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in category.children" :key="index">

                            <td class="align-middle text-center">
                                <label :for="'ch-'+item.id" class="polaris-check">
                                <input type="checkbox" :name="'ch-'+item.id" :id="'ch-'+item.id" :value="item.id"
                                        v-model="indexList" @change="updateCheck(account.id)"/>
                                <span></span>
                                </label>
                            </td>

                            <td v-if="item.image && item.image.imageUrl" align="center" style="vertical-align: middle;">
                                <img :src="item.image.imageUrl" style="height:50px;" :alt="translate('pciture','Picture')" class="img-background-color">
                            </td>
                            <td v-else align="center" style="vertical-align: middle;">
                                <img src="../../../../assets/blank32.png" :alt="translate('Picture','Picture')">
                            </td>

                            <td class="align-middle text-center">
                                <router-link :to="{name: `${module.path}.items.show`, params:{id: item.id}}">{{ translate(item.name,item.name) }}</router-link>
                            </td>

                            <td class="align-middle text-center">{{ item.id }}</td>

                            <td class="align-middle text-center">
                                <a href="javascript: void(0)"><i class="fa fa-times float-right text-danger" @click="detachItem(item)"></i></a>
                            </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
      <nevron-footer>

        <template #cancel>
          <div class="text-right mr-3 ">
          <router-link
            :to="{name: `${module.path}.categories`}"
            class="btn btn-secondary"
            style="color: #637381">{{ translate("cancel", "Cancel") }}
          </router-link>
          </div>
        </template>
        <template #save>
          <div class="text-right ">
            <button class="btn btn-primary" @click="onSubmit">{{
                translate("save", "Save")
              }}
            </button>
          </div>
        </template>

      </nevron-footer>
    <table-modal-generic ref="attach" :instance="module" metaKey="modalAttach" @save="addItemsToCategory" />
    <attach-image ref="attachImage" @attach="attachWithImage"/>
  </div>
  </div>
</template>

<script lang="ts">
import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
import virtualList from 'vue-virtual-scroll-list';
import stores from '@/stores';
import Axios from 'axios';
import NevronHeader from '@/components/ShowNevronComponents/NevronHeader.vue';
import TableModalGeneric from '@/components/NevronTableModalGeneric.vue';
import NevronInput from '@/components/NevronInput.vue';
import axiosInstance from '@/helpers/axios';
import session from '@/stores/Session';
import AttachImage from '@/modules/Media/Attach.vue';
import NevronTextbox from '@/components/NevronTextbox.vue';
import Skeleton from '@/modules/Skeleton.vue';
import NevronFooter from '@/components/ShowNevronComponents/NevronFooter.vue';

@Component({
  components: {
    virtualList,
    AttachImage,
    NevronHeader,
    NevronInput,
    NevronTextbox,
    Skeleton,
    NevronFooter,
    TableModalGeneric,
  },
})
export default class CategoryDetails extends Vue {
  @Prop()
  module!: IModule;

  response: any = null;
  category: ICategories | null = null;
  searchQuery: string = '';
  indexList: number[] = [];
  allChecked: boolean = false;

  searchInProgress: boolean = false;
  CancelToken: any = Axios.CancelToken;
  source: any;
  fullResponse: any = null;
  dragOver: boolean = false;
  preLeave: boolean = false;
  moduleItems: any[] = [];

  /**
   *
   */
  @Watch('$route', {immediate: true, deep: true})
  changePage() {
    this.refresh(this.$route.params.id);
  }
  dragEnter(num: number) {
    this.dragOver = true;
    this.preLeave = true;
    console.log('ENTER', num);
  }

  dragLeave(num: number) {
    if (this.preLeave) {
      this.preLeave = false;
    } else {
      this.preLeave = false;
      this.dragOver = false;
    }
    console.log('LEAVE', num);
  }

  onSubmit() {
    if (this.category) {
      return this.module.updateCategory!(this.category.id!, this.category)
        .then((response: any) => {
          this.refresh(this.$route.params.id);
        })
        .catch((e: any) => {
          console.log(e);
        });
    }
  }

  getFilePath(): string {
    return axiosInstance.defaults.baseURL + stores.File.uploadUrl();
  }

  /**
   *
   */
  getHeaders() {
    return stores.File.getHeader();
  }

  /**
   *
   */
  transmitFinish(files: any, res: any) {
    this.preLeave = false;
    this.dragOver = false;
    this.attachWithImage({result: res});
  }

  attachWithImage(imageObj: any) {
    if (this.category) {
      if (imageObj) {
        this.category.image = imageObj.result;
        this.category.imageId = imageObj.result.id;
      } else {
        this.category.image = null;
        this.category.imageId = null;
      }
    }
  }

  addItemsToCategory(newItemsList: any) {
    if (this.category && this.category.children) {
      const childrenIds = this.category.children.map((item: any) => item.id);
      const uniqueNewItemsList = newItemsList.filter((item: any) => !childrenIds.includes(item.id));
      // @ts-ignore
      this.category.children = [...this.category.children, ...uniqueNewItemsList];
    }
  }

  deleteCategory() {
    if (this.category && this.category.id) {
      return this.module.deleteCategory!(this.category.id)
        .then((response: any) => {
          this.$router.push({name: `${this.module.path}.categories`, params: {moduleId: String(this.module.id)}});
        })
        .catch((error: any) => {
          console.log(error);
        });
    }
  }

  detachItem(item: IItem) {
      this.category!.children = this.category!.children!.filter((el: any) => el !== item);
  }

  refresh(id: string) {
    return this.module.getSpecificCategory!(Number(id))
      .then((response) => {
        console.log(response);
        this.response = response;
        this.category = response.data;
      })
      .catch((e) => {
        console.log(e);
      });
  }

  mounted() {
    console.log(`${this.module.path}.categories`);
    this.refresh(this.$route.params.id);
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

  openAttachModal() {
    console.log(this.$refs);
    // @ts-ignore
    this.$refs.attach.$children[0].open();
  }

}
</script>
