<template>
  <header class="">

    <div class="d-flex align-items-center mb-3">
      <!-- BREADCRUM -->
      <div>
      <button class="btn p-0" style="color: #637381" @click="$emit('breadcrumClick')">
        <icon type="back" :hasTooltip="false"></icon>
       <slot name="breadcrum-title"></slot>
      </button>
      </div>
      <!-- SAVE & CANCEL -->
      <div class="ml-auto d-flex" style="padding-top: 5px">
            <button class="btn mr-3" @click="$emit('cancel')">
              {{ translate("cancel", "Cancel") }}
            </button>
            <button class="btn btn-primary" @click="$emit('save')">
              {{ translate("save", "Save") }}
            </button>
      </div>
    </div>
    <!-- ITEM NAME -->
    <div class="d-flex align-items-center">
      <div>
        <p class="h3"><slot name="title">{{ getTranslatedDisplayName(item) }}</slot></p>
      </div>
    </div>
    <slot name="p m-0"></slot>
    <div class="d-flex align-items-center">
      <div>
        <ul class="nav">
          <!-- REMOVE -->
          <li v-if="hasRemove" class="nav-item">
            <nevron-click-confirm>
              <a class="nav-link pl-0" href="javascript: void(0)" @click="$emit('remove')">
                <icon type="remove"></icon> {{ translate("remove", "Remove") }}
              </a>

            <template #confirm-message>
              {{ translate('permanently-delete', 'Permanetly delete') }}&nbsp;<strong>{{ getTranslatedDisplayName(item) }}</strong>?
            </template>
            </nevron-click-confirm>
          </li>
          
          <!-- REFRESH -->
          <li v-if="hasRefresh" class="nav-item">
              <a class="nav-link pl-0" href="javascript: void(0)" @click="$emit('refresh')">
                <icon type="refresh"></icon> {{ translate("refresh", "Refresh") }}
              </a>
          </li>
          <!-- ADDITIONAL BUTTONS -->
          <slot name="nav"></slot>
        </ul>
      </div>
      <div class="ml-auto mb-2" v-if="item.neighbours">
          <button v-if="item.neighbours.prev !== null" class="btn btn-link-gray" @click="$emit('prev')">
            <icon type="left"></icon>
          </button>
          <div v-else class="btn btn-link-gray disabled">
            <icon type="left"></icon>
          </div>
          <button v-if="item.neighbours.next !== null" class="btn btn-link-gray" @click="$emit('next')">
            <icon type="right"></icon>
          </button>
          <div v-else class="btn btn-link-gray disabled">
            <icon type="right"></icon>
          </div>
      </div>
    </div>
  </header>
</template>
<script lang='ts'>
import { Component, Vue, Prop, Watch } from 'vue-property-decorator';
import { mixin as Clickaway } from 'vue-clickaway';
import NevronClickConfirm from '@/components/NevronClickConfirm.vue';
import {translatedDisplayName} from '@/helpers/functions';

@Component({
  components: {
    NevronClickConfirm,
  },
  mixins: [Clickaway],
})
export default class NevronTableModalGeneric extends Vue {
  @Prop({required: true})
  item!: IItem[] | IItem;

  @Prop({default: true})
  hasRemove!: boolean;

  @Prop({default: true})
  hasRefresh!: boolean;

  getTranslatedDisplayName(item: IItem) {
    return translatedDisplayName(item);
  }

}
</script>