<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button id="uploadedImagePopup" backdrop="static" blocking>

    <template v-slot:title>
      <h3>{{translate("add-new-section","ADD NEW SECTION")}}</h3>
      <button class="close-modal" @click="cancel()"></button>
    </template>

    <template>
      <div class="form-group text-left mb-0">
        <div class="row p-2">
          <div class="col-md-1">
            <input type="radio" id="collection" class="form-control w-75 m-auto" name="option" value="collection"
                    @click="update('collection')">
          </div>
            <h4 class="my-auto"><label for="collection">{{translate("add-collection-of-items","Add Collection of Items")}}</label></h4>
        </div>
        <div class="row p-2" v-if="!isHeader">
          <div class="col-md-1">
            <input type="radio" id="header" class="form-control w-75 m-auto" name="option" value="header"
                    @click="update('header')">
          </div>
          <h4 class="my-auto"><label for="header">{{translate("add-header","Add Header")}}</label></h4>
        </div>
        <div class="row p-2" v-if="!isNavigation">
          <div class="col-md-1">
            <input type="radio" id="navigation" class="form-control w-75 m-auto" name="option" value="navigation"
                    @click="update('navigation')">
          </div>
          <h4 class="my-auto"><label for="navigation">{{translate("add-navigation","Add Navigation")}}</label></h4>
        </div>
        <div class="row p-2" v-if="!isBanner">
          <div class="col-md-1">
            <input type="radio" id="banner" class="form-control w-75 m-auto" name="option" value="banner"
                    @click="update('banner')">
          </div>
          <h4 class="my-auto"><label for="banner">{{translate("add-banners","Add Banners")}}</label></h4>
        </div>
      </div>
    </template>

    <template v-slot:button>
      <a href="javascript: void(0)" class="btn btn-link-gray mr-3"
          @click="cancel()">{{translate("cancel","Cancel")}}</a>
      <a href="javascript: void(0)" class="btn btn-primary" @click="next">{{translate("next","Next")}}</a>
    </template>

  </sweet-modal>
</template>


<script lang="ts">
  import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
  import {SweetModal} from 'sweet-modal-vue';

  @Component({
    components: {
      SweetModal,
    },
  })
  export default class AddSection extends Vue {
    option: any = 'collection';
    @Prop({required: true})
    isBanner!: boolean;
    @Prop()
    isNavigation!: boolean;
    @Prop()
    isHeader!: boolean;

    cancel() {
      // @ts-ignore
      this.$children[0].close();
      this.$emit('close', this.option);
    }

    update(option: string) {
      this.option = option;
    }

    next() {
      this.$emit('next', this.option);
      // @ts-ignore
      this.$children[0].close();
    }
  }
</script>

<style scoped>
label{
  margin-bottom: 0 !important;
}
</style>
