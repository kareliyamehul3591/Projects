<template>

    <span v-if="hasTooltip"
          :class="icons[type].code" class="nevron-icon"
          data-toggle="tooltip" data-placement="bottom" :title=" (title) ? title : icons[type].tooltip"></span>
  <!-- Without tooltip -->
  <span v-else :class="icons[type].code" class="nevron-icon"></span>

</template>
<script>
export default {
  props: {
    type: {
      type: String,
      required: true,
    },
    hasTooltip: {
      type: Boolean,
      default: true,
    },
    title: {
      type: String,
      required: false,
    },
  },
  data() {
    return {
      icons: {
          remove: {code: 'fas fa-trash-alt', tooltip: 'Remove'},
          edit: {code: 'fas fa-edit', tooltip: 'Edit'},
          unlink: {code: 'fas fa-times text-danger', tooltip: 'Unlink'},
          import: {code: 'fas fa-download', tooltip: 'Import'},
          export: {code: 'fas fa-upload', tooltip: 'Export'},
          refresh: {code: 'fas fa-sync', tooltip: 'Refresh'},
          copy: {code: 'far fa-copy', tooltip: 'Copy'},
          copied: {code: 'fas fa-copy', tooltip: 'Copied'},
          add: {code: 'fas fa-plus', tooltip: 'Add'},
          right: {code: 'fas fa-arrow-right', tooltip: 'Right Arrow'},
          left: {code: 'fas fa-arrow-left', tooltip: 'Left Arrow'},
          back: {code: 'fas fa-chevron-left', tooltip: 'Back Arrow'},

      },
    };
  },
  methods: {
  },
};
</script>
