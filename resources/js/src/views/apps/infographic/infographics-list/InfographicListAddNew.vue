<template>
  <b-sidebar
    id="add-new-infographic-sidebar"
    :visible="isAddNewInfographicSidebarActive"
    bg-variant="white"
    sidebar-class="sidebar-lg"
    shadow
    backdrop
    no-header
    right
    @hidden="resetForm"
    @change="(val) => $emit('update:is-add-new-infographic-sidebar-active', val)"
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
        <h5 class="mb-0">Add New Infographic</h5>

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
          class="p-2"
          @submit.prevent="handleSubmit(onSubmit)"
          @reset.prevent="resetForm"
        >
          <!-- Full Name -->
          <validation-provider
            #default="validationContext"
            name="name"
            rules="required"
          >
            <b-form-group label-for="infographic-name">
              <template v-slot:label>
                Name <span class="text-danger">*</span>
              </template>
              <b-form-input
                id="infographic-name"
                v-model="infographicData.name"
                autofocus
                :state="getValidationState(validationContext)"
                trim
                placeholder="Name"
              />

              <b-form-invalid-feedback>
                {{ validationContext.errors[0] }}
              </b-form-invalid-feedback>
            </b-form-group>
          </validation-provider>

          <!-- Media -->
          <validation-provider
            #default="validationContext"
            name="media"
            rules="required"
          >
            <b-form-group label-for="infographic-media">
              <template v-slot:label>
                File <span class="text-danger">*</span>
              </template>

              <b-form-file
                id="infographic-media"
                accept=".jpg, .png, .jpeg"
                v-model="infographicData.media"
                autofocus
                :state="getValidationState(validationContext)"
                v-on:change="onFileChange"
              />

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
  BFormFile,
  BSpinner,
  BFormCheckbox,
} from "bootstrap-vue";
import { ValidationProvider, ValidationObserver } from "vee-validate";
import { ref } from "@vue/composition-api";
import { required, alphaNum } from "@validations";
import formValidation from "@core/comp-functions/forms/form-validation";
import Ripple from "vue-ripple-directive";
import store from "@/store";
// Notification
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";
import axios from "@axios";

export default {
  components: {
    BSidebar,
    BForm,
    BFormGroup,
    BFormInput,
    BFormInvalidFeedback,
    BButton,
    BFormFile,
    BSpinner,
    BFormCheckbox,
    // Form Validation
    ValidationProvider,
    ValidationObserver,
    ToastificationContent,
  },
  directives: {
    Ripple,
  },
  model: {
    prop: "isAddNewInfographicSidebarActive",
    event: "update:is-add-new-infographic-sidebar-active",
  },
  props: {
    isAddNewInfographicSidebarActive: {
      type: Boolean,
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
    const blankInfographicData = {
      name: "",
      media: [],
    };

    const infographicData = ref(JSON.parse(JSON.stringify(blankInfographicData)));
    const resetinfographicData = () => {
      infographicData.value = JSON.parse(JSON.stringify(blankInfographicData));
    };

    const showLoaderBtn = ref(false);
    const onSubmit = () => {
      showLoaderBtn.value = true;
      let formData = new FormData();
      formData.append("name", infographicData.value.name);
      formData.append(
        "media",
        infographicData.value.media instanceof File
          ? infographicData.value.media
          : null
      );

      store
        .dispatch("app-infographic/addInfographic", formData)
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
            emit("update:is-add-new-infographic-sidebar-active", false);
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
      infographicData.value.media = e.target.files;
    };

    const { refFormObserver, getValidationState, resetForm } =
      formValidation(resetinfographicData);

    return {
      showLoaderBtn,
      infographicData,
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

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";

#add-new-infographic-sidebar {
  .vs__dropdown-menu {
    max-height: 200px !important;
  }
}
</style>
