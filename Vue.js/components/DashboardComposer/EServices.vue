<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button blocking>

    <template v-slot:title>
      <h3>{{translate("add-eservice","ADD eSERVICE")}}</h3>
      <button class="close-modal" @click="cancel()"></button>
    </template>

    <template>
      <div class="form-group text-left">
        <div class="row p-3" style="height: 300px; overflow: auto">
          <template v-for="(service, index) in services" >
            <div class="col-md-1" v-if="isValidServiceType(service)" :key="index">
              <input class="form-control w-75 m-auto" value="collection"  type="checkbox"
                      :id="'ch-'+index" name="item-selected" :value="index"
                      v-model="list">
            </div>
            <div class="col-md-11 pt-2" :key="index + 'name' ">
              <h5>
                <label :for="'ch-'+index">
                {{ translate(service.name,service.name) }}
                <span v-if="!service.active">{{translate("(disabled)","(disabled)")}}</span>
                </label>
              </h5>
            </div>
          </template>
        </div>
      </div>

<!--          <div class="form-group text-left">-->
<!--            <label for="sorting" style="margin-top: 35px">{{translate("select-element-ratio","Select Element Ratio:")}}</label>-->
<!--            <div class="ratio-container">-->
<!--              <div class="div-three" :class="{active: isSelected('card-poster')}"-->
<!--                   @click="updateRatio('card-poster')">-->
<!--                2:3-->
<!--              </div>-->
<!--              <div class="div-two" :class="{active: isSelected('card-cover')}"-->
<!--                   @click="updateRatio('card-cover')">-->
<!--                5:4-->
<!--              </div>-->
<!--              <div class="div-one" :class="{active: isSelected('image')}"-->
<!--                   @click="updateRatio('image')">-->
<!--                4:5-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->

    </template>

    <template v-slot:button>
        <a href="javascript: void(0)" class="btn btn-link-gray mr-3"
            @click="cancel()">{{translate("cancel","Cancel")}}</a>
        <a href="javascript: void(0)" class="btn btn-primary" @click="next">{{translate("next","Next")}}</a>
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
    export default class EServices extends Vue {
        option: any = 'collection';
        @Prop()
        panel: any;
        services: [] = [];
        list: [] = [];
        data: any = {
          category: null,
          module: null,
          sortType: 'sort',
          ratio: 'image',
          quantity: 'all',
          data: [],
        };

        cancel() {
            // @ts-ignore
            this.$children[0].close();
            this.$emit('close', this.option);
            this.clearData();
        }

        mounted() {

              stores.modules.modules()
                  .then((response) => {
                      this.services = response;
                  });
        }
        isSelected(ration: string) {
          return this.data.ratio === ration;
        }
        updateRatio(option: string) {
          this.data.ratio = option;
        }

        update(option: string) {
                this.option = option;
        }

        isValidServiceType(type: string) {
            // return type ===
            return true;
        }

        clearData() {
            this.option = null;
            // this.services = [];
            this.list = [];
        }

        next() {
            const temp: any[] = [];
            for (const index in this.list) {
                if (index) {
                    // @ts-ignore
                    temp.push(this.services[this.list[index]]);
                }

            }
            this.$emit('response', temp);
            // @ts-ignore
            this.$children[0].close();
        }
    }
</script>

<style scoped>
.ratio-container {
  width: 100%;
  /*padding: 20px;*/
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
  border: 2px dashed #007ace !important;
}


input[type=radio] {
  height: 15px;
  width: 15px;
}
</style>
