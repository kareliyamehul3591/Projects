<template>
  <header class="">

    <div class="d-flex align-items-center mb-3">
      <div>
       <slot name="breadcrum"></slot>
      </div>
      <div class="ml-auto d-flex  " style="padding-top: 5px">
        <slot name="cancel" ></slot>
        <slot name="save"></slot>
      </div>
    </div>
    <div class="d-flex align-items-center">
      <div>
        <p class="h3"><slot name="title"></slot></p>
      </div>
    </div>
    <slot name="p m-0"></slot>
    <div class="d-flex align-items-center">
      <div>
        <ul class="nav">
          <slot name="nav"></slot>
        </ul>
      </div>
      <div class="ml-auto mb-2">
        <slot name="buttons"></slot>
      </div>
    </div>
  </header>
</template>
<script lang="ts">
export default {
};
</script>
