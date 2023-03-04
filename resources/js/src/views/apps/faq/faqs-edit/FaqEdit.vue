<template>
  <div>
    <!-- Alert: No item found -->
    <b-alert variant="danger" :show="faqData === undefined">
      <h4 class="alert-heading">Error fetching faq data</h4>
      <div class="alert-body">
        No faq found with this id. Check
        <b-link class="alert-link" :to="{ name: 'apps-faqs-list' }">
          faqs list
        </b-link>
        for other faqs.
      </div>
    </b-alert>

    <template v-if="faqData">
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
                  <h3>Faqs Details</h3>
                  <!-- Spacer -->
                </b-card-header>
                <hr class="demo-inline-spacing" />
                <b-card-body>
                  <b-row>
                    <!-- Question # -->
                    <b-col cols="12" md="5" class="mb-md-0 mb-2">
                      <!-- Question -->
                      <validation-provider
                        #default="validationContext"
                        name="question"
                        rules="required"
                      >
                        <b-form-group label-for="question">
                          <template v-slot:label>
                            Question <span class="text-danger">*</span>
                          </template>
                          <b-form-textarea
                            id="question"
                            v-model="faqData.question"
                            :state="getValidationState(validationContext)"
                            trim
                            rows="3"
                            max-rows="8"
                            class="mb-1 mb-xl-0"
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Answer -->
                    <b-col cols="12" md="5" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="answer"
                        rules="required"
                      >
                        <b-form-group label-for="answer">
                          <template v-slot:label>
                            Answer <span class="text-danger">*</span>
                          </template>
                          <b-form-textarea
                            id="answer"
                            v-model="faqData.answer"
                            :state="getValidationState(validationContext)"
                            trim
                            rows="3"
                            max-rows="8"
                            class="mb-1 mb-xl-0"
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Published -->
                    <b-col cols="12" md="2" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="status"
                        rules=""
                      >
                        <b-form-group label="" label-for="status">
                          <label></label>
                          <b-form-checkbox v-model="faqData.status">
                            Status
                          </b-form-checkbox>

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
import faqStoreModule from "../faqStoreModule";

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
    const FAQ_APP_STORE_MODULE_NAME = "app-faq";

    const toast = useToast();
    const showLoaderBtn = ref(false);
    const faqData = ref(null);

    const { refFormObserver, getValidationState, resetForm } =
      formValidation(faqData);

    // Register module
    if (!store.hasModule(FAQ_APP_STORE_MODULE_NAME))
      store.registerModule(FAQ_APP_STORE_MODULE_NAME, faqStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(FAQ_APP_STORE_MODULE_NAME))
        store.unregisterModule(FAQ_APP_STORE_MODULE_NAME);
    });

    const appLoading = document.getElementById("loading-bg-content");
    if (appLoading) {
      appLoading.style.display = "block";
    }
    store
      .dispatch("app-faq/fetchFaq", {
        id: router.currentRoute.params.id,
      })
      .then((response) => {
        faqData.value =
          response.data.data ||
          JSON.parse(
            JSON.stringify({
              question: "",
              answer: "",
              status: false,
            })
          );

        if (response.data.data) {
          faqData.value.status = response.data.data.status == "active";
        }
        const appLoading = document.getElementById("loading-bg-content");
        if (appLoading) {
          appLoading.style.display = "none";
        }
      })
      .catch((error) => {
        faqData.value = undefined;
        const appLoading = document.getElementById("loading-bg-content");
        if (appLoading) {
          appLoading.style.display = "none";
        }
      });

    const onSubmit = () => {
      showLoaderBtn.value = true;
      let formData = new FormData();
      formData.append("id", faqData.value.id);
      formData.append("question", faqData.value.question);
      formData.append("answer", faqData.value.answer);
      formData.append("status", faqData.value.status);

      store
        .dispatch("app-faq/updateFaq", formData)
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

          if (response?.data?.code === 200) {
            router.push({ name: "apps-faqs-list" });
          }
        })
        .catch((err) => {
          showLoaderBtn.value = false;
          toast({
            component: ToastificationContent,
            props: {
              title: err?.message ? err.message : err?.response?.data?.message,
              icon: "AlertTriangleIcon",
              variant: "danger",
            },
          });
        });
    };

    return {
      showLoaderBtn,
      faqData,
      onSubmit,
      refFormObserver,
      getValidationState,
      resetForm,
      toast,
    };
  },
};
</script>
  
<style lang="scss">
</style>

  