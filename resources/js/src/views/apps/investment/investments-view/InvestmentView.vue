<template>
  <div>
    <!-- Alert: No item found -->
    <b-alert variant="danger" :show="investmentData === undefined">
      <h4 class="alert-heading">Error fetching investment data</h4>
      <div class="alert-body">
        No investment found with this investment num. Check
        <b-link class="alert-link" :to="{ name: 'apps-investments-list' }">
          investments list
        </b-link>
        for other investments.
      </div>
    </b-alert>

    <template v-if="investmentData">
      <!-- BODY -->
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <!-- Form -->
        <b-form
          class="p-2"
          @submit.prevent="handleSubmit(onSubmit)"
          @reset.prevent="resetForm"
        >
          <!-- Investment details -->
          <b-row>
            <b-col cols="12" lg="12" class="p-0">
              <b-card no-body>
                <b-card-header class="pb-25">
                  <h3>Investment Details</h3>
                  <!-- Spacer -->
                </b-card-header>
                <hr class="demo-inline-spacing" />
                <b-card-body>
                  <b-row>
                    <!-- Investment # -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="investment #"
                        rules="required"
                      >
                        <b-form-group label-for="investment-num">
                          <template v-slot:label> Investment # </template>
                          <b-form-input
                            id="investment-num"
                            v-model="investmentData.investment_num"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Investment #"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Date # -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="investment date"
                        rules="required"
                      >
                        <b-form-group label-for="investment-date">
                          <template v-slot:label> Investment # </template>
                          <b-form-input
                            id="investment-date"
                            v-model="investmentData.created_at"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Investment Date"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Invested Amount -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="invested amount"
                        rules="required"
                      >
                        <b-form-group label-for="invested-amount">
                          <template v-slot:label> Invested Amount </template>
                          <b-form-input
                            id="investment-amount"
                            v-model="investmentData.invested_amount"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Invested Amount"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Name -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="name"
                        rules="required"
                      >
                        <b-form-group label-for="invester-name">
                          <template v-slot:label> Name </template>
                          <b-form-input
                            id="invester-name"
                            v-model="investmentData.name"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Name"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Email -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="email"
                        rules="required"
                      >
                        <b-form-group label-for="invester-email">
                          <template v-slot:label> Email </template>
                          <b-form-input
                            id="invester-email"
                            v-model="investmentData.email"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Email"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Phone -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <b-form-group label-for="invester-phone">
                        <template v-slot:label> Phone </template>
                        <b-form-input
                          id="phone"
                          :value="
                            investmentData.country_code +
                            ' ' +
                            investmentData.contact_no
                          "
                          readonly
                        />
                      </b-form-group>
                    </b-col>

                    <!-- DOB -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="date of birth"
                        rules="required"
                      >
                        <b-form-group label-for="date-of-birth">
                          <template v-slot:label> DOB </template>
                          <b-form-input
                            id="date-of-birth"
                            v-model="investmentData.dob"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="DOB"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>
                    <!-- DOB -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="channel"
                        rules=""
                      >
                        <b-form-group label-for="date-of-birth">
                          <template v-slot:label> Channel </template>
                          <b-form-input
                            id="channel"
                            v-model="investmentData.channel"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Channel"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Address -->
                    <b-col cols="12" md="12" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="address"
                        rules="required"
                      >
                        <b-form-group label-for="invester-address">
                          <template v-slot:label> Address </template>
                          <b-form-textarea
                            id="address"
                            class="mb-1 mb-xl-0"
                            rows="2"
                            max-rows="8"
                            no-resize
                            v-model="investmentData.address"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Address"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Stripe User Id -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="stripe user id"
                        rules="required"
                      >
                        <b-form-group label-for="stripe-user-id">
                          <template v-slot:label> Stripe User Id </template>
                          <b-form-input
                            id="stripe-user-id"
                            v-model="investmentData.user.stripe_user_id"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Stripe User Id"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Stripe Charge Id -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="stripe charge id"
                        rules=""
                      >
                        <b-form-group label-for="stripe-charge-id">
                          <template v-slot:label> Stripe Charge Id </template>
                          <b-form-input
                            id="stripe-charge-id"
                            v-model="investmentData.stripe_charge_id"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Stripe Charge Id"
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Investment Status -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="{ errors }"
                        name="investment status"
                        rules="required"
                      >
                        <b-form-group label-for="investment-status">
                          <template v-slot:label>
                            Investment Status <span class="text-danger">*</span>
                          </template>

                          <v-select
                            id="investment-status"
                            class="w-100"
                            :clearable="false"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :options="statusOptions"
                            label="label"
                            :reduce="(val) => val.value"
                            v-model="investmentData.investment_status"
                          />

                          <small class="text-danger">{{ errors[0] }}</small>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Form Actions -->
                    <b-col
                      cols="12"
                      md="12"
                      class="mb-md-0 mb-2 d-flex justify-content-end"
                    >
                      <div class="d-flex mt-2">
                        <b-button
                          v-if="showLoaderBtn == false"
                          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                          variant="primary"
                          class="mr-0 text-center"
                          type="submit"
                        >
                          Save
                        </b-button>
                        <b-button
                          v-if="showLoaderBtn == true"
                          v-ripple.400="'rgba(255, 255, 255, 0.15)'"
                          variant="primary"
                          class="mr-0 text-center"
                          type="button"
                        >
                          <b-spinner
                            variant="light"
                            key="light"
                            label="Loading..."
                            small
                          ></b-spinner>
                        </b-button>
                      </div>
                    </b-col>
                  </b-row>
                </b-card-body>
              </b-card>
            </b-col>
          </b-row>

          <!-- User details-->
          <b-row>
            <b-col cols="12" lg="12" class="p-0">
              <b-card no-body>
                <b-card-header class="pb-25">
                  <h3>User Details</h3>
                  <!-- Spacer -->
                </b-card-header>
                <hr class="demo-inline-spacing" />
                <b-card-body>
                  <b-row>
                    <!-- User Name -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="name"
                        rules="required"
                      >
                        <b-form-group label-for="user-name">
                          <template v-slot:label> Name </template>
                          <b-form-input
                            id="user-name"
                            v-model="investmentData.user.name"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Name"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Email -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="email"
                        rules="required"
                      >
                        <b-form-group label-for="user-email">
                          <template v-slot:label> Email </template>
                          <b-form-input
                            id="user-email"
                            v-model="investmentData.user.email"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Email"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Phone -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <b-form-group label-for="user-phone">
                        <template v-slot:label> Phone </template>
                        <b-form-input
                          id="user-phone"
                          :value="
                            investmentData.user.country_code +
                            ' ' +
                            investmentData.user.contact_no
                          "
                          readonly
                        />
                      </b-form-group>
                    </b-col>

                    <!-- Stripe User Id -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="stripe user id"
                        rules="required"
                      >
                        <b-form-group label-for="stripe-user-id">
                          <template v-slot:label> Stripe User Id </template>
                          <b-form-input
                            id="stripe-user-id"
                            v-model="investmentData.user.stripe_user_id"
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Stripe User Id"
                            readonly
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>
                  </b-row>
                </b-card-body>
              </b-card>
            </b-col>
          </b-row>

          <!-- Solution details-->
          <b-row>
            <b-col cols="12" lg="12" class="p-0">
              <b-card no-body>
                <b-card-header class="pb-25">
                  <h3>Solution Details</h3>
                </b-card-header>
                <!-- Spacer -->
                <hr class="demo-inline-spacing" />

                <b-card-body>
                  <div
                    v-for="(item, index) in investmentData.investment_solutions"
                    :id="`item-${index}`"
                    :key="index"
                  >
                    <b-row>
                      <!-- Solution # -->
                      <b-col cols="12" md="12" class="mb-md-0 mb-2">
                        <b-form-group :label-for="`solution-id-${index}`">
                          <h5 :id="`solution-id-${index}`">
                            Solution # {{ index + 1 }}
                          </h5>
                        </b-form-group>
                      </b-col>

                      <!-- Name -->
                      <b-col cols="12" md="4" class="mb-md-0 mb-2">
                        <b-form-group :label-for="`solution-name-${index}`">
                          <template v-slot:label> Solution Name </template>
                          <b-form-input
                            :id="`item-${index}`"
                            :value="item.solution.name"
                            trim
                            placeholder="Solution Name"
                            readonly
                          />
                        </b-form-group>
                      </b-col>

                      <!-- Address -->
                      <b-col cols="12" md="12" class="mb-md-0 mb-2">
                        <b-form-group
                          :label-for="`solution-descrition-${index}`"
                        >
                          <template v-slot:label> Description </template>
                          <b-form-textarea
                            class="mb-1 mb-xl-0"
                            rows="2"
                            max-rows="8"
                            no-resize
                            :id="`solution-name-${index}`"
                            :value="item.solution.description"
                            trim
                            placeholder="Address"
                            readonly
                          />
                        </b-form-group>
                      </b-col>
                      <!-- Spacer -->
                      <hr class="demo-inline-spacing" />
                    </b-row>
                  </div>
                </b-card-body>
              </b-card>
            </b-col>
          </b-row>
        </b-form>
      </validation-observer>
    </template>
  </div>
