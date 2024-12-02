<template v-on-clickaway="cancel">
  <sweet-modal ref="section" hide-close-button blocking>

    <template v-slot:title>
      <h3>{{translate("add-guestflow","ADD GuestFlow")}}</h3>
      <button class="close-modal" @click="cancel()"></button>
    </template>

    <template>
      <div class="form-group text-left">
        <div class="row p-3" style="height: 300px; overflow: auto">
          <template v-for="(guest, index) in guestFlow" >
            <div class="col-md-1" v-if="isValidServiceType(guest)" :key="index">
              <input class="form-control w-75 m-auto" value="collection"  type="checkbox"
                      :id="'ch-'+index" name="item-selected" :value="index"
                      v-model="list">
            </div>
            <div class="col-md-11 pt-2" :key="index + 'name' ">
              <h5>
                {{ translate(guest.name, guest.name) }}
              </h5>
            </div>
          </template>
        </div>
      </div>

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
    export default class GuestFlow extends Vue {
        option: any = 'collection';
        @Prop()
        panel: any;
        guestFlow: [] = [];
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
          // @ts-ignore
          stores.GuestFlow.listofServices()
          .then((response) => {
            console.log('res', response);
            this.guestFlow = response.data;
          });
          console.log('guesflow', this.guestFlow);
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
            // this.guestFlow = [];
            this.list = [];
        }

        next() {
            const temp: any[] = [];
            for (const index in this.list) {
                if (index) {
                    // @ts-ignore
                    temp.push(this.guestFlow[this.list[index]]);
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
