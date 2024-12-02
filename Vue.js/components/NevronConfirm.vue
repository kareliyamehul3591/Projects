<template>
  <div v-on-clickaway="cancel" class="popover fade show bs-popover-bottom" style="position: absolute; left: 350px; top: 175px;">
    <div class="arrow"></div>
    <h3 class="popover-header">{{translate("are-you-sure?","Are you sure?")}}</h3>
    <div class="popover-body p-2">
      <button class="btn btn-sm btn-outline-danger mr-3" @click="cancel()"><icon type="unlink" :hasTooltip="false"></icon> {{translate("no","No")}}</button>
      <button class="btn btn-sm btn-outline-success" @click="confirm()"><span class="fas fa-check-circle mr-1" name="confirm"></span>{{translate("yes","Yes")}}</button>
    </div>
  </div>
</template>
<script lang="ts">
import { Component, Vue, Emit } from 'vue-property-decorator';
import { mixin as Clickaway } from 'vue-clickaway';

@Component({
  mixins: [ Clickaway ],
})

export default class NevronConfirmDelete extends Vue {
  confirm() {
    this.$emit('confirm');
  }
  cancel() {
    this.$emit('cancel');
  }
}
</script>

