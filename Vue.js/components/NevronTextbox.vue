<template>
  <div class="input-group mb-3">
        <textarea type="text" class="form-control" :class="classes" :placeholder="placeholder" rows="10" 
                  v-model="localText"
                  @keyup="$emit('input', localText)"></textarea>
    <div class="input-group-append">
      <a href="javascript: void(0)" @click="$refs.translator.$children[0].open()"
         class="input-group-text">
        <i class="fa fa-globe"></i>
      </a>
    </div>
    <dynamic-content ref="translator" :referenceText="localText" :referenceKey="localKey || referenceKey"/>
  </div>
</template>
<script lang="ts">
import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
import DynamicContent from '@/modules/Translation/DynamicContent.vue';
import stores from '@/stores';

@Component({
  components: {
    DynamicContent,
  },
})
export default class NevronInput extends Vue {
  @Prop()
  value: any | null;
  @Prop()
  referenceKey: any | '';
  @Prop()
  placeholder: any;
  @Prop()
  classes: any;
  @Prop() // parentType mean is this called from pop-up or page
  parentType: any | '';

  localKey: any = '';
  localText: any = '';
  intervalTime: any = null;

  mounted() {
    this.localKey = this.value;
    this.localText = Vue.prototype.translate(this.localKey, this.localKey);
  }

  handleTranslate() {
    // @ts-ignore
    this.$refs.translator.$children[0].open();
  }

}
</script>
