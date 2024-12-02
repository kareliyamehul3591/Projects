<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button blocking>

    <template v-slot:title>
      <h3>{{translate("add-collection-of-items","ADD COLLECTION OF ITEMS")}}</h3>
      <button class="close-modal" @click="cancel()"></button>
    </template>
    <template>
      <div class="form-group text-left">
        <div class="row p-2">
          <div class="col-md-1">
            <input type="radio" id="services" class="form-control w-75 m-auto" name="option" value="services"
                   @click="update('services')">
          </div>
          <h5 class="my-auto"><label for="services">{{translate("add-list-of-eservices","Add list of eServices")}}</label></h5>
        </div>
        <div class="row p-2">
          <div class="col-md-1">
            <input type="radio" id="items" class="form-control w-75 m-auto" name="option" value="items"
                    @click="update('items')">
          </div>
          <h5 class="my-auto"><label for="items">{{translate("add-list-of-items","Add list of items")}}</label></h5>
        </div>
        <div class="row p-2">
          <div class="col-md-1">
            <input type="radio" id="guestflow" class="form-control w-75 m-auto" name="option" value="guestflow"
                    @click="update('guestflow')">
          </div>
            <h5 class="my-auto"><label for="guestflow">{{translate("add-list-of-guestflow","Add list of GuestFlow")}}</label></h5>
        </div>
          <!-- <div class="col-md-1">
            <input type="radio" class="form-control" name="option" value="mix"
                    disabled>
          </div>
          <div class="col-md-11 pt-2">
            <h5>{{translate("add-mix-of-all-above-(link,-eservice,-category,-item)","Add mix of all above (link, eService, Category, Item)")}}</h5>
          </div> -->
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
    import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
    import {SweetModal} from 'sweet-modal-vue';
    import axiosInstance from '../../helpers/axios';
    import session from '@/stores/Session';

    @Component({
        components: {
            SweetModal,
        },
    })
    export default class CollectionItem extends Vue {
        @Prop()
        checkGFModule: any;
        option: any = 'collection';

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

</style>
