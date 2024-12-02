<template>
  <div>
    <!--    <div id="preloader">-->
    <!--      <div id="status">-->
    <!--        <div class="spinner-chase">-->
    <!--          <div class="chase-dot"></div>-->
    <!--          <div class="chase-dot"></div>-->
    <!--          <div class="chase-dot"></div>-->
    <!--          <div class="chase-dot"></div>-->
    <!--          <div class="chase-dot"></div>-->
    <!--          <div class="chase-dot"></div>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
    <div id="layout-wrapper">
      <Header v-if="session.project"/>
      <!-- ============================================================== -->
      <!-- Start Page Content here -->
      <!-- ============================================================== -->
      <SideBar :is-condensed="isMenuCondensed" :type="leftSidebarType" :width="layoutWidth"/>

      <div class="main-content">
        <div class="page-content">
          <!-- Start Content-->
          <div class="container-fluid">
            <router-view/>
          </div>
        </div>
        <Footer/>
      </div>
    </div>
  </div>
</template>

<script lang='ts'>
import {Component, Vue, Watch, Emit} from 'vue-property-decorator';
import stores from '@/stores';
import Edit from '@/modules/Modules/Edit.vue';
import Module from '@/stores/modules/Module';
import {mixin as Clickaway} from 'vue-clickaway';
import AdminChangePassword from '@/modules/Admins/ChangePassword.vue';
import Skeleton from '@/modules/Skeleton.vue';
import Header from '@/components/partials/header.vue';
import SideBar from '@/components/partials/side-bar.vue';

@Component({
  components: {
    Edit,
    AdminChangePassword,
    Skeleton,
    Header,
    SideBar,
  },
  mixins: [Clickaway],
})

export default class HomeView extends Vue {
  dropdown = false;
  dropdownProfile = false;
  dropdownHamburger = false;

  isMenuCondensed: boolean = false;
  leftSidebarType: string = '';
  layoutWidth: string = '';

  dropdownHamburgerExtra = false;
  // TODO Maks: ali res ne moremo imeti ene komponente, ki skrbi za dropdown?
  openModul: number = -1;
  modules = stores.modules.items;
  admin = stores.admin;
  session = stores.session;

  editModuleVisible: boolean = false;
  selected: IModule | null = null;

  timer: any = null;

  editPass(user: IAdmin) {
    return stores.admins.changePassword(this.admin.id, user)
      .then(() => {
        user.password = '';
        user.passwordConfirmation = '';
        // @ts-ignore
        this.$refs.pass.$children[0].close();
      })
      .catch((e) => {
        console.log(e);
      });
  }

  editAdmin(user: IAdmin) {

    return stores.admins.edit(user)
      .then(() => {
        console.log('success');

      }).catch((e: any) => {
        console.log(e);
      });
  }

  istypesSelected(input: string): boolean {
    return this.$route.path.includes('/devices/types');
  }

  subIsActive(input: string): boolean {
    const paths: any = Array.isArray(input) ? input : [input];
    return paths.some((path: any) => {
      return this.$route.path.indexOf(path) === 0; // current path starts with this path string
    });
  }

  editSaved(edited: Module) {
    edited.save();
  }

  editModuleClicked(selected: IModule) {
    this.selected = selected;
    this.editModuleVisible = true;
  }

  createNewModule() {
    this.$router.push('/module/create');
  }

  setClickedModul(id: number) {
    this.openModul = id;
  }

  dropdownToggle() {
    if (this.dropdown) {
      this.dropdown = false;
    } else {
      this.dropdown = true;
      this.dropdownHamburgerExtra = true;
    }
  }

  dropdownToggleProfile() {
    this.dropdownProfile = !this.dropdownProfile;
  }

  dropdownToggleHamburger() {
    this.dropdownHamburger = !this.dropdownHamburger;
  }

  dropdownHide() {
    this.dropdown = false;

  }

  dropdownHideProfile() {
    this.dropdownProfile = false;
  }

  dropdownHideHamburger() {
    if (this.dropdownHamburgerExtra) {
      if (!this.dropdown) {
        this.dropdownHamburgerExtra = false;
      }
    } else {
      this.dropdownHamburger = false;
    }
  }

  onImageLoadFailure(event: any) {
    event.target.src = require('../../assets/nevron_logo.png');
  }

  hideRightSidebar() {
    document.body.classList.remove('right-bar-enabled');
  }
}
</script>
