<template>
  <div>
    <!-- Filters -->
    <portfolio-list-filters
      :user-filter.sync="userFilter"
      :category-filter.sync="categoryFilter"
      :solution-filter.sync="solutionFilter"
      :user-options="userOptions"
      :category-options="categoryOptions"
      :solution-options="solutionOptions"
    />

    <!-- Table Container Card -->
    <b-card no-body class="mb-0">
      <div class="m-2">
        <!-- Table Top -->
        <b-row>
          <!-- Per Page -->
          <b-col
            cols="12"
            md="6"
            class="d-flex align-items-center justify-content-start mb-1 mb-md-0"
          >
            <label>Show</label>
            <v-select
              v-model="perPage"
              :dir="$store.state.appConfig.isRTL ? 'rtl' : 'ltr'"
              :options="perPageOptions"
              :clearable="false"
              class="per-page-selector d-inline-block mx-50"
            />
            <label>entries</label>
          </b-col>

          <!-- Search -->
          <b-col cols="12" md="6">
            <div class="d-flex align-items-center justify-content-end">
              <!-- <b-form-input
                v-model="searchQuery"
                class="d-inline-block mr-1"
                placeholder="Search..."
              /> -->
              <b-button variant="primary" @click="downloadCSV">
                <span class="text-nowrap">Download CSV</span>
              </b-button>
            </div>
          </b-col>
        </b-row>
      </div>

      <b-table
        ref="refPortfoliosListTable"
        class="position-relative"
        :items="fetchPortfolios"
        responsive
        :fields="tableColumns"
        primary-key="id"
        :sort-by.sync="sortBy"
        show-empty
        empty-text="No matching records found"
        :sort-desc.sync="isSortDirDesc"
      >
        <!-- Column: User -->
        <template #cell(user)="data">
          {{ data.item.user.name }}
        </template>

        <!-- Column: Email -->
        <template #cell(email)="data">
          {{ data.item.user.email }}
        </template>

        <!-- Column: Phone -->
        <template #cell(phone)="data">
          {{ data.item.user.country_code }}-{{ data.item.user.contact_no }}
        </template>

        <!-- Column: Category -->
        <template #cell(category)="data">
          {{ data.item.category.name }}
        </template>

        <!-- Column: Solution -->
        <template #cell(solution)="data">
          {{ data.item.solution.name }}
        </template>

        <!-- Column: Actions -->
        <template #cell(actions)="data">
          <b-dropdown
            variant="link"
            no-caret
            :right="$store.state.appConfig.isRTL"
          >
            <template #button-content>
              <feather-icon
                icon="MoreVerticalIcon"
                size="16"
                class="align-middle text-body"
              />
            </template>
            <b-dropdown-item
              :to="{
                name: 'apps-solutions-edit',
                params: { id: data.item.solution_id },
              }"
            >
              <feather-icon
                icon="FileTextIcon"
                :to="{
                  name: 'apps-solutions-edit',
                  params: { id: data.item.solution_id },
                }"
              />
              <span
                class="align-middle ml-50"
                :to="{
                  name: 'apps-solutions-edit',
                  params: { id: data.item.solution_id },
                }"
                >View Solution</span
              >
            </b-dropdown-item>
          </b-dropdown>
        </template>
      </b-table>
      <div class="mx-2 mb-2">
        <b-row>
          <b-col
            cols="12"
            sm="6"
            class="
              d-flex
              align-items-center
              justify-content-center justify-content-sm-start
            "
          >
            <span class="text-muted"
              >Showing {{ dataMeta.from }} to {{ dataMeta.to }} of
              {{ dataMeta.of }} entries</span
            >
          </b-col>
          <!-- Pagination -->
          <b-col
            cols="12"
            sm="6"
            class="
              d-flex
              align-items-center
              justify-content-center justify-content-sm-end
            "
          >
            <b-pagination
              v-model="currentPage"
              :total-rows="totalPortfolios"
              :per-page="perPage"
              first-number
              last-number
              class="mb-0 mt-1 mt-sm-0"
              prev-class="prev-item"
              next-class="next-item"
            >
              <template #prev-text>
                <feather-icon icon="ChevronLeftIcon" size="18" />
              </template>
              <template #next-text>
                <feather-icon icon="ChevronRightIcon" size="18" />
              </template>
            </b-pagination>
          </b-col>
        </b-row>
      </div>
    </b-card>
  </div>
</template>

<script>
import {
  BCard,
  BRow,
  BCol,
  BFormInput,
  BButton,
  BTable,
  BMedia,
  BAvatar,
  BLink,
  BBadge,
  BDropdown,
  BDropdownItem,
  BPagination,
} from "bootstrap-vue";
import vSelect from "vue-select";
import store from "@/store";
import { ref, onUnmounted, reactive } from "@vue/composition-api";
import PortfolioListFilters from "./PortfolioListFilters.vue";
import usePortfolioList from "./usePortfolioList";
import portfolioStoreModule from "../portfolioStoreModule";
// Notification
import { useToast } from "vue-toastification/composition";
import ToastificationContent from "@core/components/toastification/ToastificationContent.vue";

