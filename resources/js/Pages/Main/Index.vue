<script setup>
import { useI18n } from 'vue-i18n';
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
import Event2 from "./Partials/Event2.vue";
import Gallery from "./Partials/Gallery.vue";
import Greeting from "./Partials/Greeting.vue";
import Gift from "./Partials/Gift.vue";
import Story from "./Partials/Story.vue";
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
  code: String,
  locale: String,
  translations: Object
});

// Setup i18n
const { t, locale } = useI18n();

// Fungsi untuk mengubah bahasa
const changeLanguage = (newLocale) => {
  locale.value = newLocale;
  localStorage.setItem('locale', newLocale);
};

const play = ref(false);
const setPlay = (value) => {
  play.value = value;
};

// Video background references and state
const videoBackground = ref(null);
const videoLoaded = ref(false);
const videoError = ref(false);

// Video source - update with your actual path
const videoSrc = "/assets/videos/background.web.mp4"; 
const fallbackImage = "/assets/images/raw-1208.jpg"; //ganti

// Function to handle video loading
const handleVideoLoad = () => {
  videoLoaded.value = true;
};

// Function to handle video error
const handleVideoError = () => {
  videoError.value = true;
  console.error("Failed to load video");
};

// Function to control video playback
const controlVideo = (shouldPlay) => {
  if (videoBackground.value) {
    if (shouldPlay && videoBackground.value.paused) {
      videoBackground.value.play().catch(error => {
        console.error("Video playback failed:", error);
        videoError.value = true;
      });
    } else if (!shouldPlay && !videoBackground.value.paused) {
      videoBackground.value.pause();
    }
  }
};

// Prevent scroll when popup is active and control video
watch(show, (newValue) => {
  if (newValue) {
    document.body.style.overflow = "hidden";
    play.value = true;
    controlVideo(false); // Pause video when popup is shown
  } else {
    document.body.style.overflow = "";
    controlVideo(true); // Play video when popup is hidden
  }
});

onMounted(() => {
  fetchData();
   
  const savedLocale = localStorage.getItem('locale');
  const initialLocale = savedLocale || props.locale || 'id';
  changeLanguage(initialLocale);
 
  // Auto-play video when component mounts (if browser allows)
  if (!show.value) {
    setTimeout(() => {
      controlVideo(true);
    }, 500); // Small delay to ensure DOM is ready
  }
});
</script>

<template>
  <Wallet :show="amplop" @close="() => setAmplop(false)" />
  <GuestLayout>
    <!-- Language Switcher -->
    <div class="language-switcher fixed top-4 right-4 z-50 bg-black/50 rounded-full p-2">
      <button 
        @click="changeLanguage('en')" 
        :class="{ 'font-bold': locale === 'en' }"
        class="px-2 text-white hover:text-gold-500 transition"
      >
        EN
      </button>
      <span class="text-white">|</span>
      <button 
        @click="changeLanguage('id')" 
        :class="{ 'font-bold': locale === 'id' }"
        class="px-2 text-white hover:text-gold-500 transition"
      >
        ID
      </button>
    </div>

    <!-- Popup opening -->
    <Popup v-if="show" :show="show" @close="() => setShow(false)" :guest="guest" @music="setPlay" />
    <div  
      class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/50 to-black/70 z-1"  
    ></div> 
    <!-- Video Background Container -->
    <div class="video-wrapper">
      <video 
        ref="videoBackground"
        class="video-bg"
        autoplay
        muted
        loop
        playsinline
        @canplay="handleVideoLoad"
        @error="handleVideoError"
      >
        <source :src="videoSrc" type="video/mp4">
        <!-- Fallback content -->
        <img :src="fallbackImage" alt="Wedding background">
      </video>
      
      <!-- Fallback image if video fails -->
      <div 
        v-if="videoError"
        class="fallback-bg"
        :style="{ backgroundImage: `url(${fallbackImage})` }"
      ></div>
      
      <!-- Loading indicator -->
      <div v-if="!videoLoaded && !videoError" class="loading-indicator">
        <div class="loading-content">
          <img
            src="/assets/images/spinner.gif"
            alt="Loading"
            class="w-24 h-24 object-contain"
          />
          <p class="text-white font-eyesome animate-pulse text-xl mt-2">
            {{ t('loading') || 'Loading video...' }}
          </p>
        </div>
      </div>
    </div>
  
    <!-- Main Content -->
    <div class="relative w-full max-w-md mx-auto">
      <Main />
      <RegVeda />
      <GroomMen />
      <GroomWomen />
      <!-- <Countdown /> -->
      <Countdown :code="code"/>
      <Event2 :code="code"/>
      <Gallery />
      <Story />
      <Reservation />
      <Greeting :greeting="greeting" />
      <div class="mt-12 sm:mt-16">
        <Gift />
      </div>
      <Closed />
      <Footer />
      <Music :play="play" @play="setPlay" />
    </div>
  </GuestLayout>
</template>

<style>
/* CSS tetap sama seperti sebelumnya */
body {
  margin: 0;
  padding: 0;
}

.invisible {
  visibility: hidden;
}

/* Video background styles */
.video-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: -1;
  overflow: hidden;
  pointer-events: none;
}

.video-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  object-position: center center;
}

@media (min-width: 1px) {
  .video-bg {
    min-width: 100%;
    min-height: 100%;
    width: 100%;
    height: 100%;
  }
}

.fallback-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: center;
}

.loading-indicator {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 0.8);
  z-index: 100;
}

.loading-content {
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* Responsive video handling */
@media (max-aspect-ratio: 16/9) {
  .video-bg {
    width: 100%;
    height: auto;
  }
}

@media (min-aspect-ratio: 16/9) {
  .video-bg {
    width: auto;
    height: 100%;
  }
}

/* Optimize for mobile */
@media (max-width: 768px) {
  .video-bg, .fallback-bg {
    transform: translateZ(0);
    will-change: transform;
  }
}

/* Language switcher styling */
.language-switcher {
  transition: all 0.3s ease;
}

.language-switcher button {
  transition: all 0.3s ease;
}

.language-switcher button.font-bold {
  color: #d4af37; /* Gold color for active language */
}
</style>
