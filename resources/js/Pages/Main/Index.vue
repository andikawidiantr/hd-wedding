<!-- index.vue -->
<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { show, setShow, fetchData } from "./Utils/index";
import { onMounted, ref, watch } from "vue";
import Popup from "./Partials/Popup.vue";
import Main from "./Partials/Main.vue";
import RegVeda from "./Partials/RegVeda.vue";
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';
import GroomMen from "./Partials/GroomMen.vue";
import GroomWomen from "./Partials/GroomWomen.vue";
import Event from "./Partials/Event.vue";
import Map from "./Partials/Map.vue";
import Gallery from "./Partials/Gallery.vue";
import Greeting from "./Partials/Greeting.vue";
import Gift from "./Partials/Gift.vue";
import Closed from "./Partials/Closed.vue";
import Footer from "./Partials/Footer.vue";

gsap.registerPlugin(ScrollTrigger);

const props = defineProps({
  guest: String,
  greeting:Object,
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
});
</script>

<template>
  <GuestLayout>
    <!-- Popup -->
    <Popup 
      v-if="show" 
      :show="show" 
      @close="() => setShow(false)" 
      :guest="guest"
    />

    <!-- Main Content -->
    <div 
      class="relative w-full max-w-md mx-auto"
     
    >
      <Main />
      <RegVeda />
      <GroomMen/>
      <GroomWomen/>
      <Event/>
      <Map/>
      <Gallery/>
      <Greeting :greeting="greeting"/>
      <Gift/>
      <Closed/>
      <Footer/>
    </div>
  </GuestLayout>
</template>

<style>
body {
  margin: 0;
  padding: 0;
}

.invisible {
  visibility: hidden;
}
</style>