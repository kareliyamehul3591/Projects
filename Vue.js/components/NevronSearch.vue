<template>
  <div class="card-body" :class="{'px-0': noXPadding}">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text"><span class="fas fa-search"></span></span>
        </div>
        <slot></slot>
      </div>
  </div> 
</template>
<script lang="ts">
export default {
  props : {
    noXPadding: {
      type: Boolean,
      default: false,
    },
  },
};
</script>