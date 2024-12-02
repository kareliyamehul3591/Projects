<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button blocking>

    <template v-slot:title>
      <h3>{{translate("add-list-of-items","ADD LIST OF ITEMS")}}</h3>
      <button class="close-modal" @click="cancel()"></button>
    </template>

    <template>
      <div class="form-group">
        Add items of the selected service category to a new collection panel on the dashboard.
      </div>

      <form @submit.prevent="next" id="add-panel-form">
        <div class="form-group text-left">
          <label for="eService">{{translate("select-eservice","Select eService")}}:</label>
          <select class="form-control" name="eService" required
                  id="eService" v-model="selectedModule" @click="loadModules" @change="loadModuleCategory">
            <option v-for="(mod, index) in modules" :value="mod" :key="index">
              {{ translate(mod.name,mod.name) }}
              <template v-if="mod.name !== 'GuestFlow' && !mod.active">&nbsp;({{translate("disabled","Disabled")}})</template>
            </option>
          </select>
        </div>
        <div class="form-group text-left">
          <label for="category">{{translate("select-category","Select Category")}}:</label>
          <select class="form-control" name="category" required id="category"
                  v-model="selectedCategory">
            <template v-if="selectedModule && categories && categories.length > 0" >
              <option v-for="(category, index) in categories"  :value="category" :key="index">
                {{ translate(category.name,category.name) }}
              </option>
            </template>
          </select>
        </div>
        <div class="form-group text-left">
          <label>{{translate("how-many-to-add","How many to add")}}:</label>
          <br>
          <label class="radio-inline ml-3 font-weight-normal">
            <input type="radio" value="all" id="all" name="quantity"
                   @click="updateQuantity('all')" checked>{{translate("all","All")}}
          </label>
          <br>
          <label class="radio inline text-left ml-3 font-weight-normal">
            <input type="radio" value="10" id="10" name="quantity" @click="updateQuantity('10')">{{translate("first-10","First 10")}}
          </label>
        </div>
        <div class="form-group text-left">
          <label for="sorting">{{translate("select-element-ratio","Select Element Ratio")}}:</label>
          <div class="ratio-container">
            <div class="sample card-poster" :class="{active: isSelected('card-poster')}"
                 @click="updateRatio('card-poster')">
              2:3
            </div>
            <div class="sample card-cover" :class="{active: isSelected('card-cover')}"
                 @click="updateRatio('card-cover')">
              16:9
            </div>
            <div class="sample image" :class="{active: isSelected('image')}"
                 @click="updateRatio('image')">
              1:1
            </div>
            <div class="sample card-poster-lg" :class="{active: isSelected('card-poster-lg')}"
                 @click="updateRatio('card-poster-lg')">
              2:3 <small class="ml-1">({{translate('large', 'large')}})</small>
            </div>
          </div>
        </div>
      </form>
    </template>

    <template v-slot:button>
      <a href="javascript: void(0)" class="btn btn-link-gray mr-3"
         @click="cancel()">{{translate("cancel","Cancel")}}</a>
      <button type="submit" form="add-panel-form" class="btn btn-primary">{{translate("next","Next")}}</button>
    </template>
  </sweet-modal>
</template>


