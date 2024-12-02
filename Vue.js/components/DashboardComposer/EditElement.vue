<template v-on-clickaway="cancel">
  <sweet-modal ref="editElement" hide-close-button blocking>

  <template v-slot:title>
    <h3>{{translate("manage-banner-slide","Manage Banner Slide")}}</h3>
    <button class="close-modal" @click="cancel()"></button>
  </template>

    <template>
      <form @submit.prevent="next" id="manage-slider-form">
        <div class="text-left" v-if="localElement">
          <div class="form-group">
            <label for="name">{{translate("slide-title","Slide Title")}}:</label>
<!--            <input type="text" class="form-control" name="name" id="name" v-model="localElement.name"-->
<!--                   required>-->
            <nevron-input name="name" id="name" parentType="model" type="text" v-model="localElement.name"
                            :reference-key="'mobile-composer-slider' + localElement.id + '.name'" @handler="notifyParent"/>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="show_name" id="show_name"
                   v-model="localSlider.showName"/>
            <label class="form-check-label" for="show_name">{{translate("show-panel-title","Show panel Title")}}</label>
          </div>
        </div>
      </form>
    </template>

    <template v-slot:button>
      <button @click="cancel()" type="button" class="btn btn-default">{{translate("cancel","Cancel")}}</button>
      <button type="submit" form="manage-slider-form" class="btn btn-primary">{{translate("save","Save")}}</button>
    </template>
  </sweet-modal>
</template>


<script lang="ts">
  import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
  import {SweetModal} from 'sweet-modal-vue';
  import NevronInput from '@/components/NevronInput.vue';

  @Component({
    components: {
      SweetModal,
      NevronInput,
    },
  })
  export default class EditElement extends Vue {
    @Prop()
    element: any | null;
    @Prop()
    elementIndex: any| null;
    @Prop()
    sliders: any|null;

    localElement = null;
    localSlider = null;
    dragData: any = {};
    @Watch('element')
    setInitValues() {
      this.localElement = this.element;
      this.localSlider = this.sliders;
      console.log('localPanel',  this.localSlider);
      console.log('element',  this.localElement);
    }
    mounted() {
      console.log('localPanel',  this.localSlider);
      console.log('element',  this.localElement);
    }
    cancel() {
      // @ts-ignore
      this.$children[0].close();
    }
    notifyParent(data: any) {
      this.$emit('openTranlator', data);
    }

    next() {
      const output = {
        element: this.localElement,
        elementIndex: this.elementIndex,
        // @ts-ignore
        sliderShowName: this.localSlider.showName,
      };
      console.log('element', output);
      this.$emit('next', output);
      // this.clearData();
      // @ts-ignore
      this.$children[0].close();
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
    border: 2px dashed #007ace !important;
  }


  input[type=radio] {
    height: 15px;
    width: 15px;
  }
</style>
