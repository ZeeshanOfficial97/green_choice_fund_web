<template>
  <div>
    <template>
      <!-- BODY -->
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <!-- Form -->
        <b-form
          class="p-2"
          @submit.prevent="handleSubmit(onSubmit)"
          @reset.prevent="resetForm"
        >
          <b-row>
            <b-col cols="12" lg="12" class="p-0">
              <b-card no-body>
                <b-card-header class="pb-25">
                  <h3>Send Notification</h3>
                  <!-- Spacer -->
                </b-card-header>
                <hr class="demo-inline-spacing" />
                <b-card-body>
                  <b-row>
                    <!-- Name -->
                    <b-col cols="12" md="12" class="mb-md-0 mb-2">
                      <!-- Full Name -->
                      <validation-provider
                        #default="validationContext"
                        name="title"
                        rules="required"
                      >
                        <b-form-group label-for="title">
                          <template v-slot:label>
                            Title <span class="text-danger">*</span>
                          </template>
                          <b-form-input
                            id="title"
                            v-model="notificationData.title"
                            autofocus
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Title"
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Description -->
                    <b-col cols="12" md="12" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="description"
                        rules="required"
                      >
                        <b-form-group label-for="description">
                          <template v-slot:label>
                            Description <span class="text-danger">*</span>
                          </template>
                          <b-form-textarea
                            id="description"
                            v-model="notificationData.description"
                            :state="getValidationState(validationContext)"
                            trim
                            rows="4"
                            max-rows="8"
                            class="mb-1 mb-xl-0"
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
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
                          Send
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
import notificationStoreModule from "../notificationStoreModule";

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
  },
  directives: {
    Ripple,
  },
  setup() {
    const NOTIFICATION_APP_STORE_MODULE_NAME = "app-notification";

    const toast = useToast();
    const showLoaderBtn = ref(false);

    const blankNotificationData = {
      title: "",
      description: "",
    };

    const notificationData = ref(
      JSON.parse(JSON.stringify(blankNotificationData))
    );
    const resetNotificationData = () => {
      notificationData.value = JSON.parse(
        JSON.stringify(blankNotificationData)
      );
    };

    const { refFormObserver, getValidationState, resetForm } = formValidation(
      resetNotificationData
    );

    // Register module
    if (!store.hasModule(NOTIFICATION_APP_STORE_MODULE_NAME))
      store.registerModule(
        NOTIFICATION_APP_STORE_MODULE_NAME,
        notificationStoreModule
      );

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(NOTIFICATION_APP_STORE_MODULE_NAME))
        store.unregisterModule(NOTIFICATION_APP_STORE_MODULE_NAME);
    });

    const onSubmit = () => {
      showLoaderBtn.value = true;
      let formData = new FormData();
      formData.append("title", notificationData.value.title);
      formData.append("description", notificationData.value.description);

      store
        .dispatch("app-notification/sendNotification", formData)
        .then((response) => {
          // showLoaderBtn.value = false;
          // toast({
          //   component: ToastificationContent,
          //   props: {
          //     title: response?.data?.message,
          //     icon: "AlertTriangleIcon",
          //     variant:
          //       response?.data?.code === 200
          //         ? "success"
          //         : response?.data?.code === 610
          //         ? "danger"
          //         : "danger",
          //   },
          // });
          // if (response?.data?.code === 200) {
          //   notificationData.value.title = '';
          //   notificationData.value.description = '';
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

      setTimeout(() => {
        toast({
          component: ToastificationContent,
          props: {
            title: "Notification sent successfully",
            icon: "AlertTriangleIcon",
            variant: "success",
          },
        });

        showLoaderBtn.value = false;
        resetNotificationData();
      }, 2000);
    };

    return {
      showLoaderBtn,
      notificationData,
      onSubmit,
      refFormObserver,
      getValidationState,
      resetForm,
      toast,
    };
  },
};
</script>

<style>
</style>
