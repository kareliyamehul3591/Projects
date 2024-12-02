<template>
  <div class="card">
    <div class="card-body">
      <h4 class="card-title mb-5">
        Modules ({{ basicData.moduleCount }})
        <router-link :to="{ name: 'modules.create' }"
                     class="btn btn-sm btn-default float-right">
          <i class="mdi mdi-plus"></i>
          {{translate("add-module","Add Module")}}
        </router-link>
      </h4>
      <div class="table-responsive mb-0">
        <table class="table table-centered table-nowrap">
          <thead class="thead-light">
          <tr>
            <th>{{translate("module-id","Module ID")}}</th>
            <th>{{translate("type","Type")}}</th>
            <th>{{translate("name","Name")}}</th>
          </tr>
          </thead>
          <tbody v-if="modules.data">
          <tr v-for="data in modules.data" :key="data.id">
            <td>
              <a href="javascript: void(0);"
                 class="text-body font-weight-bold">{{data.id}}</a>
            </td>
            <td>{{data.type}}</td>
            <td>{{translate(data.name)}}</td>
          </tr>
          </tbody>
        </table>
        <div class="text-center">
          <button type="button" class="btn btn-sm btn-primary float-left"
                  v-if="modules.currentPage !== 1" @click="modulePre()">{{translate("pre","Pre")}}
          </button>
          <button type="button" v-if="modules.currentPage !== modules.lastPage"
                  class="btn btn-sm btn-primary float-right" @click="moduleNext()">{{translate("next","Next")}}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import {Component, Prop, Vue, Watch} from 'vue-property-decorator';
    export default {
        props: ['modules', 'basicData'],
        methods: {
            modulePre() {
                this.$emit('pre', true);
            },
            moduleNext() {
                this.$emit('next', true);
            },
        },
    };
</script>

