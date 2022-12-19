<template>
  <b-sidebar
    id="add-new-solution-sidebar"
    :visible="isAddNewSolutionSidebarActive"
    bg-variant="white"
    sidebar-class="sidebar-lg"
    shadow
    backdrop
    no-header
    right
    @hidden="resetForm"
    @change="(val) => $emit('update:is-add-new-solution-sidebar-active', val)"
  >
    <template #default="{ hide }">
      <!-- Header -->
      <div
        class="
          d-flex
          justify-content-between
          align-items-center
          content-sidebar-header
          px-2
          py-1
        "
      >
        <h5 class="mb-0">Add New Solution</h5>

        <feather-icon
          class="ml-1 cursor-pointer"
          icon="XIcon"
          size="16"
          @click="hide"
        />
      </div>

      <!-- BODY -->
      <validation-observer #default="{ handleSubmit }" ref="refFormObserver">
        <!-- Form -->
        <b-form
          enctype="multipart/form-data"
          class="p-2"
          @submit.prevent="handleSubmit(onSubmit)"
          @reset.prevent="resetForm"
        >
          <!-- Name -->
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

          <!-- Category Id -->
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
                :reduce="(val) => (val.value || '').toString()"
                :options="categoryOptions"
              />

              <small class="text-danger">{{ errors[0] }}</small>
            </b-form-group>
          </validation-provider>
          <!-- <validation-provider
            #default="validationContext"
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
                :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
                :options="categoryOptions"
                label="label"
                :reduce="(val) => (val.value || '').toString()"
                v-model="solutionData.categoryId"
                :state="getValidationState(validationContext)"
                :rules="[(v) => !!v || 'This is required']"
                required
              >
                <template #search="{ attributes, events }">
                  <input
                    class="vs__search"
                    :required="!solutionData.categoryId"
                    :rules="[(v) => !!v || 'This is required']"
                    v-bind="attributes"
                    v-on="events"
                  />
                </template>
              </v-select>

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider> -->

          <!-- Media -->
          <validation-provider
            #default="validationContext"
            name="media"
            rules="required"
          >
            <b-form-group label-for="solution-media">
              <template v-slot:label>
                File <span class="text-danger">*</span>
              </template>
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

          <!-- Description -->
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

          <!-- Published -->
          <validation-provider
            #default="validationContext"
            name="published"
            rules=""
          >
            <b-form-group label="" label-for="">
              <b-form-checkbox v-model="solutionData.published">
                Published
              </b-form-checkbox>

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <!-- Status -->
          <validation-provider
            #default="validationContext"
            name="status"
            rules=""
          >
            <b-form-group label="" label-for="">
              <b-form-checkbox v-model="solutionData.status">
                Status
              </b-form-checkbox>

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <!-- Form Actions -->
          <div class="d-flex mt-2">
            <b-button
              v-if="showLoaderBtn == false"
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              class="mr-2 text-center"
              type="submit"
            >
              Add
            </b-button>
            <b-button
              v-if="showLoaderBtn == true"
              v-ripple.400="'rgba(255, 255, 255, 0.15)'"
              variant="primary"
              class="mr-2 text-center"
              type="button"
            >
              <b-spinner
                variant="light"
                key="light"
                label="Loading..."
                small
              ></b-spinner>
            </b-button>
            <b-button
              v-ripple.400="'rgba(186, 191, 199, 0.15)'"
              type="button"
              variant="outline-secondary"
              @click="hide"
            >
              Cancel
            </b-button>
          </div>
        </b-form>
      </validation-observer>
    </template>
  </b-sidebar>
</template>

<script>
import {
  BSidebar,
  BForm,
  BFormGroup,
  BFormInput,
  BFormInvalidFeedback,
  BButton,
  BFormTextarea,
  BFormFile,
  BSpinner,
  BFormCheckbox,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";
import { required, alphaNum } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import Ripple from "vue-ripple-directive";
import vSelect from "vue-select";
import store from "@/store";
// Notification
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BButton,
    BFormTextarea,
    BFormFile,
    BSpinner,
    BFormCheckbox,
    vSelect,
    // Form Validation
    ValidationProvider,
    ValidationObserver,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: "isAddNewSolutionSidebarActive",
    event: "update:is-add-new-solution-sidebar-active",
  },
  props: {
    isAddNewSolutionSidebarActive: {
      type: Boolean,
      required: true,
    },
    categoryOptions: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      required,
      alphaNum,
    };
  },
  setup(props, { emit }) {
    const toast = useToast();

    const blankSolutionData = {
      name: "",
      description: "",
      published: false,
      status: false,
      media: [],
      categoryId: null,
    };

    const solutionData = ref(JSON.parse(JSON.stringify(blankSolutionData)));
    const resetsolutionData = () => {
      solutionData.value = JSON.parse(JSON.stringify(blankSolutionData));
    };

    const showLoaderBtn = ref(false);
    const onSubmit = () => {
      
      showLoaderBtn.value = true;
      let formData = new FormData();
      formData.append("name", solutionData.value.name);
      formData.append("category_id", solutionData.value.categoryId);
      formData.append("description", solutionData.value.description);
      formData.append("published", solutionData.value.published);
      formData.append("status", solutionData.value.status);
      (solutionData.value.media || []).forEach((image) => {
        formData.append("media[]", image);
      });
      // formData.append("media", solutionData.value.files);

      store
        .dispatch("app-solution/addSolution", formData)
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
            emit("refetch-data");
            emit("update:is-add-new-solution-sidebar-active", false);
          }
        })
        .catch((err) => {
          showLoaderBtn.value = false;
          this.$toast({
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

    const { refFormObserver, getValidationState, resetForm } =
      formValidation(resetsolutionData);

    return {
      showLoaderBtn,
      solutionData,
      onSubmit,
      onFileChange,
      refFormObserver,
      getValidationState,
      resetForm,
      toast,
    };
  },
  methods: {},
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";

#add-new-solution-sidebar {
  .vs__dropdown-menu {
    max-height: 200px !important;
  }
}
</style>
