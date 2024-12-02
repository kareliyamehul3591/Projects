<template>
  <div class="text-center">
    <p class="display-1"><span class="fas fa-search fa-sm"></span></p>
    <p class="h3">{{translate("could-not-find-any", "Could not find any")}} {{type}}</p>
    <p class="h6">{{translate("add-new","Add new")}} {{type}}</p>
  </div> 
</template>
<script>
export default {
  props: {
    type: {
        type: String,
        required: true,
    },
  },
};
</script>
