<template>
  <b-card no-body>
    <b-card-header class="pb-50">
      <h5>
        Filters
      </h5>
    </b-card-header>
    <b-card-body>
      <b-row>
        
        <b-col
          cols="12"
          md="4"
          class="mb-md-0 mb-2"
        >
          <label>Category</label>
          <v-select
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :value="categoryFilter"
            :options="categoryOptions"
            :reduce="val => (val.value || '').toString()"
            class="w-100"
            label="label"
            @input="(val) => $emit('update:categoryFilter', val)"
          />
        </b-col>

        <b-col
          cols="12"
          md="4"
          class="mb-md-0 mb-2"
        >
          <label>Publish</label>
          <v-select
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :value="publishFilter"
            :options="publishOptions"
            class="w-100"
            :reduce="val => val.value"
            @input="(val) => $emit('update:publishFilter', val)"
          />
        </b-col>
        <b-col
          cols="12"
          md="4"
          class="mb-md-0 mb-2"
        >
          <label>Status</label>
          <v-select
            :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
            :value="statusFilter"
            :options="statusOptions"
            class="w-100"
            :reduce="val => val.value"
            @input="(val) => $emit('update:statusFilter', val)"
          />
        </b-col>
      </b-row>
    </b-card-body>
  </b-card>
</template>

<script>
import { BCard, BCardHeader, BCardBody, BRow, BCol } from 'bootstrap-vue'
import vSelect from 'vue-select'

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
    
    categoryFilter: {
      type: [String, null],
      default: null,
    },
    publishFilter: {
      type: [String, null],
      default: null,
    },
    statusFilter: {
      type: [String, null],
      default: null,
    },

    categoryOptions: {
      type: Array,
      required: true,
    },    
    publishOptions: {
      type: Array,
      required: true,
    },
    statusOptions: {
      type: Array,
      required: true,
    },
  },
}
</script>

<style lang="scss">
@import '~@resources/scss/vue/libs/vue-select.scss';
</style>
