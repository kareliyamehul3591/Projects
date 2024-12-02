<template v-on-clickaway="cancel">
  <div>
    <sweet-modal ref="modal" hide-close-button blocking>

      <template v-slot:title>
        <!-- if modal meta display name is defined -->
        <h3 v-if="modalMeta.displayName !== instanceMeta.displayName">{{translate(modalMeta.displayName, modalMeta.displayName)}}</h3>
        <!-- else if instance is dynamic module -->
        <h3 v-else-if="instance.name">{{translate('link', 'Link')}} {{translate(instance.name, instance.name)}} {{translate(instance.itemsName, instance.itemsName)}}</h3>
        <!-- else -->
        <h3 v-else>{{translate('link', 'Link')}} {{translate(modalMeta.displayName, modalMeta.displayName)}}</h3>

        <button class="close-modal" @click="cancel()"></button>
      </template>

      <template>

        <span v-if="response">


          <input class="form-control col-12 my-3" name="search" @keyup="searchItems" v-model="searchQuery" type="search" placeholder="Search"/>
          <div class="row">
            <div v-if="!modalMeta.selectOne && selectedArray.length > 0" class="check-count col-12">
              <span>{{ selectedArray.length }}</span> {{ translate('selected', 'selected') }}
            </div>
          </div>
          <div class="row"  v-if="items && items.length > 0">
              <div class="col-xl-12 col-sm-12">
                <!-- select one -->
                <table-generic v-if="modalMeta.selectOne" :instance="instance" :items="items" :metaKey="metaKey"
                    :selectOpenItem="true" :resetSelected="resetSelected"
                      v-model="selectedItem" />
                <!-- select multiple -->
                <table-generic v-else :instance="instance" :items="items" :metaKey="metaKey"
                    :selectOpenItem="true" :resetSelected="resetSelected"
                      v-model="selectedArray" />
              </div>
              <nav class="text-center w-100">
              <v-pagination v-if="response" v-model="response.currentPage" :page-count="response.lastPage" emitOnly />
              </nav>
          </div>
          
          <nevron-search-no-results v-else :type="translate('items','items')" />

        </span>

        <div v-else class="d-flex mt-5">
          <b-spinner class="mx-auto" variant="primary" role="status"></b-spinner>
        </div>


        </template>

      <template v-slot:button>
            <button class="btn" style="align: left" @click="cancel">
              {{translate("cancel","Cancel")}}
            </button>
            <button class="btn btn-primary" style="align: right" @click="save">
              {{translate("save","Save")}}
            </button>
      </template>
    </sweet-modal>

  </div>
</template>

<script lang="ts">
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import stores from '@/stores';
import Axios from 'axios';
import { mixin as Clickaway } from 'vue-clickaway';
import { SweetModal } from 'sweet-modal-vue';
import vPagination from '@/components/VuePlainPagination.vue';
import NevronSearchNoResults from '@/components/NevronSearchNoResults.vue';
import _ from 'lodash';
import {modulesMetaData} from '@/helpers/constants';
import Skeleton from '@/modules/ModulesSkeleton.vue';
import TableGeneric from '@/components/NevronTableGeneric.vue';

@Component({
  components: {
    SweetModal,
    vPagination,
    NevronSearchNoResults,
    Skeleton,
    TableGeneric,
  },
  mixins: [ Clickaway ],
})

export default class NevronTableModalGeneric extends Vue {
    @Prop({required: true})
    instance!: IModule;

    @Prop({default: null})
    metaKey!: string | null;

    resetSelected = false;

    response: any = null;
    items: any = null;
    selectedItem: IItem | null = null;
    selectedArray: IItem[] = [];
    searchQuery: string = '';

    searchItems = _.debounce((res: any) => {this.fetchData(); }, 400);

    get instanceMeta(): any {
        // @ts-ignore
        return modulesMetaData[this.instance.routeName];
    }

    get modalMeta(): any {
        if (this.metaKey && this.instanceMeta[this.metaKey]) {
            return this.instanceMeta[this.metaKey];
        }
        return this.instanceMeta;
    }

    mounted() {
      this.$watch('$refs.modal.is_open', (oldState, newState) => {
        if (oldState === true && newState === false) {
          if (!this.response) {
            this.fetchData();
          }
        }
      });
    }

    /**
     * Emits 'save' with an array containing their IDs
     */
    save() {
    const emitValue = (this.modalMeta.selectOne) ? this.selectedItem : this.selectedArray;
    this.$emit('save', emitValue);
    this.cancel();
    }

    /**
     * Empties the selection array and closes the popup
     */
    @Watch('$route')
    cancel() {
      this.resetSelected = !this.resetSelected;
      // @ts-ignore
      this.$children[0].close();
    }

    @Watch('response.currentPage')
    fetchData(index = 1) {
        return this.instance.fetchData!(index, this.searchQuery, 10)
            .then((response) => {
            this.response = response;
            this.items = response.data;
            })
            .catch((e) => {
            console.log(e);
            });
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
