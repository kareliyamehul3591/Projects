<template>
  <div>
    <!-- click blocking layer -->
    <span :id="randomId" ref="trigger" tabindex="-1" @click.capture="interceptEvent">
      <slot></slot>
    </span>

    <!-- modal -->
    <sweet-modal ref="modal" hide-close-button blocking>
      <template v-slot:title>
        <h3>{{ translate("Are-you-sure", "Are you sure") }}?</h3>
        <button class="close-modal" @click="cancel()"></button>
      </template>
      <template>
        <div>
          <p style="font-size: 20px">
            <slot name="confirm-message"></slot>
          </p>
        </div>
      </template>

      <template v-slot:button>
        <button class="change save btn btn-lg" @click="cancel()">
          {{ translate("no", "No") }}
        </button>
        <button class="change save btn btn-primary btn-lg" @click.prevent="onOk">
          {{ translate("yes", "Yes") }}
        </button>
      </template>
    </sweet-modal>

  </div>
</template>

<script>
import { SweetModal } from 'sweet-modal-vue';
export default {
  components: {
    SweetModal,
  },
  data() {
    return {
      isOpen: false,
      randomId: 'clickConfirm' + this._uid,
      target: null,
      allow: false,
    };
  },
  props: {
    disabled: {
      type: Boolean,
      default: false,
    },
  },
  watch: {
    disabled(newValue) {
      if (newValue && this.isOpen) {
        this.cancel();
      }
    },
  },
  methods: {
    onHidden() {
      this.target = null;
    },
    onOk() {
      if (this.target !== null) {
        this.allow = true;
        const mouseClick = new MouseEvent('click', {
          bubbles: true,
          cancelable: true,
          composed: true,
        });
        if (!this.target.dispatchEvent(mouseClick)) {
          console.error('Confirmed event failed to dispatch');
        }
        this.allow = false;
      }
      this.cancel();
    },
    cancel() {
      this.$refs.modal.close();
      this.isOpen = false;
    },
    interceptEvent(e) {
      if (this.disabled) {
        return;
      }
      this.target = e.target;
      if (this.allow) {
          return;
      }
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
      this.$refs.modal.open();
      this.isOpen = true;
    },
  },
  beforeDestroy() {
    if (this.isOpen) {
      this.cancel();
    }
  },
};
</script>
