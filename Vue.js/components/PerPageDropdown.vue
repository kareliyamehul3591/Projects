<template>
      <div class="float-right">
        <label>{{translate("show","show")}} </label>
        <select name="perPage" id="perPage" v-model="newPerPage" @change="perPageChange()" class="mx-1">
        <option value="20">20</option>
        <option value="50">50</option>
        <option value="100">100</option>
        <option value="10000000">All</option>
        </select>
        <label>{{translate("entries","entries")}}</label>
      </div>
</template>
<script>
export default {
  props: {
    value: {
      default: 20,
    },
  },
  data() {
    return {
      newPerPage: this.value,
    };
  },
  methods: {
      perPageChange() {
        this.$emit('input', this.newPerPage);
      },
  },
};
</script>
