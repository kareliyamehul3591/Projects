<template v-on-clickaway="cancel">
  <sweet-modal ref="create" hide-close-button blocking>
    <template v-slot:title>
      <h3>{{translate("add-item","Add Item")}}</h3>
      <button class="close-modal" @click="cancel()"></button>
    </template>
    <template>
      <form form autocomplete="off" class="needs-validation" novalidate id="create-form" ref="create-form">
      <label class="col-form-label" for="name">{{translate("name","Name")}}</label>
      <input type="text" class="form-control" name="name" id="name" v-model="newItem.name" v-bind="nameRules" required/>
      <div class="invalid-feedback">
        {{translate("name-is-required.","Name is required.")}}
      </div>
      </form>
    </template>

      <template v-slot:button>
        <button class="change save btn" @click="cancel()">
          {{translate("cancel","Cancel")}}
        </button>
        <button class="change save btn btn-primary" type="submit" form="create-form">
          {{translate("next","Next")}}
        </button>
      </template>

  </sweet-modal>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import stores from '@/stores';
import { SweetModal } from 'sweet-modal-vue';

@Component({
 components: {
   SweetModal,
 },
})

export default class CreateCategory extends Vue {
  @Prop()
  module!: IModule;

  newItem = {
    id: null,
    active: true,
    name: '',
    imageId: null,
    moduleId: null,
    sort: null,
  };

  nameRules = {};

  submitNewItem() {
    if (this.newItem) {
      return this.module.createNewItem!((this.newItem))
        .then((response) => {
          this.$router.push({name: `${this.module.path}.items.show`, params: {id: response.data.id}});
          this.cancel();
        })
        .catch((e) => {
          console.log(e);
        });
    }
  }
  cancel() {
    (document.getElementById('create-form') as HTMLFormElement).classList.remove('was-validated');
    // @ts-ignore
    this.$children[0].close();
  }

  mounted() {

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const form = document.getElementById('create-form') as HTMLFormElement;
    form.addEventListener('submit', (event: any) => {
      event.preventDefault();
      if (form.checkValidity() === true) {
        event.stopPropagation();
        this.submitNewItem();
      }
      if (form.checkValidity() === false) {
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    }, false);
  }
}
</script>
