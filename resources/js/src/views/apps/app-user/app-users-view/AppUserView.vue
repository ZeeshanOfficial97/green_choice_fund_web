<template>
  <div>
    <!-- Alert: No item found -->
    <b-alert variant="danger" :show="userData === undefined">
      <h4 class="alert-heading">Error fetching user data</h4>
      <div class="alert-body">
        No user found with this user id. Check
        <b-link class="alert-link" :to="{ name: 'app-users' }">
          User List
        </b-link>
        for other users.
      </div>
    </b-alert>

    <template v-if="userData">
      <!-- First Row -->
      <b-row>
        <b-col cols="12" lg="12">
          <b-card no-body>
            <b-card-header class="pb-25">
              <h3>User Details</h3>
              <!-- Spacer -->
            </b-card-header>
            <hr class="demo-inline-spacing" />
            <b-card-body>
              <b-row>
                <!-- name -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Name" label-for="name">
                    <b-form-input id="name" v-model="userData.name" readonly />
                  </b-form-group>
                </b-col>

                <!-- email -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Email" label-for="email">
                    <b-form-input
                      id="email"
                      v-model="userData.email"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- stripe user id -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group
                    label="Stripe User Id"
                    label-for="stripe-user-id"
                  >
                    <b-form-input
                      id="stripe-user-id"
                      v-model="userData.stripe_user_id"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- phone -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Phone" label-for="phone">
                    <b-form-input
                      id="phone"
                      :value="userData.country_code + ' ' + userData.contact_no"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- user type -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="User Type" label-for="user-type">
                    <b-form-input
                      id="user-type"
                      v-model="userData.user_type"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- role -->
                <b-col
                  cols="12"
                  md="4"
                  class="mb-md-0 mb-2"
                  v-if="userData && userData.roles && userData.roles.length > 0"
                >
                  <b-form-group label="Role" label-for="role">
                    <b-form-input
                      id="role"
                      :value="userData.roles[0].slug"
                      readonly
                    />
                  </b-form-group>
                </b-col>


                <!-- status -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Status" label-for="status">
                    <b-form-input
                      id="status"
                      :value="userData.status ? 'Active' : 'Inactive'"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                
              </b-row>
            </b-card-body>
          </b-card>
        </b-col>
      </b-row>
    </template>
  </div>
</template>

<script>
import store from "@/store";
import router from "@/router";
import { ref, onUnmounted } from "@vue/composition-api";
import {
  BFormInput,
  BRow,
  BCol,
  BFormGroup,
  BAlert,
  BLink,
  BFormTextarea,
  BCard,
  BCardHeader,
  BCardBody,
} from "bootstrap-vue";
import appuserStoreModule from "../appuserStoreModule";

export default {
  components: {
    BFormInput,
    BRow,
    BCol,
    BFormGroup,
    BAlert,
    BLink,
    BFormTextarea,
    BCard,
    BCardHeader,
    BCardBody,
  },
  setup() {
    const userData = ref(null);

    const USER_APP_STORE_MODULE_NAME = "app-user";

    // Register module
    if (!store.hasModule(USER_APP_STORE_MODULE_NAME))
      store.registerModule(USER_APP_STORE_MODULE_NAME, appuserStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(USER_APP_STORE_MODULE_NAME))
        store.unregisterModule(USER_APP_STORE_MODULE_NAME);
    });

    store
      .dispatch("app-user/fetchUser", { id: router.currentRoute.params.id })
      .then((response) => {
        
        userData.value = response.data.data;
      })
      .catch((error) => {
        if (error.response.status === 404) {
          userData.value = undefined;
        }
      });

    return {
      userData,
    };
  },
};
</script>

<style>
</style>
