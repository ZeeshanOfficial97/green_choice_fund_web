<template>
  <div>
    <!-- Alert: No item found -->
    <b-alert variant="danger" :show="categoryData === undefined">
      <h4 class="alert-heading">Error fetching category data</h4>
      <div class="alert-body">
        No category found with this category id. Check
        <b-link class="alert-link" :to="{ name: 'app-categories-list' }">
          categories list
        </b-link>
        for other categories.
      </div>
    </b-alert>

    <template v-if="categoryData">
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
                  <h3>Edit Category</h3>
                  <!-- Spacer -->
                </b-card-header>
                <hr class="demo-inline-spacing" />
                <b-card-body>
                  <b-row>
                    <!-- Name -->
                    <b-col cols="12" md="6" class="mb-md-0 mb-2">
                      <!-- Full Name -->
                      <validation-provider
                        #default="validationContext"
                        name="name"
                        rules="required"
                      >
                        <b-form-group label-for="category-name">
                          <template v-slot:label>
                            Category Name <span class="text-danger">*</span>
                          </template>
                          <b-form-input
                            id="category-name"
                            v-model="categoryData.name"
                            autofocus
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Category Name"
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Media -->
                    <b-col cols="12" md="6" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="media"
                        rules=""
                      >
                        <b-form-group label="File" label-for="category-media">
                          <b-form-file
                            id="category-media"
                            accept=".mp4, .jpg, .png, .gif"
                            v-model="categoryData.media"
                            autofocus
                            :state="getValidationState(validationContext)"
                            v-on:change="onFileChange"
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Published -->
                    <b-col cols="12" md="6" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="published"
                        rules=""
                      >
                        <b-form-group label="" label-for="">
                          <b-form-checkbox v-model="categoryData.published">
                            Published
                          </b-form-checkbox>

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <b-col cols="12" md="6" class="mb-md-0 mb-2">
                      <!-- Status -->
                      <validation-provider
                        #default="validationContext"
                        name="status"
                        rules=""
                      >
                        <b-form-group label="" label-for="">
                          <b-form-checkbox v-model="categoryData.status">
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
import categoryStoreModule from "../categoryStoreModule";

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
    const CATEGORY_APP_STORE_MODULE_NAME = "app-category";

    const toast = useToast();
    const showLoaderBtn = ref(false);
    const categoryData = ref(null);

    const { refFormObserver, getValidationState, resetForm } =
      formValidation(categoryData);

    // Register module
    if (!store.hasModule(CATEGORY_APP_STORE_MODULE_NAME))
      store.registerModule(CATEGORY_APP_STORE_MODULE_NAME, categoryStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(CATEGORY_APP_STORE_MODULE_NAME))
        store.unregisterModule(CATEGORY_APP_STORE_MODULE_NAME);
    });

    store
      .dispatch("app-category/fetchCategory", {
        id: router.currentRoute.params.id,
      })
      .then((response) => {
        categoryData.value =
          response.data.data ||
          JSON.parse(
            JSON.stringify({
              name: "",
              published: false,
              status: false,
            })
          );
        categoryData.value.published =
          categoryData.value.published == 1 ? true : false;
        categoryData.value["media"] = [];
      })
      .catch((error) => {
        categoryData.value = undefined;
      });

    const onSubmit = () => {
      showLoaderBtn.value = true;
      let formData = new FormData();
      formData.append("id", categoryData.value.id);
      formData.append("name", categoryData.value.name);
      formData.append("published", categoryData.value.published);
      formData.append("status", categoryData.value.status);
      formData.append(
        "media",
        categoryData.value.media instanceof File
          ? categoryData.value.media
          : null
      );

      store
        .dispatch("app-category/updateCategory", formData)
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
            router.push({ name: "apps-categories-list" });
          }
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

    const onFileChange = (e) => {
      categoryData.value.media = e.target.files[0];
    };

    return {
      showLoaderBtn,
      categoryData,
      onSubmit,
      onFileChange,
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
