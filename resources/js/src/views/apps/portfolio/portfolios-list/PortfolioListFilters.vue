<template>
  <b-card no-body>
    <b-card-header class="pb-50">
      <h5>Filters</h5>
    </b-card-header>
    <b-card-body>
      <b-row>
        <b-col cols="12" md="4" class="mb-md-0 mb-2">
          <label>User</label>
          <v-select
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :value="userFilter"
            :options="userOptions"
            :reduce="(val) => (val.value || '').toString()"
            class="w-100"
            label="label"
            @input="(val) => $emit('update:userFilter', val)"
          />
        </b-col>

        <b-col cols="12" md="4" class="mb-md-0 mb-2">
          <label>Category</label>
          <v-select
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :value="categoryFilter"
            :options="categoryOptions"
            :reduce="(val) => (val.value || '').toString()"
            class="w-100"
            label="label"
            @input="(val) => $emit('update:categoryFilter', val)"
          />
        </b-col>

        <b-col cols="12" md="4" class="mb-md-0 mb-2">
          <label>Solution</label>
          <v-select
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :value="solutionFilter"
            :options="solutionOptions"
            :reduce="(val) => (val.value || '').toString()"
            class="w-100"
            label="label"
            @input="(val) => $emit('update:solutionFilter', val)"
          />
        </b-col>
        
      </b-row>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardHeader, BCardBody, BRow, BCol } from "bootstrap-vue";
import vSelect from "vue-select";

export default {
  components: {
    BRow,
    BCol,
    BCard,
    BCardHeader,
    BCardBody,
    vSelect,
  },
  props: {
    userFilter: {
      type: [String, null],
      default: null,
    },
    categoryFilter: {
      type: [String, null],
      default: null,
    },

    solutionFilter: {
      type: [String, null],
      default: null,
    },

    userOptions: {
      type: Array,
      required: true,
    },
    categoryOptions: {
      type: Array,
      required: true,
    },
    solutionOptions: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";
</style>
