<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button blocking>
    <template>
      <div class="modal-header">
        <h2 class="modal-title text-left" style="width: 100%">
          {{translate("add-list-of-categories","ADD LIST OF CATEGORIES")}}
          <i class="fa fa-times float-right" @click="cancel()"></i>
          <hr>
        </h2>
      </div>
      <div class="modal-body">
        <div class="form-group text-left" style="margin-top: -20px">
        </div>
        <div class="form-group text-left">
          <label for="eService">{{translate("select-eservice","Select eService")}}:</label>
          <select class="form-control" name="eService" id="eService">
            <option value="">{{translate("movies","Movies")}}</option>
          </select>
        </div>
        <div class="form-group text-left">
          <label for="sorting">{{translate("sorting-option","Sorting Option")}}:</label>
          <select class="form-control" name="sorting" id="sorting">
            <option value="">{{translate("a---z","A - Z")}}</option>
            <option value="">{{translate("z---a","Z - A")}}</option>
          </select>
        </div>
        <div class="form-group text-left">
          <label for="sorting">{{translate("select-element-ratio","Select Element Ratio")}}:</label>
          <div class="ratio-container">
            <div class="div-one" :class="{active: isSelected('one')}" @click="updateRatio('one')">
              4:5
            </div>
            <div class="div-two" :class="{active: isSelected('two')}" @click="updateRatio('two')">
              5:4
            </div>
            <div class="div-three" :class="{active: isSelected('three')}"
                 @click="updateRatio('three')">
              2:3
            </div>
          </div>
        </div>
        <div class="form-group text-left">
          <div class="text-right">
            <a href="javascript: void(0)" class="btn btn-link-gray mr-3"
               @click="cancel()">{{translate("cancel","Cancel")}}</a>
            <a href="javascript: void(0)" class="btn btn-primary" @click="next">{{translate("next","Next")}}</a>
          </div>
        </div>
      </div>
    </template>
  </sweet-modal>
</template>


<script lang="ts">
    import {Component, Vue, Prop, Watch} from 'vue-property-decorator';
    import {SweetModal} from 'sweet-modal-vue';
    import axiosInstance from '../../helpers/axios';
    import session from '@/stores/Session';
    import stores from '@/stores';

    @Component({
        components: {
            SweetModal,
        },
    })
    export default class Categories extends Vue {
        option: any = 'collection';
        ratio: string = 'one';
        services: [] = [];

        mounted() {
            stores.modules.modules().then((response) => {
                    this.services = response;
                });
        }

        cancel() {
            // @ts-ignore
            this.$children[0].close();
            this.$emit('close', this.option);
        }

        update(option: string) {
            this.option = option;
        }

        updateRatio(option: string) {
            this.ratio = option;
        }

        isSelected(ration: string) {
            return this.ratio === ration;
        }

        next() {
            this.$emit('next', this.option);
            // @ts-ignore
            this.$children[0].close();
        }
    }
</script>

<style scoped>
  .ratio-container {
    width: 100%;
    padding: 20px;
  }

  .div-one {
    border-radius: 15px;
    height: 150px;
    width: 100px;
    border: 1px dashed black;
    display: inline-flex;
    padding: 60px 0 0 35px;
    margin: 5px;
    cursor: pointer;
  }

  .div-two {
    border-radius: 15px;
    height: 90px;
    width: 150px;
    margin: 5px;
    border: 1px dashed black;
    display: inline-flex;
    padding: 30px 0 0 55px;
    cursor: pointer;
  }

  .div-three {
    border-radius: 15px;
    height: 90px;
    width: 70px;
    margin: 5px;
    border: 1px dashed black;
    display: inline-flex;
    padding: 30px 0 0 25px;
    cursor: pointer;
  }

  .active {
    border: 2px dashed #007ace;
  }

 
</style>