</template>

<script>
import store from "@/store";
import router from "@/router";
import { ref, onUnmounted } from "@vue/composition-api";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { required, alphaNum } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import Ripple from "vue-ripple-directive";

// Notification
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
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
  BForm,
  BFormInvalidFeedback,
  BButton,
  BFormFile,
  BSpinner,
  BFormCheckbox,
} from "bootstrap-vue";
import investmentStoreModule from "../investmentStoreModule";
import vSelect from "vue-select";

export default {
  components: {
    BRow,
    BCol,
    BFormGroup,

    BCard,
    BCardHeader,
    BCardBody,

    BForm,
    BFormInput,
    BFormFile,
    BFormCheckbox,
    BSpinner,

    BLink,
    BFormTextarea,
    BFormInvalidFeedback,
    BButton,
    BAlert,
    // Form Validation
    ValidationProvider,
    ValidationObserver,
    ToastificationContent,
    vSelect,
  },
  directives: {
    Ripple,
  },
  setup() {
    const INVESTMENT_APP_STORE_MODULE_NAME = "app-user-investment";

    const toast = useToast();
    const showLoaderBtn = ref(false);
    const investmentData = ref(null);

    const { refFormObserver, getValidationState, resetForm } =
      formValidation(resetinvestmentData);
    const resetinvestmentData = () => {
      investmentData.value = JSON.parse(JSON.stringify({}));
    };

    // Register module
    if (!store.hasModule(INVESTMENT_APP_STORE_MODULE_NAME))
      store.registerModule(
        INVESTMENT_APP_STORE_MODULE_NAME,
        investmentStoreModule
      );

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(INVESTMENT_APP_STORE_MODULE_NAME))
        store.unregisterModule(INVESTMENT_APP_STORE_MODULE_NAME);
    });

    const statusOptions = [
      { label: "Pending", value: 1 },
      { label: "Verified", value: 2 },
      { label: "In Process", value: 3 },
      { label: "Process", value: 4 },
      { label: "Cancelled", value: 5 },
    ];

    const appLoading = document.getElementById("loading-bg-content");
    if (appLoading) {
      appLoading.style.display = "block";
    }
    store
      .dispatch("app-user-investment/fetchInvestment", {
        id: router.currentRoute.params.id,
      })
      .then((response) => {
        debugger;
        if(response.data.data) {
          if(response.data.data.investment_status || response.data.data.investment_status == 0) {
            response.data.data.investment_status = response.data.data.investment_status + 1;
          }
        }
        investmentData.value = response.data.data || undefined;
        const appLoading = document.getElementById("loading-bg-content");
        if (appLoading) {
          appLoading.style.display = "none";
        }
      })
      .catch((error) => {
        investmentData.value = undefined;
        const appLoading = document.getElementById("loading-bg-content");
        if (appLoading) {
          appLoading.style.display = "none";
        }
      });

    const onSubmit = () => {
      debugger;
      showLoaderBtn.value = true;
      investmentData.value.investment_status= investmentData.value.investment_status - 1;
      store
        .dispatch(
          "app-user-investment/updateInvestmentStatus",
          investmentData.value
        )
        .then((response) => {
          showLoaderBtn.value = false;

          toast({
            component: ToastificationContent,
            props: {
              title: response?.data?.message,
              icon: "AlertTriangleIcon",
              variant:
                response?.data?.code === 200
                  ? "success"
                  : response?.data?.code === 610
                  ? "danger"
                  : "danger",
            },
          });

          // if (response?.data?.code === 200) {
          //   router.push({ name: "apps-categories-list" });
          // }
        })
        .catch((err) => {
          toast({
            component: ToastificationContent,
            props: {
              title: err?.message ? err.message : err?.response?.data?.message,
              icon: "AlertTriangleIcon",
              variant: "danger",
            },
          });
          showLoaderBtn.value = false;
        });
    };

    return {
      showLoaderBtn,
      investmentData,
      onSubmit,
      refFormObserver,
      getValidationState,
      resetForm,
      toast,
      statusOptions,
    };
  },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";
</style>
