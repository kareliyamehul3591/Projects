<template>
  <div class="card overflow-hidden">
    <div class="bg-soft-primary">
      <div class="row">
        <div class="col-7">
          <div class="text-primary p-3">
            <h5 class="text-primary">{{translate("welcome-back-!","Welcome Back !")}}</h5>
            <p>{{translate("nevron-manager-dashboard","Nevron Manager Dashboard")}}</p>
          </div>
        </div>
        <div class="col-5 align-self-end">
          <img src="@/assets/images/profile-img.png" alt class="img-fluid"/>
        </div>
      </div>
    </div>
    <div class="card-body pt-0">
      <div class="row">
        <div class="col-sm-4">
          <div class="avatar-md profile-user-wid mb-4">
            <img src="@/assets/images/avatar.png" alt class="img-thumbnail rounded-circle"/>
          </div>
          <h5 class="font-size-15 text-truncate">{{ admin.name?admin.name:name }}</h5>
          <p class="text-muted mb-0 text-truncate">{{ admin.email?admin.email:email }}</p>
        </div>

        <div class="col-sm-8">
          <div class="pt-4">
            <div class="row">
              <div class="col-6 text-center">
                <h5 class="font-size-15">{{totalDevices}}</h5>
                <p class="text-muted mb-0">{{translate("devices","Devices")}}</p>
                <router-link :to="{ name: 'devices'}">{{translate("show-data..","Show Data..")}}</router-link>
              </div>
              <div class="col-6 text-center">
                <h5 class="font-size-15">{{totalAccount}}</h5>
                <p class="text-muted mb-0">{{translate("accounts","Accounts")}}</p>
                <router-link :to="{ name: 'accounts' }">{{translate("show-data..","Show Data..")}}</router-link>
              </div>
            </div>
          </div>
          <div class="mt-4 float-right">
            <router-link :to="{ name: 'setting'}" class="btn btn-primary btn-sm">
              <i class="mdi mdi-cog-outline ml-1"></i>
              {{translate("go-to-setting","GO TO SETTING")}}
            </router-link>
          </div>
        </div>
      </div>
    </div>
    <!-- end card-body -->
  </div>
  <!-- end card -->
</template>
<script>
  /**
   * Profile component
   */
  import admin from '../../../stores/TheLogedInAdmin';
  import axiosInstance from '@/helpers/axios';
  import stores from '../../../stores';

  export default {
    data() {
      return {
        name: 'Super Admin',
        email: 'info@nevron.tv',
        admin : [],
        totalAccount : 0,
        totalDevices : 0 ,
      };
    },
    methods: {
      getProfile() {
        this.admin = stores.admin;
      },
      prepareDashboard() {
        stores.accounts.fetchData(1, '', 9999999999)
        .then((response) => {
          this.totalAccount = response.data.length;
        });
        stores.Devices.fetchData(1, '', 999999999)
        .then((response) => {
          this.totalDevices = response.data.length;
        });
      },
    },
    mounted() {
      this.getProfile();
      this.prepareDashboard();
    },
  };
</script>
