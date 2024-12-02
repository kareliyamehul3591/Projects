<template>
  <div>
    <div class="btn-group" v-if="search">
      <div :disabled="hasFirst" :class="{'btn': true, 'btn-outline-primary': true, 'disabled': hasFirst}" @click="prev()"><icon type="left" :hasTooltip="false"></icon></div>
      <div><input ref="pageInput" class="page-input text-secondary" type="text" :placeholder="value" v-on:keyup.enter="newValueInput()" /></div>
      <div :disabled="hasLast" :class="{'btn': true, 'btn-outline-primary': true, 'disabled': hasLast}" @click="next()"><icon type="right" :hasTooltip="false"></icon></div>
    </div>
    <div class="btn-group" v-else>
      <div :disabled="hasFirst" :class="{'btn': true, 'btn-outline-primary': true, 'disabled': hasFirst}" @click="prev()"><icon type="left" :hasTooltip="false"></icon></div>
      <div><input ref="pageInput" class="page-input text-secondary" type="text" :placeholder="value" v-on:keyup.enter="newValueInput()" /></div>
      <div :disabled="hasLast" :class="{'btn': true, 'btn-outline-primary': true, 'disabled': hasLast}" @click="next()"><icon type="right" :hasTooltip="false"></icon></div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    value: {  // current page
      type: Number,
      required: true,
    },
    pageCount: { // page numbers
      type: Number,
    },
    search: { // search query
      type: String,
      required: false,
    },
    emitOnly: { // router dont push page
      type: Boolean,
      default: false,
    },
  },
  /**
   * Called when changes need to re-render
   */
  mounted() {
    if (this.value > this.pageCount) {
      this.$emit('input', this.pageCount);
    }
  },

  computed: {
    hasFirst() {
      return (this.value === 1);
    },

    hasLast() {
      return (this.value === this.pageCount);
    },
  },

  methods: {
    prevPage() {
      if ((this.value - 1) < 1) {
        return 1;
      } else {
          return (this.value - 1);
      }
    },

    nextPage() {
        if ((this.value + 1) > this.pageCount) {
            return this.pageCount;
        } else {
            return (this.value + 1);
        }
    },

    prev() {
      if (!this.hasFirst) {
        this.$emit('input', (this.value - 1));
        if (!this.emitOnly) {
          this.$router.push({query: {page: this.prevPage()}});
        }
      }
    },

    next() {
      if (!this.hasLast) {
        this.$emit('input', (this.value + 1));
        if (!this.emitOnly) {
          this.$router.push({query: {page: this.nextPage()}});
        }
      }
    },

    newValueInput() {
      const pageInput = this.$refs.pageInput;
      let newValue = Number(pageInput.value);
      if (!Number.isInteger(newValue)) {
        pageInput.value = null;
        return;
      }
      if (newValue > this.pageCount) {
        newValue = this.pageCount;
      } else if (newValue < 1) {
        newValue = 1;
        }
      pageInput.value = null;
      this.$emit('input', newValue);
    },
  },
};
</script>
<style scoped>
.page-input {
  all: unset;
  display: flex;
  align-self: center;
  width: 37px;
  height: 100%;
  font-size: 15px;
}
.page-input::placeholder {
  color: #556EE6;
}
</style>
