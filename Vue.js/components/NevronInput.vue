<template>
  <div class="input-group mb-3">
    <!-- <input type="text" class="form-control" :class="classes" :placeholder="placeholder" @input="handleInput" v-model="localText"> -->
    <input type="text" class="form-control" :class="classes" :placeholder="placeholder"
           v-model="localText" :required="checkTextEmpty"
           @keyup="$emit('input', localText)">
    <div class="input-group-append">
      <a href="javascript: void(0)" @click="handleTranslate" class="input-group-text">
        <i class="fa fa-globe"></i>
      </a>
    </div>
    <dynamic-content ref="translator" :referenceText="localText" :referenceKey="localKey || referenceKey"/>
  </div>
</template>
<script lang="ts">
import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
import DynamicContent from '@/modules/Translation/DynamicContent.vue';
// @ts-ignore
import i18n from '../i18n';
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
  @Prop()
  parentType: any | '';
  @Prop()
  checkTextEmpty: any | false;

  $languages: any  = '';
  localKey: any = '';
  localText: any = '';

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
