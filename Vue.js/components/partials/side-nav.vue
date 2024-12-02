<template>
  <!-- ========== Left Sidebar Start ========== -->
  <!--- Sidemenu -->
  <div id="sidebar-menu">
    <!-- Left Menu Start -->
    <ul id="side-menu" class="metismenu list-unstyled">
      <li class="menu-title">{{ translate('menu', 'Menu') }}</li>
      <li>
        <router-link to="/dashboard" class="side-nav-link-ref">
          <i class="bx bx-home"></i>
          <span>{{ translate('home', 'Home') }}</span>
        </router-link>
      </li>
      <li class="">
        <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
          <i class="fa fa-users pr-2"></i>
          <span>{{ translate('customers', 'Customers') }}</span>
        </a>
        <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
          <li>
            <router-link :to="{name: 'customers'}">
              {{ translate('customers', 'Customers') }}
            </router-link>
          </li>
          <li>
            <router-link to="/interests" class="side-nav-link-ref">
              <span>{{ translate('interests', 'Interests') }}</span>
            </router-link>
          </li>
          <li class="">
            <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
              <span>{{ translate('login-data', 'Login Data') }}</span>
            </a>
            <ul aria-expanded="false" class="sub-menu mm-collapse" style="">

              <li>
                <router-link :to="{name: 'users'}">
                  {{translate('users data', 'Users Data') }}
                </router-link>
              </li>

              <li>
                <router-link :to="{name: 'accounts'}">
                  {{ translate('accounts', 'Accounts') }}
                </router-link>
              </li>
            </ul>
          </li>
          <li>
            <router-link to="/guest-flow-module" class="side-nav-link-ref">
              <span>{{ translate('guest-flow-modules', 'Guest Flow Modules') }}</span>
            </router-link>
          </li>
        </ul>
      </li>
      <li class="">
        <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
          <i class="fa fa-bed pr-2" aria-hidden="true"></i>

          <span>{{ translate('stays', 'Stays') }}</span>
        </a>
        <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
          <li>
            <router-link :to="{name: 'stays'}">
              {{ translate('stays', 'Stays') }}
            </router-link>
          </li>
          <li>
            <router-link to="/stay/category">
              {{ translate('stay-types', 'Stay Types') }}
            </router-link>
          </li>
        </ul>
      </li>
      <li>
        <router-link to="/promotions" class="side-nav-link-ref">
          <i class="fas fa-bullhorn"></i> &nbsp;
          <span>{{ translate('promotoions', 'Promotions') }}</span>
        </router-link>
      </li>
      <li class="">
        <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
          <i class="fas fa-archway"></i>
          <span>
            {{translate('property-assets', 'Property Assets') }}
          </span>
        </a>
        <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
          <li>
            <router-link to="/assets" class="side-nav-link-ref">
              &nbsp;
              <span>{{ translate('assets', 'Assets') }}</span>
            </router-link>
          </li>
          <li>
            <router-link to="/assets/category" class="side-nav-link-ref">
              &nbsp;
              <span>{{ translate('assets-categories', 'Assets Categories') }}</span>
            </router-link>
          </li>
          <li class="">
            <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
              <span>
            {{translate('locations', 'Locations') }}
          </span>
            </a>
            <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
              <li>
                <router-link to="/locations" class="side-nav-link-ref">
                  <span>{{ translate('zone', 'Zone') }}</span>
                </router-link>
              </li>
              <li>
                <router-link to="/category" class="side-nav-link-ref">
                  <span>{{ translate('category', 'Category') }}</span>
                </router-link>
              </li>
            </ul>
          </li>
          <li>
            <router-link to="/library" class="side-nav-link-ref">
              &nbsp;
              <span>{{ translate('accommodation-library', 'Accommodation Library') }}</span>
            </router-link>
          </li>
          <li class="">
            <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
              <span>
            {{translate('media', 'Media') }}
          </span>
            </a>
            <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
              <li>
                <router-link to="/social-media" class="side-nav-link-ref">
                  <span>{{ translate('social-media', 'Social Media Plateforms') }}</span>
                </router-link>
              </li>
              <li>
                <router-link to="/rating/plateform" class="side-nav-link-ref">
                  <span>{{ translate('ratings-platforms', 'Ratings Platforms') }}</span>
                </router-link>
              </li>
              <li>
                <router-link to="/comment-proposals" class="side-nav-link-ref">
                  <span>{{ translate('comment-proposals', 'Comment Proposals') }}</span>
                </router-link>
              </li>
            </ul>
          </li>
        </ul>
      </li>
      <li class="">
        <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
          <i class="fas fa-desktop pr-2"></i>
          <span>
            {{ translate('devices', 'Device') }}
          </span>
        </a>
        <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
          <li>
            <router-link :to="{name: 'devices'}">
              {{ translate('devices', 'Devices') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{name: 'devices.types'}">
              {{ translate('devices-types', 'Device Types') }}
            </router-link>
          </li>
        </ul>
      </li>
      <li>
        <router-link :to="{name: 'packages', params:{folderId: 1}}" class="side-nav-link-ref">
          <i class="fas fa-th-list pr-2"></i>
          <span>
            {{ translate('packages', 'Packages') }}
          </span>
        </router-link>
      </li>
      <li class="">
        <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
          <i class="fa fa-tachometer-alt pr-2"></i>
          <span>
            {{ translate('dashboards', 'Dashboards') }}
          </span>
        </a>
        <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
          <li>
            <router-link :to="{name: 'stbDashboards'}">
              {{ translate('stb-dashboards', 'STB Dashboards') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{name: 'mobileDashboards'}">
              {{ translate('mobile-dashboards', 'Mobile Dashboard') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{name: 'gfMobileDashboard'}">
              {{translate('gf-mobile-dashboard', 'GF-Mobile Dashboard') }}
            </router-link>
          </li>
        </ul>
      </li>
      <!-- comment for crew or cast      -->
      <!--      <li class="">-->
      <!--        <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">-->
      <!--          <i class="fa fa-tachometer-alt pr-2"></i>-->
      <!--          <span>-->
      <!--            {{translate('crew-&-cast', 'Crew & Cast') }}-->
      <!--          </span>-->
      <!--        </a>-->
      <!--        <ul aria-expanded="false" class="sub-menu mm-collapse" style="">-->
      <!--          <li>-->
      <!--            <router-link :to="{name: 'crew'}">-->
      <!--              {{translate('crew', 'Crew') }}-->
      <!--            </router-link>-->
      <!--          </li>-->
      <!--          <li>-->
      <!--            <router-link :to="{name: 'mobile-dashboards'}">-->
      <!--              {{translate('cast', 'Cast') }}-->
      <!--            </router-link>-->
      <!--          </li>-->
      <!--        </ul>-->
      <!--      </li>-->

      <li class="menu-title">
        {{ translate('modules', 'Modules') }}
      </li>
      <li>
        <router-link :to="{ name: 'modules.index'}" class="side-nav-link-ref">
          <i class="fa fa-list fa-2x"></i>
          <span>{{ translate('modules', 'Modules') }}</span>
        </router-link>
      </li>
      <!--      <li>-->
      <!--        <router-link :to="{name: 'modules.create'}" class="side-nav-link-ref">-->
      <!--          <i class="fa fa-plus"></i>-->
      <!--          <span>-->
      <!--            {{translate('menuitems.add-module.text', 'Add Module') }}-->
      <!--          </span>-->
      <!--        </router-link>-->
      <!--      </li>-->
      <li>
        <router-link class="side-nav-link-ref" :to="{name: 'epgSources'}">
          <i class="fas fa-calendar pr-2"></i>
          <span>
            {{ translate('epg-sources', 'EPG Sources') }}
          </span>
        </router-link>
      </li>
      <template v-for="element in menus">
        <li v-if="element.type === 'TV_RADIO'" class="" :key="element.id" aria-expanded="false">
          <a href="javascript:void(0);" class="is-parent has-arrow">
            <i class="fas fa-tv pr-2"></i>
            <span>
            {{ translate(element.name.replace(/\s/g, "-").toLowerCase(), element.name) }}
            </span>
          </a>
          <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'tv.items', params: {moduleId: element.id}}">
                {{ translate(`module${element.id}.item`, 'Channels') }}
              </router-link>
            </li>
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'tv.categories', params: {moduleId: element.id}}">
                {{ translate(`module${element.id}.category`, 'Categories') }}
              </router-link>
            </li>
          </ul>
        </li>
        <li v-if="element.type === 'SERIES'" class="" :key="element.id" aria-expanded="false">
          <a href="javascript:void(0);" class="is-parent has-arrow">
            <i class="fas fa-tv pr-2"></i>
            <span>
            {{ translate(element.name.replace(/\s/g, "-").toLowerCase(), element.name) }}
            </span>
          </a>
          <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'series.items', params: {moduleId: element.id}}">
                {{ translate(`module${element.id}.item`, 'Series') }}
              </router-link>
            </li>
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'series.categories', params: {moduleId: element.id, categoryId: 0}}">
                {{ translate(`module${element.id}.category`, 'Genre') }}
              </router-link>
            </li>
          </ul>
        </li>
        <li v-if="element.type === 'PAGES'" :key="element.id" class="">
          <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
            <i class="fas fa-tv pr-2"></i>
            <span>
              {{ translate(element.name.replace(/\s/g, "-").toLowerCase(), element.name) }}
            </span>
          </a>
          <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'pages.items', params: {moduleId: element.id}}">
                {{ translate(`module${element.id}.item`, 'Page Items') }}
              </router-link>
            </li>
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'pages.categories', params: {moduleId: element.id, id: 0}}">
                {{ translate(`module${element.id}.category`, 'Page Categories') }}
              </router-link>
            </li>
          </ul>
        </li>
        <li v-if="element.type === 'APPLICATION'" :key="element.id" class="">
          <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
            <i class="fas fa-tv pr-2"></i>
            <span>
              {{ translate(element.name.replace(/\s/g, "-").toLowerCase(), element.name) }}
            </span>
          </a>
          <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'applications.items', params: {moduleId: element.id}}">
                {{ translate(`module${element.id}.item`, 'Application Items') }}
              </router-link>
            </li>
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'applications.categories', params: {moduleId: element.id, categoryId: 0}}">
                {{ translate(`module${element.id}.category`, 'Application Categories') }}
              </router-link>
            </li>
          </ul>
        </li>
        <li v-if="element.type === 'VOD'" :key="element.id">
          <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
            <i class="fas fa-tv pr-2"></i>
            <span>
              {{ translate(element.name.replace(/\s/g, "-").toLowerCase(), element.name) }}
            </span>
          </a>
          <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'vod.items', params: {moduleId: element.id}}">
                {{ translate(`module${element.id}.item`, 'Movies') }}
              </router-link>
            </li>
            <li>
              <router-link class="side-nav-link-ref"
                           :to="{name: 'vod.categories', params: {moduleId: element.id, categoryId: 0}}">
                {{ translate(`module${element.id}.category`, 'Categories') }}
              </router-link>
            </li>
          </ul>
        </li>

        <li v-if="element.type === 'TRANSCODER'" :key="element.id">
          <router-link class="side-nav-link-ref"
                       :to="{name: `transcoder.streams`, params: {moduleId: element.id}}">
            <i class="fas fa-calendar pr-2"></i>
            {{
              translate(element.name.replace(/\s/g, "-").toLowerCase(),
                element.name)
            }}
          </router-link>
        </li>
        <li v-if="element.type === 'RECORDER'" :key="element.id">
          <router-link class="side-nav-link-ref"
                       :to="{name: `recorders`, params: {moduleId: element.id}}">
            <i class="fas fa-calendar pr-2"></i>
            {{
              translate(element.name.replace(/\s/g, "-").toLowerCase(),
                element.name)
            }}
          </router-link>
        </li>
        <li v-if="element.type === 'WEATHER'" :key="element.id">
          <router-link class="side-nav-link-ref"
                       :to="{name: `locations`, params: {moduleId: element.id}}">
            <i class="fas fa-cloud pr-2"></i>
            <span>
              {{ translate(element.name.replace(/\s/g, "-").toLowerCase(), element.name) }}
            </span>
          </router-link>
        </li>
        <li v-if="element.type === 'TIME'" :key="element.id">
          <router-link class="side-nav-link-ref"
                       :to="{name: `clock`, params: {moduleId: element.id}}">
            <i class="fas fa-clock pr-2"></i>
            <span>
              {{ translate(element.name.replace(/\s/g, "-").toLowerCase(), element.name) }}
            </span>
          </router-link>
        </li>
      </template>

      <li class="menu-title">
        {{ translate('tools', 'Tools') }}
      </li>
      <li>
        <router-link class="side-nav-link-ref" :to="{name: 'media.folder', params:{folderId: 1}}">
          <i class="fas fa-photo-video pr-2"></i>
          <span>
            {{ translate('media-library', 'Media Library') }}
          </span>
        </router-link>
      </li>
      <li class="menu-title">
        {{ translate('configurations', 'Configurations') }}
      </li>
      <li class="">
        <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
          <i class="fas fa-cogs pr-2"></i>
          <span>
            {{ translate('settings', 'Settings') }}
          </span>
        </a>
        <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
          <li>
            <router-link :to="{name: 'general.setting'}">
              {{ translate('general-configuration', 'General Configuration') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{name: 'file.library'}">
              {{ translate('file-library', 'File Module') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{name: 'app.setting'}">
              {{ translate('app-settings', 'App Settings') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{name: 'cron.settings'}">
              {{ translate('cron-job-settings', 'Cron Job Settings') }}
            </router-link>
          </li>
          <li>
            <router-link :to="{name: 'modules.setting'}">
              {{ translate('module-element-settings', 'Module Element Settings') }}
            </router-link>
          </li>
          <li>
            <a href="javascript:void(0);" class="is-parent has-arrow" aria-expanded="false">
              <i class="fas fa-language pr-2"></i>
              <span>
                {{ translate('localization', 'Localization') }}
              </span>
            </a>
            <ul aria-expanded="false" class="sub-menu mm-collapse" style="">
              <li>
                <router-link :to="{name: 'languages'}">
                  {{ translate('languages', 'Languages') }}
                </router-link>
              </li>
              <li>
                <router-link :to="{name: 'translation.index'}">
                  {{ translate('translations', 'Translations') }}
                </router-link>
              </li>
            </ul>
          </li>
          <li>
            <router-link :to="{name: 'matomo.setting'}">
              {{ translate('matomo-settings', 'Matomo Settings') }}
            </router-link>
          </li>
        </ul>
      </li>
      <li>
        <router-link class="side-nav-link-ref" to="/admins">
          <i class="fas fa-users pr-2"></i>
          <span>{{ translate('admins', 'Admins') }}</span>
        </router-link>
      </li>
    </ul>
  </div>
  <!-- Sidebar -->
</template>


<script>
import MetisMenu from 'metismenujs/dist/metismenujs';
import stores from '@/stores';

export default {
  data() {
    return {
      modules: [],
      menus: [],
      counter: 0,
      admin: stores.admin,
      timer: '',
    };
  },
  mounted() {
    this.menus = stores.modules.models;
    Promise.resolve().then(() => {
      this.menuManagement();
    });
  },
  methods: {
    /**
     * Returns true or false if given menu item has child or not
     * @param item menuItem
     */
    hasItems(item) {
      return item.subItems !== undefined ? item.subItems.length > 0 : false;
    },
    menuManagement() {
      const menuRef = new MetisMenu('#side-menu');
      const links = document.getElementsByClassName('side-nav-link-ref');
      let matchingMenuItem = null;
      const paths = [];

      // for (let i = 0; i < links.length; i++) {
      // @ts-ignore
      // paths.push(links[i]['pathname']);
      // console.log(links[i]['pathname']);
      // }

      const itemIndex = paths.indexOf(window.location.pathname);
      if (itemIndex === -1) {
        const strIndex = window.location.pathname.lastIndexOf('/');
        const item = window.location.pathname.substr(0, strIndex).toString();
        matchingMenuItem = links[paths.indexOf(item)];
      } else {
        matchingMenuItem = links[itemIndex];
      }
      if (matchingMenuItem) {
        matchingMenuItem.classList.add('active');
        const parent = matchingMenuItem.parentElement;

        /**
         * TODO: This is hard coded way of expading/activating parent menu dropdown and working till level 3.
         * We should come up with non hard coded approach
         */
        // if (parent) {
        //   parent.classList.add('mm-active');
        //   const parent2 = parent.parentElement.closest('ul');
        //   if (parent2 && parent2.id !== 'side-menu') {
        //     parent2.classList.add('mm-show');
        //
        //     const parent3 = parent2.parentElement;
        //     if (parent3) {
        //       parent3.classList.add('mm-active');
        //       const childAnchor = parent3.querySelector('.has-arrow');
        //       const childDropdown = parent3.querySelector('.has-dropdown');
        //       if (childAnchor) {
        //         childAnchor.classList.add('mm-active');
        //       }
        //       if (childDropdown) {
        //         childDropdown.classList.add('mm-active');
        //       }
        //
        //       const parent4 = parent3.parentElement;
        //       if (parent4 && parent4.id !== 'side-menu') {
        //         parent4.classList.add('mm-show');
        //         const parent5 = parent4.parentElement;
        //         if (parent5 && parent5.id !== 'side-menu') {
        //           parent5.classList.add('mm-active');
        //           const childanchor = parent5.querySelector('.is-parent');
        //           alert("k");
        //           if (childanchor && parent5.id !== 'side-menu') {
        //             childanchor.classList.add('mm-active');
        //           }
        //         }
        //       }
        //     }
        //   }
        // }
      }
    },
  },
};
</script>

