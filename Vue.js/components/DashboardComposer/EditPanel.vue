<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button blocking>

    <template v-slot:title>
      <h3>{{translate("manage-collection-panel","Manage Collection Panel")}}</h3>
      <button class="close-modal" @click="cancel()"></button>
    </template>

    <template>
      <form @submit.prevent="next" v-if="localPanel" id="manage-collection">
          <div class="form-group">
            <label for="name">{{translate("panel-title","Panel Title")}}:</label>
<!--            <input type="text" class="form-control" name="name" id="name" v-model="localPanel.name"-->
<!--                   required>-->
            <nevron-input name="name" id="name" parentType="model" type="text" v-model="localPanel.name"
                          :reference-key="'mobile-composer-element' + localPanel.id + '.name'" @handler="notifyParent"/>
          </div>
          <div class="form-check mb-1">
            <input class="form-check-input" type="checkbox" value="1" name="show_name"
                   id="show_name"
                   v-model="localPanel.showName"/>
            <label class="form-check-label" for="show_name">{{translate("show-panel-title","Show Panel Title")}}</label>
          </div>
          <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" value="1" name="active" id="active"
                   v-model="localPanel.active"/>
            <label class="form-check-label" for="active">{{translate("active","Active")}}</label>
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

          <div class="form-group">
            <div class="section-body mt-0">
              <sortable class="card b-radius" v-for="(element, key) in localPanel.elements"
                        v-model="dragData"
                        :key="key"
                        :index="key"
                        drag-direction="horizontal"
                        replace-direction="horizontal"
                        @sortend="sortend($event, localPanel.elements)"
                        :class="localPanel.elementType"
              >
                <i class="icon fa fa-times" @click="removeElement(element, index)" style="z-index: 100;"></i>
                <img :src="element.image" alt="" class="cover b-radius"
                     v-if="element.image"
                >
                <img :src="noImageIcon" class="cover b-radius"
                     alt="No Image found" v-else>
              </sortable>
            </div>
          </div>
      </form>
    </template>

    <template v-slot:button>
      <button @click="cancel()" type="button" class="btn btn-default">{{translate("cancel","Cancel")}}</button>
      <button type="submit" form="manage-collection" class="btn btn-primary">{{translate("update","Update")}}</button>
    </template>

  </sweet-modal>
</template>


<script lang="ts">
  import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
  import {SweetModal} from 'sweet-modal-vue';
  // @ts-ignore
  import Sortable from 'vue-drag-sortable';
  import NevronInput from '@/components/NevronInput.vue';

  @Component({
    components: {
      SweetModal,
      Sortable,
      NevronInput,
    },
  })
  export default class EditPanel extends Vue {
    @Prop()
    panel: any | null;
    @Prop()
    panelIndex: any | null;
    @Prop()
    noImageIcon: any | null;
    localPanel = null;

    dragData: any = {};

    @Watch('panel')
    loadProp() {
      this.localPanel = JSON.parse(JSON.stringify(this.panel));
    }
    mounted() {
      console.log('localPanel', this.localPanel);
    }

    sort(e: any) {
      const {oldIndex, newIndex} = e;
      console.log(oldIndex, newIndex);
    }
    notifyParent(data: any) {
      this.$emit('openTranlator', data);
    }
    sorting(e: any) {
      const {oldIndex, newIndex} = e;
      this.rearrange(this.panel.elements, oldIndex, newIndex);
    }

    sortend(e: any, list: any) {
      const {oldIndex, newIndex} = e;
      this.rearrange(list, oldIndex, newIndex);
    }

    rearrange(array: any, oldIndex: any, newIndex: any) {
      if (oldIndex > newIndex) {
        array.splice(newIndex, 0, array[oldIndex]);
        array.splice(oldIndex + 1, 1);
      } else {
        array.splice(newIndex + 1, 0, array[oldIndex]);
        array.splice(oldIndex, 1);
      }
    }

    updateRatio(option: string) {
      // @ts-ignore
      this.localPanel.elementType = option;
    }

    isSelected(ration: string) {
      // @ts-ignore
      return this.localPanel.elementType === ration;
    }

    cancel() {
      if (localStorage.getItem('createPanel') === 'true') {
        this.$emit('close');
      }
      // @ts-ignore
      this.$children[0].close();
    }

    next() {
      const output = {
        panel: JSON.parse(JSON.stringify(this.localPanel)),
        panelIndex: this.panelIndex,
      };
      this.$emit('next', output);
      // @ts-ignore
      this.$children[0].close();
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
