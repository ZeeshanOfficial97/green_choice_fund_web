<template>
  <div>
    <!-- Alert: No item found -->
    <b-alert variant="danger" :show="inquiryData === undefined">
      <h4 class="alert-heading">Error fetching inquiry data</h4>
      <div class="alert-body">
        No inquiry found with this id. Check
        <b-link class="alert-link" :to="{ name: 'apps-inquiry' }">
          Inquiry List
        </b-link>
        for other inquiries.
      </div>
    </b-alert>

    <template v-if="inquiryData">
      <!-- First Row -->

      <b-row>
        <b-col cols="12" lg="12">
          <b-card no-body>
            <b-card-header class="pb-25">
              <h3>Inquiry Details</h3>
              <!-- Spacer -->
            </b-card-header>
            <hr class="demo-inline-spacing" />
            <b-card-body>
              <b-row>
                <!-- name -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Name" label-for="name">
                    <b-form-input
                      id="name"
                      v-model="inquiryData.name"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- email -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Email" label-for="email">
                    <b-form-input
                      id="email"
                      v-model="inquiryData.email"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- Phone -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Phone" label-for="phone">
                    <b-form-input
                      id="phone"
                      v-model="inquiryData.phone"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- Contact Reason -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group
                    label="Contact Reason"
                    label-for="contact_reason"
                  >
                    <b-form-input
                      id="contact_reason"
                      v-model="inquiryData.contact_reason.name"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- Date -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Date" label-for="date">
                    <b-form-input
                      id="date"
                      v-model="inquiryData.date"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- Company URL -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Company URL" label-for="company_url">
                    <b-form-input
                      id="company_url"
                      v-model="inquiryData.company_url"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- Address -->
                <b-col cols="12" md="12" class="mb-md-0 mb-2">
                  <b-form-group label="Address" label-for="address">
                    <b-form-textarea
                      id="address"
                      class="mb-1 mb-xl-0"
                      rows="4"
                      max-rows="8"
                      readonly
                      no-resize
                      v-model="inquiryData.address"
                    />
                  </b-form-group>
                </b-col>

                <!-- Description -->
                <b-col cols="12" md="12" class="mb-md-0 mb-2">
                  <b-form-group label="Description" label-for="description">
                    <b-form-textarea
                      id="description"
                      class="mb-1 mb-xl-0"
                      rows="4"
                      max-rows="8"
                      readonly
                      no-resize
                      v-model="inquiryData.description"
                    />
                  </b-form-group>
                </b-col>
              </b-row>
            </b-card-body>
          </b-card>
        </b-col>
      </b-row>
    </template>
    <template v-if="inquiryData && inquiryData.user">
      <!-- First Row -->

      <b-row>
        <b-col cols="12" lg="12">
          <b-card no-body>
            <b-card-header class="pb-50">
              <h5>Inquiry User Details</h5>
            </b-card-header>
            <b-card-body>
              <b-row>
                <!-- user name -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Name" label-for="user-name">
                    <b-form-input
                      id="user-name"
                      v-model="inquiryData.user.name"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- user email -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Email" label-for="user-email">
                    <b-form-input
                      id="user-email"
                      v-model="inquiryData.user.email"
                      readonly
                    />
                  </b-form-group>
                </b-col>

                <!-- user phone -->
                <b-col cols="12" md="4" class="mb-md-0 mb-2">
                  <b-form-group label="Phone" label-for="user-phone">
                    <b-form-input
                      id="user-phone"
                      :value="
                        inquiryData.user.country_code +
                        ' ' +
                        inquiryData.user.contact_no
                      "
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
import inquiryStoreModule from "../inquiryStoreModule";

export default {
  components: {
    BRow,
    BCol,
    BAlert,
    BLink,
    BFormInput,
    BFormGroup,
    BFormTextarea,
    BCard,
    BCardHeader,
    BCardBody,
  },
  setup() {
    const inquiryData = ref(null);

    const INQUIRY_APP_STORE_MODULE_NAME = "app-user-inquiry";

    // Register module
    if (!store.hasModule(INQUIRY_APP_STORE_MODULE_NAME))
      store.registerModule(INQUIRY_APP_STORE_MODULE_NAME, inquiryStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(INQUIRY_APP_STORE_MODULE_NAME))
        store.unregisterModule(INQUIRY_APP_STORE_MODULE_NAME);
    });

    const appLoading = document.getElementById("loading-bg-content");
    if (appLoading) {
      appLoading.style.display = "block";
    }
    store
      .dispatch("app-user-inquiry/fetchInquiry", {
        id: router.currentRoute.params.id,
      })
      .then((response) => {
        inquiryData.value = response.data.data;
        const appLoading = document.getElementById("loading-bg-content");
        if (appLoading) {
          appLoading.style.display = "none";
        }
      })
      .catch((error) => {
        if (error.response.status === 404) {
          inquiryData.value = undefined;
        }
        const appLoading = document.getElementById("loading-bg-content");
        if (appLoading) {
          appLoading.style.display = "none";
        }
      });

    return {
      inquiryData,
    };
  },
};
</script>

<style>
</style>
