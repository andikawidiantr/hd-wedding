<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { show, setShow, fetchData } from "./Utils/index";
import { onMounted, ref, watch } from "vue";
import Popup from "./Partials/Popup.vue";
import Main from "./Partials/Main.vue";
import RegVeda from "./Partials/RegVeda.vue";
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';
import ScrollSmoother from 'gsap/ScrollSmoother';

gsap.registerPlugin(ScrollTrigger, ScrollSmoother);

const props = defineProps({
  guest: String,
});

// Prevent scroll when popup is active
watch(show, (newValue) => {
  if (newValue) {
    document.body.style.overflow = 'hidden';
  } else {
    document.body.style.overflow = '';
  }
});

onMounted(() => {
  fetchData();

  // Create smooth scroller only when popup is closed
  if (!show.value) {
    const smoother = ScrollSmoother.create({
      wrapper: "#smooth-wrapper",
      content: "#smooth-content",
      smooth: 1.5,
      effects: true,
      smoothTouch: 0.1,
    });
  }

  window.addEventListener('resize', () => {
    ScrollTrigger.refresh();
  });
});
</script>

<template>
  <GuestLayout>
    <Popup 
      v-if="show" 
      :show="show" 
      @close="() => setShow(false)" 
      :guest="guest"
    />

    <div 
      id="smooth-wrapper"
      :class="{ 'invisible': show }"
    >
      <div id="smooth-content">
        <main>
          <Main />
          <RegVeda :show="show" />
        </main>
      </div>
    </div>
  </GuestLayout>
</template>

<style>
#smooth-wrapper {
  overflow: hidden;
  position: fixed;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
}

#smooth-content {
  overflow: visible;
  width: 100%;
  transform: translateZ(0);
  will-change: transform;
}

/* Hide scrollbar but keep functionality */
::-webkit-scrollbar {
  width: 0px;
  background: transparent;
}

* {
  -webkit-tap-highlight-color: transparent;
}

/* Add styles for popup state */
.invisible {
  visibility: hidden;
}

body {
  margin: 0;
  padding: 0;
}
</style>