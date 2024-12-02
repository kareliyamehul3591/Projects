<template>
  <header id="page-topbar">
    <div class="navbar-header">
      <div class="d-flex">
        <!-- LOGO -->
        <div class="navbar-brand-box">
          <router-link tag="a" :to="{ name:'home' }" class="logo logo-dark">
            <span class="logo-sm">
              <img :src="logoPath" alt height="12"/>
            </span>
            <span class="logo-lg">
              <img :src="logoPath" alt height="50"/>
            </span>
          </router-link>

          <router-link tag="a" :to="{ name:'home' }" class="logo logo-light">
            <span class="logo-sm">
              <img :src="logoPath" alt height="12"/>
            </span>
            <span class="logo-lg">
              <img :src="logoPath" alt height="50"/>
            </span>
          </router-link>
        </div>

        <button id="vertical-menu-btn" type="button" class="btn btn-sm px-3 font-size-16 header-item"
                @click="toggleMenu">
          <i class="fa fa-fw fa-bars"></i>
        </button>

        <!-- App Search-->
        <form class="app-search d-none d-lg-block">
          <div class="position-relative">
            <input type="text" class="form-control" :placeholder="$t('search')"/>
            <span class="bx bx-search-alt"></span>
          </div>
        </form>
      </div>

      <div class="d-flex">
        <b-dropdown variant="black" toggle-class="header-item" v-if="inDevelopment">
          <template v-if="session.project && session.project.name" v-slot:button-content>
            <img class="rounded-circle header-profile-user"
                 :src="(session.project.logo) ? session.project.logo : defaultLogo()"
                 @error="defaultLogo()"
            />
            <span class="d-xl-inline-block ml-1">{{translate(session.project.name, session.project.name)}}</span>
          </template>
          <!-- item-->
          <b-dropdown-item v-for="(project, index) in master.projects" :key="index"  @click="changeProject(project.id)">
            <span :class="{active: project.id === session.project.id}">
              <i class="bx bx-book"></i>
              {{ translate(project.name, project.name) }}
            </span>
          </b-dropdown-item>
          <b-dropdown-item>
            <router-link :to="{ name: 'projects.create' }">
              <i class="bx bx-plus"></i>
              {{translate("create-new-project","Create new project")}}
            </router-link>
          </b-dropdown-item>
        </b-dropdown>

        <!--   toggle button and search bar  -->
        <b-dropdown
          class="d-inline-block d-lg-none ml-2"
          variant="black"
          menu-class="dropdown-menu-lg p-0"
          toggle-class="header-item noti-icon"
          right
        >
          <template v-slot:button-content>
            <i class="mdi mdi-magnify"></i>
          </template>

          <form class="p-3">
            <div class="form-group m-0">
              <div class="input-group">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Search ..."
                  aria-label="Recipient's username"
                />
                <div class="input-group-append">
                  <button class="btn btn-primary" type="submit">
                    <i class="mdi mdi-magnify"></i>
                  </button>
                </div>
              </div>
            </div>
          </form>
        </b-dropdown>

        <!--  language dropdown  -->
        <b-dropdown variant="white" right toggle-class="header-item" v-if="currentLang">
          <template v-slot:button-content>
            <img v-if="currentLang.image" :src="currentLang.image.imageUrl" height="16"/>
            {{ currentLang.name }}
          </template>
          <b-dropdown-item
            class="notify-item"
            v-for="(language, i) in languages"
            :key="`Lang${i}`"
            :value="language"
            @click="setLanguage(language)"
            :class=" {'active' : currentLang.lan === language.localCode}"
          >
            <img v-if="language.image" :src="language.image.imageUrl" class="mr-1" height="12"/>
            <span class="align-middle">{{ translate(language.name,language.name) }}</span>
          </b-dropdown-item>
        </b-dropdown>
        <!--  Full screen toggle -->
        <div class="dropdown d-none d-lg-inline-block ml-1">
          <button type="button" class="btn header-item noti-icon" @click="initFullScreen">
            <i class="bx bx-fullscreen"></i>
          </button>
        </div>
        <!--    User Profile    -->
        <b-dropdown right variant="black" toggle-class="header-item">
          <template v-slot:button-content>
            <img
              class="rounded-circle header-profile-user"
              src="@/assets/images/avatar.png"
              alt="Header Avatar"
            />
            <span class="d-none d-xl-inline-block ml-1">{{ translate(admin.name, admin.name) }}</span>
          </template>
          <b-dropdown-divider></b-dropdown-divider>
          <router-link class="dropdown-item text-danger" :to="{path: '/logout'}">
            <i class="bx bx-power-off font-size-16 align-middle mr-1 text-danger"></i>
            {{ $t('logout') }}
          </router-link>

        </b-dropdown>
      </div>
    </div>
  </header>
