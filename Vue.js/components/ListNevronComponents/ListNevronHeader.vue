<template>
  <header class="">
    <p class="h3">
      <slot name="title"></slot>
    </p>
    <slot name="p m-0"></slot>
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
  </header>
</template>

<script>
export default {
};
</script>
