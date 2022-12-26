<template>
  <b-sidebar
    id="add-new-faq-sidebar"
    :visible="isAddNewFaqSidebarActive"
    bg-variant="white"
    sidebar-class="sidebar-lg"
    shadow
    backdrop
    no-header
    right
    @hidden="resetForm"
    @change="(val) => $emit('update:is-add-new-faq-sidebar-active', val)"
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
        <h5 class="mb-0">Add New Faq</h5>

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

          <!-- Answer -->
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

          <!-- Status -->
          <validation-provider
            #default="validationContext"
            name="status"
            rules=""
          >
            <b-form-group label="" label-for="">
              <b-form-checkbox v-model="faqData.status">
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
    prop: "isAddNewFaqSidebarActive",
    event: "update:is-add-new-faq-sidebar-active",
  },
  props: {
    isAddNewFaqSidebarActive: {
      type: Boolean,
      required: true,
    },
    categoryOptions: {
      type: Array,
      required: false,
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

    const blankFaqData = {
      question: "",
      answer: "",
      status: false,
    };

    const faqData = ref(JSON.parse(JSON.stringify(blankFaqData)));
    const resetfaqData = () => {
      faqData.value = JSON.parse(JSON.stringify(blankFaqData));
    };

    const showLoaderBtn = ref(false);
    const onSubmit = () => {
      showLoaderBtn.value = true;
      let formData = new FormData();
      formData.append("question", faqData.value.question);
      formData.append("answer", faqData.value.answer);
      formData.append("status", faqData.value.status);

      store
        .dispatch("app-faq/addFaq", formData)
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
            emit("update:is-add-new-faq-sidebar-active", false);
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

    const { refFormObserver, getValidationState, resetForm } =
      formValidation(resetfaqData);

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
  methods: {},
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";

#add-new-faq-sidebar {
  .vs__dropdown-menu {
    max-height: 200px !important;
  }
}
</style>
