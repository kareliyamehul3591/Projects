<template>
  <div class="mb-3">
    <p class="h3">
      <slot name="title"></slot>
    </p>
    <div class="alert alert-primary" role="alert">
      <span class="fas fa-sad-tear h1 mr-2 align-middle"></span>
      {{ translate("no-data-available.", "No data available.") }}
    </div>
    <div class="d-flex align-items-center">
      <div>
        <ul class="nav">
          <slot name="nav"></slot>
        </ul>
      </div>
      <div class="ml-auto mb-4">
        <slot name="buttons"></slot>
      </div>
    </div>
  </div>
</template>
<script lang="ts">
export default {};
</script>
