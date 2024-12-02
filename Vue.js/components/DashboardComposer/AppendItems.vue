<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button blocking>
  <template v-slot:title>
    <h3>{{translate("append-items","Append Items")}}</h3>
    <button class="close-modal" @click="cancel()"></button>
  </template>

    <template>
        <form @submit.prevent="next" id="add-items-form" class="mt-2">
          <div class="form-group text-left">
            <label for="eService">{{translate("select-eservice","Select eService")}}:</label>
            <select class="form-control" name="eService" required
                    id="eService" v-model="selectedModule" @click="loadModules" @change="loadModuleCategories">
              <option v-for="(service, index) in modules" :value="service" :key="index">
                {{ translate(service.name,service.name) }}
                <template v-if="!service.active">&nbsp;({{translate("disabled","disabled")}})</template>
              </option>
            </select>
          </div>
          <div class="form-group text-left" v-if="selectedModule && categories && categories.length > 0 && checkFloatingSection === true ">
            <label for="category">{{translate("select-category","Select Category")}}:</label>
            <select class="form-control" name="category"  id="category"
                    v-model="selectedCategory" @change="loadCategoryItems">
              <option :value="null"> {{translate("none","None")}}</option>
              <template v-if="categories && categories.length > 0">
                <option v-for="(category, index) in categories" :value="category" :key="index">
                  {{ translate(category.name,category.name) }}
                </option>
              </template>
            </select>
          </div>
          <div class="form-group text-left" v-if="selectedCategory && items && items.length > 0">
            <label for="items">{{translate("select-item","Select Item")}}:</label>
            <select class="form-control" name="item"  id="items"
                    v-model="selectedItem">
              <option :value="null"> {{translate("none","None")}}</option>
              <template v-if="items && items.length > 0">
                <option v-for="(item, index) in items" :value="item" :key="index">
                  {{ translate(item.name,item.name)}}
                </option>
              </template>
            </select>
          </div>
        </form>
    </template>

    <template v-slot:button>
      <a href="javascript: void(0)" class="btn btn-link-gray mr-3"
          @click="cancel()">{{translate("cancel","Cancel")}}</a>
      <button type="submit" form="add-items-form" class="btn btn-primary"  >{{translate("next","Next")}}</button>
    </template>
  </sweet-modal>
</template>


<script lang="ts">
    import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
    import {SweetModal} from 'sweet-modal-vue';
    import axiosInstance from '../../helpers/axios';
    import session from '@/stores/Session';
    import stores from '@/stores';
    import {DashboardPanel, PanelElement} from '@/stores/Dashboard';
    import {createItemPanelElement, createModulePanelElement} from './helpers';
    @Component({
        components: {
            SweetModal,
        },
    })
    export default class AppendItems extends Vue {
        @Prop()
        panelIndex: any;
        @Prop()
        checkFloatingSection: any;

        option: any = 'collection';
        modules: any = '';
        saveservices: [] = [];
        categories: ICategories[] = [];
        items: IItem[] = [];
        selectedModule: IModule | null = null;
        selectedCategory: ICategories | null = null;
        selectedItem: any = '';
        responseChannels: any = '';

        loadModuleCategories() {
            this.items = [];
            this.categories = [];
            if (this.selectedModule && typeof this.selectedModule.getAllCategories !== 'undefined') {
              this.selectedModule.getAllCategories()
                .then((response: any) => this.categories = response.data);
            }
        }
        loadModules() {

          this.modules = stores.modules.models;
          stores.GuestFlow.listofServices()
            .then((response) => {
              console.log('res', response.data);
              for (const index in response.data) {
                if (index) {
                  // @ts-ignore
                  this.modules.push(response.data[index]);
                }
              }
              // this.modules = response.data;
            });
        }

        loadCategoryItems() {
            this.items = [];
            if (this.selectedModule && this.selectedCategory && typeof this.selectedModule!.getSpecificCategory !== 'undefined') {
              this.selectedModule.getSpecificCategory(this.selectedCategory.id!)
                .then((response: any) => this.items = response.data.children);
            }
        }

        cancel() {
          this.selectedModule = null;
          this.selectedCategory = null;
          this.selectedItem = null;
          // @ts-ignore
          this.$children[0].close();
        }

        next() {
          console.log('selected Module', this.selectedModule);
          if (!this.selectedModule) {
            return;
          }
          const elements: IPanelElement[] = [];
          if (this.selectedItem) {
            console.log('selected Item', this.selectedItem);
          // ADD ITEM
            elements.push(createItemPanelElement(this.selectedModule, this.selectedItem));

          } else if (this.selectedCategory) {
          // ADD CATEGORY
            for (const item of this.items) {
              elements.push(createItemPanelElement(this.selectedModule, item));
            }

          } else {
            // ADD ESERVICE
            const panelElement = createModulePanelElement(this.selectedModule);
            elements.push(panelElement);
          }
          this.$emit('next', elements, this.selectedModule);
          this.cancel();
        }

    }
</script>

<style scoped>
  .ratio-container {
    width: 100%;
    padding: 20px;
  }

  .div-one {
    border-radius: 15px;
    height: 150px;
    width: 100px;
    border: 1px dashed black;
    display: inline-flex;
    padding: 60px 0 0 35px;
    margin: 5px;
    cursor: pointer;
  }

  .div-two {
    border-radius: 15px;
    height: 90px;
    width: 150px;
    margin: 5px;
    border: 1px dashed black;
    display: inline-flex;
    padding: 30px 0 0 55px;
    cursor: pointer;
  }

  .div-three {
    border-radius: 15px;
    height: 90px;
    width: 70px;
    margin: 5px;
    border: 1px dashed black;
    display: inline-flex;
    padding: 30px 0 0 25px;
    cursor: pointer;
  }

  .active {
    border: 2px dashed #007ace;
  }


  input[type=radio] {
    height: 15px;
    width: 15px;
  }
</style>
