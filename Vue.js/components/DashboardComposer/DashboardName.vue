<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button blocking>

    <template v-slot:title>
      <h3>{{translate("enter-dashboard-name","Enter Dashboard Name")}}:</h3>
      <button class="close-modal" @click="cancel()"></button>
    </template>

    <template>
      <div class="form-group text-left">
        <nevron-input name="name" id="name"  type="text" v-model="dashboardName"  parentType="model"
                      :reference-key="'mobile-composer' + dashboardId + '.name'" @handler="notifyParent"/>
      </div>

    </template>

    <template v-slot:button>
      <a href="javascript: void(0)" class="btn btn-link-gray mr-3"
          @click="cancel()">{{translate("cancel","Cancel")}}</a>
      <a href="javascript: void(0)" class="btn btn-primary" @click="next">{{translate("save","Save")}}</a>
    </template>
  </sweet-modal>
</template>


<script lang="ts">
    import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
    import {SweetModal} from 'sweet-modal-vue';
    import axiosInstance from '../../helpers/axios';
    import session from '@/stores/Session';
    import NevronInput from '@/components/NevronInput.vue';

    @Component({
        components: {
            SweetModal,
            NevronInput,
        },
    })
    export default class CollectionItem extends Vue {
        @Prop()
        dashboardName: any;
        @Prop()
        dashboardId: any;

        mounted() {
          console.log('dashboardId', this.dashboardId);
        }
        notifyParent(data: any) {
          this.$emit('openTranlator', data);
        }

        cancel() {
            // @ts-ignore
            this.$children[0].close();
            this.$emit('close', this.dashboardName);
        }
        next() {
            this.$emit('next', this.dashboardName);
            // @ts-ignore
            this.$children[0].close();
        }
    }
</script>

<style scoped>

</style>