<script lang="ts">
    import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
    import {SweetModal} from 'sweet-modal-vue';
    import axiosInstance from '../../helpers/axios';
    import session from '@/stores/Session';
    import stores from '@/stores';
    import VODModule from '@/stores/modules/VODs';
    import {DashboardPanel, PanelElement} from '@/stores/Dashboard';
    import {dynamicModules} from '@/helpers/constants';
    import {createItemPanelElement} from './helpers';

    @Component({
        components: {
            SweetModal,
        },
    })
    export default class Items extends Vue {
        option: any = 'collection';
        modules = stores.modules.models.filter((mod: IModule) => dynamicModules.includes(mod.type));
        categories: ICategories[] = [];
        panelElements: any = '';
        selectedModule: any = '';
        selectedCategory: any = '';
        ratio = 'card-poster';
        quantity = 'all';

        loadModules() {
          this.modules = stores.modules.models.filter((mod: IModule) => dynamicModules.includes(mod.type));
        }

  loadModuleCategory(service: any) {
    console.log('res', this.selectedModule);
    this.categories = [];
    // commit for guestflow
    if (this.selectedModule.name === 'GuestFlow') {
      // @ts-ignore
      stores.GuestFlow.listofServices()
        .then((response) => {
          console.log('res', response);
          for (const index in response.data) {
            // @ts-ignore
            if (index && ( response.data[index].sort === 4 || response.data[index].sort === 8)) {
              // @ts-ignore
              this.categories.push( response.data[index]);
            }
          }
        });
    } else {
      this.selectedModule.getAllCategories()
        .then((response: any) => {
          this.categories = response.data;
        });
    }

  }

  mounted() {
    const guestflow = {name: 'GuestFlow'};
    for (const index in guestflow) {
      if (index) {
        // @ts-ignore
        this.modules.push(guestflow);
      }
    }

  }

  cancel() {
    this.selectedModule = null;
    this.selectedCategory = null;
    // @ts-ignore
    this.$children[0].close();
  }

  update(option: string) {
    this.option = option;
  }

  updateRatio(option: string) {
    this.ratio = option;
  }

  updateQuantity(option: string) {
    this.quantity = option;
  }

  isSelected(ratio: string) {
    return this.ratio === ratio;
  }

  next() {
    const panel = new DashboardPanel();
    panel.name = `${this.selectedCategory.name}`;
    panel.elementType = this.ratio;
    panel.panelType = 'row';
    panel.position = 'top';
    panel.active = true;
    if (this.selectedModule.name === 'GuestFlow') {
      if (this.selectedCategory.sort === 4) {
        stores.Library.listofLibrary()
          .then((response) => {
            console.log('res', response);
            // this.panelElements = response.data;
            let items = response.data;
            const itemsCount = this.quantity === 'all' ? items.length : 10;
            items = items.slice(0, itemsCount);
            const key = 'moduleId';
            // @ts-ignore
            this.selectedModule[key] = this.selectedCategory.sort;
            for (const item of items) {
              const panelElement = createItemPanelElement(this.selectedModule, item);
              panel.elements.push(panelElement);
            }
            this.$emit('next', panel);
            this.cancel();
          });
      } else {
        stores.Promotions.listofPromotions()
          .then((response) => {
            console.log('res', response);
            // this.panelElements = response.data;
            let items = response.data;
            const itemsCount = this.quantity === 'all' ? items.length : 10;
            items = items.slice(0, itemsCount);
            const key = 'moduleId';
            // @ts-ignore
            this.selectedModule[key] = this.selectedCategory.sort;
            for (const item of items) {
              const panelElement = createItemPanelElement(this.selectedModule, item);
              panel.elements.push(panelElement);
            }
            this.$emit('next', panel);
            this.cancel();
          });

      }

    } else {
      if (this.selectedModule && this.selectedCategory && typeof this.selectedModule!.getSpecificCategory !== 'undefined') {
        this.selectedModule.getSpecificCategory(this.selectedCategory.id!)
          .then((response: any) => {
            let items = (this.selectedModule.routeName === 'pages') ? response.data.subitems : response.data.children;
            const itemsCount = this.quantity === 'all' ? items.length : 10;
            console.log('itemsCount', itemsCount);
            items = items.slice(0, itemsCount);
            for (const item of items) {
              const panelElement = createItemPanelElement(this.selectedModule, item);
              panel.elements.push(panelElement);
            }
            this.$emit('next', panel);
            this.cancel();
          });
      }
    }
  }

}
</script>

<style scoped>
.ratio-container {
  width: 100%;
  padding: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}
.sample {
  border-radius: 15px;
  border: 1px dashed black;
  margin: 5px;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  cursor: pointer;

}

.card-poster {
  height: 135px;
  width: 90px;
}

.card-cover {
  height: 101px;
  width: 180px;
}

.image {
  height: 90px;
  width: 90px;
}
.card-poster-lg {
  height: 193px;
  width: 128px;
}

.active {
  border: 2px dashed #007ace !important;
}


input[type=radio] {
  height: 15px;
  width: 15px;
}
</style>
