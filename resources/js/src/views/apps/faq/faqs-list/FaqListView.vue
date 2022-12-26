<template>
  <div>
    <div><h2 class="brand-text">Green Choice Funds</h2></div>
    <div v-if="options" class="pl-3 pr-3">
      <!-- collapse -->
      <app-collapse id="faq-payment-qna" accordion type="margin" class="mt-2">
        <app-collapse-item
          v-for="(data, index) in options"
          :key="index"
          :title="data.question"
        >
          {{ data.answer }}
        </app-collapse-item>
      </app-collapse>

      <!--/ collapse -->
    </div>
  </div>
</template>

<script>
import { BAvatar } from "bootstrap-vue";
import AppCollapse from "@core/components/app-collapse/AppCollapse.vue";
import AppCollapseItem from "@core/components/app-collapse/AppCollapseItem.vue";
import faqStoreModule from "../faqStoreModule";
import store from "@/store";
import { ref, onUnmounted } from "@vue/composition-api";

export default {
  components: {
    BAvatar,
    AppCollapseItem,
    AppCollapse,
  },
  // props: {
  //   options: {
  //     type: Object,
  //     default: () => {},
  //   },
  // },
  setup() {
    const FAQ_APP_STORE_MODULE_NAME = "app-faq";

    // Register module
    if (!store.hasModule(FAQ_APP_STORE_MODULE_NAME))
      store.registerModule(FAQ_APP_STORE_MODULE_NAME, faqStoreModule);

    // UnRegister on leave
    onUnmounted(() => {
      if (store.hasModule(FAQ_APP_STORE_MODULE_NAME))
        store.unregisterModule(FAQ_APP_STORE_MODULE_NAME);
    });

    let options = ref([]);

    store
      .dispatch("app-faq/fetchFaqsUser")
      .then((response) => {
        debugger;
        options.value = response.data.data || [];
      })
      .catch((error) => {
        options.value = [];
      });

    return {
      options,
    };
  },
};
</script>

<style>
.brand-text {
  padding-top: 3rem;
  color: #7367f0;
  font-weight: 600;
  letter-spacing: 0.01rem;
  font-size: 1.45rem;
  text-align: center;
}

.card-header:not(.collapsed) {
  background-color: #7367f0;
  color: #fff;
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
}
</style>