</template>
<script>
import i18n from '../../i18n';
import stores from '@/stores';

import simplebar from 'simplebar-vue';
import router from '@/router';
import session from '@/stores/Session';
import axios from 'axios';
import globalConfig from '@/helpers/globalConfig';
/**
 * Nav-bar Component
 */
export default {
  data() {
    return {
      lan: i18n.locale,
      currentLang: null,
      openModul: -1,
      modules: [],
      admin: [],
      master: [],
      session: [],
      logo: {
        id: 0,
        value: '',
        key: '',
      },
      logoPath: '',
      languages: [],
      inDevelopment: globalConfig.env === 'development',
    };
  },
  components: {simplebar},
  mounted() {
    this.fetchLanguages();
    this.$bus.$on('logged', () => {
      this.fetchLanguages();
    });
    this.modules = stores.modules.items;
    this.admin = stores.admin;
    this.master = stores.master;
    this.session = stores.session;
    stores.Setting.getSystemLogo().then((response) => {
      this.logo = response;
      this.logoPath = this.logo.value;
    });
  },
  methods: {
    toggleMenu() {
      document.body.classList.toggle('sidebar-enable');

      if (window.screen.width >= 992) {
        // eslint-disable-next-line no-unused-vars
        router.afterEach((routeTo, routeFrom) => {
          document.body.classList.remove('sidebar-enable');
          document.body.classList.remove('vertical-collpsed');
        });
        document.body.classList.toggle('vertical-collpsed');
      } else {
        // eslint-disable-next-line no-unused-vars
        router.afterEach((routeTo, routeFrom) => {
          document.body.classList.remove('sidebar-enable');
        });
        document.body.classList.remove('vertical-collpsed');
      }
      this.isMenuCondensed = !this.isMenuCondensed;
    },
    toggleRightSidebar() {
      document.body.classList.toggle('right-bar-enabled');
    },
    initFullScreen() {
      document.body.classList.toggle('fullscreen-enable');
      if (
        !document.fullscreenElement &&
        /* alternative standard method */ !document.mozFullScreenElement &&
        !document.webkitFullscreenElement
      ) {
        // current working methods
        if (document.documentElement.requestFullscreen) {
          document.documentElement.requestFullscreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullscreen) {
          document.documentElement.webkitRequestFullscreen(
            Element.ALLOW_KEYBOARD_INPUT,
          );
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        }
      }
    },
    fetchLanguages() {
      // fetch active languages from database
      stores.Language.activeLanguages()
      .then((response) => {
        this.languages = response;
      }).then((res) => {
        // after language fetch let's load admin
        stores.admin.me()
        .then((response) => {
          this.currentLang = (response.language) ? response.language : this.languages.find((x) => x.language === i18n.locale);
          if (!this.currentLang) {
            this.currentLang = this.languages[0];
          }
        });
      });
    },

    setLanguage(language) {
      this.currentLang = language;
      stores.admin.setLanguage(language.id)
      .then((response) => {
        console.log(response);
        i18n.locale = language.localCode;
        window.location.reload();
      });
    },

    changeProject(projectId) {
      if (projectId !== stores.session.project.id) {
        window.location.replace(`${window.location.origin}/${projectId}`);
      }
    },

    defaultLogo(event) {
      if (!event) {
        return require('../../../assets/nevron_logo.png');
      }
      event.target.src = require('../../../assets/nevron_logo.png');
    },

  },

};
</script>
