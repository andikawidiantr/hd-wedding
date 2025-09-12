<!-- index.vue -->
<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import { show, setShow, fetchData, amplop, setAmplop } from "./Utils/index";
import { onMounted, ref, watch } from "vue";
import Popup from "./Partials/Popup.vue";
import Main from "./Partials/Main.vue";
import RegVeda from "./Partials/RegVeda.vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import GroomMen from "./Partials/GroomMen.vue";
import GroomWomen from "./Partials/GroomWomen.vue";
import Event from "./Partials/Event.vue";
import Map from "./Partials/Map.vue";
import Gallery from "./Partials/Gallery.vue";
import Greeting from "./Partials/Greeting.vue";
import Gift from "./Partials/Gift.vue";
import Closed from "./Partials/Closed.vue";
import Footer from "./Partials/Footer.vue";
import Countdown from "./Partials/Countdown.vue";
import Reservation from "./Partials/Reservation.vue";
import Wallet from "./Components/Wallet.vue";
import Music from "./Components/Music.vue";

gsap.registerPlugin(ScrollTrigger);

const props = defineProps({
  guest: String,
  greeting: Object,
});

const play = ref(false);
const setPlay = (value) => {
  play.value = value;
};
// Prevent scroll when popup is active
watch(show, (newValue) => {
  if (newValue) {
    document.body.style.overflow = "hidden";
    play.value = true;
  } else {
    document.body.style.overflow = "";
  }
});

onMounted(() => {
  fetchData();
});
</script>

<template>
  <Wallet :show="amplop" @close="() => setAmplop(false)" />
  <GuestLayout>
    <!-- Popup -->
    <Popup v-if="show" :show="show" @close="() => setShow(false)" :guest="guest" @music="setPlay" />

    <!-- Main Content -->
    <div class="relative w-full max-w-md mx-auto">
      <Main />
      <RegVeda />
      <GroomMen />
      <GroomWomen />
      <Countdown />
      <Event />
      <!-- <Map/> -->
      <Gallery />
      <Reservation />
      <Greeting :greeting="greeting" />
      <Closed />
      <Footer />
      <Music :play="play" @play="setPlay" />
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
