<template>
  <div>
    <!-- Alert: No item found -->
    <b-alert variant="danger" :show="solutionData === undefined">
      <h4 class="alert-heading">Error fetching solution data</h4>
      <div class="alert-body">
        No solution found with this id. Check
        <b-link class="alert-link" :to="{ name: 'apps-solutions-list' }">
          solutions list
        </b-link>
        for other solutions.
      </div>
    </b-alert>

    <template v-if="solutionData">
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
                  <h3>Solutions Details</h3>
                  <!-- Spacer -->
                </b-card-header>
                <hr class="demo-inline-spacing" />
                <b-card-body>
                  <b-row>
                    <!-- Name # -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="name"
                        rules="required"
                      >
                        <b-form-group label-for="solution-name">
                          <template v-slot:label>
                            Solution Name <span class="text-danger">*</span>
                          </template>
                          <b-form-input
                            id="solution-name"
                            v-model="solutionData.name"
                            autofocus
                            :state="getValidationState(validationContext)"
                            trim
                            placeholder="Solution Name"
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Category Id -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="{ errors }"
                        name="category"
                        rules="required"
                      >
                        <b-form-group label-for="category-id">
                          <template v-slot:label>
                            Category <span class="text-danger">*</span>
                          </template>

                          <v-select
                            id="category-id"
                            class="w-100"
                            label="label"
                            v-model="solutionData.categoryId"
                            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                            :reduce="(val) => val.value"
                            :options="categoryOptions"
                            :clearable="false"
                          />

                          <small class="text-danger">{{ errors[0] }}</small>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Media -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="media"
                        rules=""
                      >
                        <b-form-group label-for="solution-media">
                          <template v-slot:label> File </template>
                          <b-form-file
                            id="solution-media"
                            accept="image/jpeg, image/jpg, image/png"
                            v-model="solutionData.media"
                            autofocus
                            multiple
                            :state="getValidationState(validationContext)"
                            v-on:change="onFileChange"
                            ref="mediaFiles"
                          />

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Description -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
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
                            v-model="solutionData.description"
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
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="published"
                        rules=""
                      >
                        <b-form-group label="" label-for="published">
                          <label></label>
                          <b-form-checkbox v-model="solutionData.published">
                            Published
                          </b-form-checkbox>

                          <b-form-invalid-feedback>
                            {{ validationContext.errors[0] }}
                          </b-form-invalid-feedback>
                        </b-form-group>
                      </validation-provider>
                    </b-col>

                    <!-- Published -->
                    <b-col cols="12" md="4" class="mb-md-0 mb-2">
                      <validation-provider
                        #default="validationContext"
                        name="status"
                        rules=""
                      >
                        <b-form-group label="" label-for="status">
                          <label></label>
                          <b-form-checkbox v-model="solutionData.status">
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

          <!-- Solution details-->
          <b-row>
            <b-col cols="12" lg="12" class="p-0">
              <b-card no-body>
                <b-card-header class="pb-25">
                  <h3>Solution Media</h3>
                </b-card-header>
                <!-- Spacer -->
                <hr class="demo-inline-spacing" />

                <b-card-body>
                  <b-row>
                    <div class="d-flex">
                      <!-- Solution # -->
                      <b-col
                        v-for="(item, index) in solutionData.solution_media"
                        :id="`item-${index}`"
                        :key="index"
                        cols="3"
                        md="3"
                        class="mb-2 text-center"
                      >
                        <div class="position-relative">
                          <img
                            :src="item.image"
                            :alt="item.image"
                            class="img-responsive img-fluid w-75"
                            style="border-radius: 4px"
                          />
                          <div class="img-remove">
                            <span
                              @click.self="deleteImage(index)"
                              class="img-icon text-danger"
                              >X</span
                            >
                          </div>
                        </div>
                      </b-col>
                    </div>
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
import solutionStoreModule from "../solutionStoreModule";
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
    const SOLUTION_APP_STORE_MODULE_NAME = "app-solution";

    const toast = useToast();
    const showLoaderBtn = ref(false);
    const solutionData = ref(null);

    const { refFormObserver, getValidationState, resetForm } =
      formValidation(solutionData);

    // Register module
    if (!store.hasModule(SOLUTION_APP_STORE_MODULE_NAME))
      store.registerModule(SOLUTION_APP_STORE_MODULE_NAME, solutionStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(SOLUTION_APP_STORE_MODULE_NAME))
        store.unregisterModule(SOLUTION_APP_STORE_MODULE_NAME);
    });

    let categoryOptions = ref([]);

    store
      .dispatch("app-solution/fetchCategoriesDDL")
      .then((response) => {
        categoryOptions.value = response.data.data || [];
      })
      .catch((error) => {
        categoryOptions.value = [];
      });

    store
      .dispatch("app-solution/fetchSolution", {
        id: router.currentRoute.params.id,
      })
      .then((response) => {
        solutionData.value =
          response.data.data ||
          JSON.parse(
            JSON.stringify({
              name: "",
              description: "",
              categoryId: null,
              published: false,
              status: false,
              solution_media: [],
            })
          );

        if (response.data.data) {
          solutionData.value.published =
            response.data.data.published == "published";
          solutionData.value.status = response.data.data.status == "active";
          solutionData.value.categoryId = response.data.data.category_id;
        }
        solutionData.value.published =
          solutionData.value.published == 1 ? true : false;
        solutionData.value["media"] = [];
      })
      .catch((error) => {
        solutionData.value = undefined;
      });

    const onSubmit = () => {

      showLoaderBtn.value = true;
      let formData = new FormData();
      formData.append("id", solutionData.value.id);
      formData.append("name", solutionData.value.name);
      formData.append("category_id", solutionData.value.categoryId);
      formData.append("description", solutionData.value.description);
      formData.append("published", solutionData.value.published);
      formData.append("status", solutionData.value.status);

      (solutionData.value.media || []).forEach((image) => {
        formData.append("media[]", image);
      });
      (solutionData.value.solution_media || []).forEach((obj) => {
        formData.append("solution_media_id[]", +obj.id);
      });

      store
        .dispatch("app-solution/updateSolution", formData)
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
            router.push({ name: "apps-solutions-list" });
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

    const onFileChange = (e) => {
      let isValid = true;
      (e.target.files || []).forEach((obj) => {
        if (!["image/jpeg", "image/jpg", "image/png"].includes(obj["type"])) {
          isValid = false;
        }
      });
      if (!isValid) {
        solutionData.value.media = [];
      }
    };

    const deleteImage = (index) => {
      solutionData.value.solution_media.splice(index, 1);
    };

    return {
      showLoaderBtn,
      solutionData,
      onSubmit,
      onFileChange,
      refFormObserver,
      getValidationState,
      resetForm,
      toast,
      categoryOptions,
      deleteImage,
    };
  },
};
</script>
  
<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";
</style>
<style lang="scss">
.img-remove {
  position: absolute;
  right: 13%;
  top: 0px;
  padding: 0.15rem 0.25rem;
  border-radius: 100px;
  font-weight: bolder;
  font-size: 1.5rem;
  cursor: pointer;
  z-index: 1000;
  & .img-icon {
    font-size: 1.5rem;
  }
}
</style>
  