export default {
  components: {
    PortfolioListFilters,

    BCard,
    BRow,
    BCol,
    BFormInput,
    BButton,
    BTable,
    BMedia,
    BAvatar,
    BLink,
    BBadge,
    BDropdown,
    BDropdownItem,
    BPagination,

    vSelect,
    ToastificationContent,
  },
  setup() {
    const toast = useToast();
    const PORTFOLIO_APP_STORE_MODULE_NAME = "app-portfolio";

    // Register module
    if (!store.hasModule(PORTFOLIO_APP_STORE_MODULE_NAME))
      store.registerModule(
        PORTFOLIO_APP_STORE_MODULE_NAME,
        portfolioStoreModule
      );

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(PORTFOLIO_APP_STORE_MODULE_NAME))
        store.unregisterModule(PORTFOLIO_APP_STORE_MODULE_NAME);
    });

    // var categoryOptions = [
    //   { label: 'Customer', value: '51' },
    //   { label: 'Press/Media', value: '52' },
    //   { label: 'Investor relations', value: '53' },
    //   { label: 'Charity', value: '54' },
    //   { label: 'Pledge', value: '55' },
    //   { label: 'Private equity', value: '56' },
    //   { label: 'SPAC', value: '57' },
    //   { label: 'SPV', value: '58' },
    //   { label: 'DAOs', value: '59' },
    //   { label: 'Carbon Credits', value: '60' },
    //   { label: 'Blockchain', value: '61' },
    //   { label: 'ICO', value: '62' },
    //   { label: 'Governance', value: '63' },
    // ];

    // const solutionOptions = [
    //   { label: 'All', value: null },
    //   { label: 'Active', value: '1' },
    //   { label: 'Inactive', value: '0' },
    // ]

    let userOptions = ref([]);
    let categoryOptions = ref([]);
    let solutionOptions = ref([]);

    store
      .dispatch("app-portfolio/fetchPortfolioFilters")
      .then((response) => {
        userOptions.value = response.data.data.users || [];
        categoryOptions.value = response.data.data.categories || [];
        solutionOptions.value = response.data.data.solutions || [];
      })
      .catch((error) => {
        userOptions.value = [];
        categoryOptions.value = [];
        solutionOptions.value = [];
      });

    const downloadCSV = () => {
      store
        .dispatch("app-portfolio/fetchPortfolios", {
          q: searchQuery.value,
          length: perPage.value,
          page: currentPage.value,
          sortBy: sortBy.value,
          dir: isSortDirDesc.value ? "desc" : "asc",
          user_id: userFilter.value,
          category_id: categoryFilter.value,
          solution_id: solutionFilter.value,
          csv: true,
        })
        .then((response) => {
          const csvdata = response.data.data || [];
          let csv = "Name,Email,Phone,Category,Solution\n";
          csvdata.forEach((row) => {
            csv += [
              row.user.name,
              row.user.email,
              row.user.country_code + "-" + row.user.contact_no,
              row.category.name,
              row.solution.name,
            ].join(",");
            csv += "\n";
          });

          const exportedFilenmae = "Portfolio.csv" || "export.csv"; // eslint-disable-line
          const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
          if (navigator.msSaveBlob) {
            // IE 10+
            navigator.msSaveBlob(blob, exportedFilenmae);
          } else {
            const link = document.createElement("a");
            if (link.download !== undefined) {
              const url = URL.createObjectURL(blob);
              link.setAttribute("href", url);
              link.setAttribute("download", exportedFilenmae);
              link.style.visibility = "hidden";
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
            }
          }
        })
        .catch((err) => {
          toast({
            component: ToastificationContent,
            props: {
              title: "Error fetching portfolios list",
              icon: "AlertTriangleIcon",
              variant: "danger",
            },
          });
        });
    };

    const {
      fetchPortfolios,
      tableColumns,
      totalPortfolios,
      currentPage,
      perPage,
      perPageOptions,
      dataMeta,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refPortfoliosListTable,
      refetchData,

      // Extra Filters
      userFilter,
      categoryFilter,
      solutionFilter,
    } = usePortfolioList();

    return {
      // Sidebar

      fetchPortfolios,
      tableColumns,
      totalPortfolios,
      currentPage,
      perPage,
      perPageOptions,
      dataMeta,
      searchQuery,
      sortBy,
      isSortDirDesc,
      refPortfoliosListTable,
      refetchData,

      userOptions,
      categoryOptions,
      solutionOptions,

      // Extra Filters
      userFilter,
      categoryFilter,
      solutionFilter,
      downloadCSV,
    };
  },
};
</script>

<style lang="scss" scoped>
.per-page-selector {
  width: 90px;
}
</style>

<style lang="scss">
@import "~@resources/scss/vue/libs/vue-select.scss";
</style>
