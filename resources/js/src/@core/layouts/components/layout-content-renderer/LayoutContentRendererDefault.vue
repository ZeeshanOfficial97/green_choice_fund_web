<template>
  <div
    class="app-content content"
    :class="[
      { 'show-overlay': $store.state.app.shallShowOverlay },
      $route.meta.contentClass,
    ]"
  >
    <div class="content-overlay" />
    <div class="header-navbar-shadow" />
    <div id="loading-bg-content">
      <!-- <div class="loading-logo">
        <img src="{{ asset('logo.png') }}" alt="Logo" />
      </div> -->
      <div class="loading">
        <div class="effect-1 effects"></div>
        <div class="effect-2 effects"></div>
        <div class="effect-3 effects"></div>
      </div>
    </div>
    <div
      class="content-wrapper"
      :class="contentWidth === 'boxed' ? 'container p-0' : null"
    >
      <slot name="breadcrumb">
        <app-breadcrumb />
      </slot>
      <div class="content-body">
        <transition :name="routerTransition" mode="out-in">
          <slot />
        </transition>
      </div>
    </div>
  </div>
</template>

<script>
import AppBreadcrumb from "@core/layouts/components/AppBreadcrumb.vue";
import useAppConfig from "@core/app-config/useAppConfig";

export default {
  components: {
    AppBreadcrumb,
  },
  setup() {
    const { routerTransition, contentWidth } = useAppConfig();

    return {
      routerTransition,
      contentWidth,
    };
  },
};
</script>

<style>
</style>
