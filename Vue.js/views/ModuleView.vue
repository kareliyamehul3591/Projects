<template>
  <div>
    <router-view v-if="module" :module="module" :instance="module"></router-view>
  </div>
</template>

<script lang="ts">
import {Component, Vue, Watch, Prop} from 'vue-property-decorator';
import stores from '@/stores';
import Edit from '@/modules/Modules/Edit.vue';
@Component({
  components: {
    Edit,
  },
})

export default class ModuleView extends Vue {
  @Prop()
  moduleId!: number;

  @Prop({required: true})
  moduleType!: ModuleType;

  module: any = null;
 // TODO maks implementiri tole od pintarja https://github.com/NevronIPTV/nucleus-client-stb/blob/master/src/default/router/ViewModule.script.ts
  @Watch('moduleId', { immediate: true })
  setModuleFromProps() {
    this.module = null;
    stores.modules.getData()
      .then(() => {
        this.module = stores.modules.get(this.moduleId);
        if (!this.module || this.module.type !== this.moduleType) {
            console.log('e - module is', this.module);
            console.log('module type is', this.module ? this.module.type : 'mno module');
            console.log('Sould be', this.moduleType);

            this.module =  null;
            this.$router.push({name: 'home'});
        } else {
            console.log('module is', this.module);
            console.log('module type is', this.module.type);
        }
      });
  }
}
</script>
