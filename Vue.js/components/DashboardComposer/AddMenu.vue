<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button blocking>
      <template v-slot:title>
      <h3 v-if="formName == 'navigation-form'">{{translate("add-navigation-menu","Add Navigation Menu")}}</h3>
      <h3 v-else>{{translate("add-header-menu","Add Header Menu")}}</h3>
      <button class="close-modal" @click="cancel()"></button>
    </template>
    <template>
        <form @submit.prevent="next" :id="formName">
            <div class="form-group">
              <label for="module">{{translate("select-module","Select Module")}}:</label>
              <select name="module" id="module" class="form-control" required
                v-model="selectedModule" @change="setDisplayName">
                <template v-for="(service, index) in services">
                  <option :value="service.id" :key="index">{{ translate(service.name,service.name)}}
                    <span v-if="!service.active">{{translate("(disabled)","(disabled)")}}</span>
                  </option>
                </template>
              </select>
            </div>
          <div class="form-group text-left">
            <div class="form-group">
              <label for="name">{{translate("display-name","Display Name")}}:</label>
<!--              <input type="text" name="name" id="name" class="form-control" required v-model="name">-->
              <nevron-input  :referenceKey=" 'module' + selectedModule + '.name'"   style="max-width: 800px; "
                             v-model="name" name="name" id="name" required />
            </div>
            <div class="form-group">
              <label for="action">{{translate("link-action","Link Action")}}:</label>
              <input name="action" id="action" class="form-control" v-model="action"/>
            </div>
          </div>
        </form>
    </template>

    <template v-slot:button>
      <a href="javascript: void(0)" class="btn btn-link-gray mr-3"
      @click="cancel()">{{translate("cancel","Cancel")}}</a>
     <button class="btn btn-primary" type="submit" :form="formName">{{translate("next","Next")}}</button>
    </template>

  </sweet-modal>
</template>
<script lang="ts">
    import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
    import {SweetModal} from 'sweet-modal-vue';
    import axiosInstance from '../../helpers/axios';
    import session from '@/stores/Session';
    import stores from '@/stores';
    import {PanelElement} from '@/stores/Dashboard';
    import NevronInput from '@/components/NevronInput.vue';

    @Component({
        components: {
            SweetModal,
            NevronInput,
        },
    })
    export default class AddMenu extends Vue {
        @Prop()
        formName!: string;

        services: [] = [];
        selectedModule: any = null;
        action: string = '';
        name: string = '';

        mounted() {
            stores.modules.modules()
                .then((response) => {
                    this.services = response;
                });
        }

        setDisplayName() {
          // @ts-ignore
          this.name = Vue.prototype.translate(this.services.find((mod: any) => mod.id === this.selectedModule).name, this.services.find((mod: any) => mod.id === this.selectedModule).name);
        }

        cancel() {
            // @ts-ignore
            this.$children[0].close();
            this.$emit('close');
        }

        next() {
            const ele = new PanelElement({
                name: this.name,
                linkAction: this.action,
                active: true,
                linkModuleId: this.selectedModule,
                linkLayout: 'element',
            });
            this.$emit('next', ele);
            // @ts-ignore
            this.$children[0].close();
        }
    }
</script>
