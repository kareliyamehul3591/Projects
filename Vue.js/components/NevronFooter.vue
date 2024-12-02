<template>
  <footer class="">
    <div class="d-flex align-items-center">

      <div >
        <p class="h3"><slot name="title"></slot></p>
      </div>
      <div class="ml-auto d-flex  ">
        <slot name="cancel" ></slot>
        <slot name="save"></slot>

      </div>
    </div>

  </footer>
</template>
<script lang="ts">
export default {
};
</script>
