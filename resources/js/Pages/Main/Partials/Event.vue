<script setup>
import moment from "moment";
import { onBeforeUnmount, onMounted, ref } from "vue";
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const eventRef = ref(null);
const dateLocationRef = ref(null);
const timelineRef = ref(null);
const saveTheDateRef = ref(null);
const qrRef = ref(null);
const mapButtonRef = ref(null);

const preloadImage = (url) => {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.src = url;
    img.onload = () => {
      imageLoaded.value = true;
      resolve();
    };
    img.onerror = reject;
  });
};

const createGoogleCalendarLink = () => {
  const event = {
    title: "Pawiwahan & Resepsi Dharma & Astri",
    description:
      "Pawiwahan: 05:00 - 15:00 Wita\nResepsi: 16:00 Wita - Selesai\nLokasi: Delod Sema Village, Tegalalang, Taro",
    location: "Delod Sema Village, Tegalalang, Taro",
    start: "20250929T050000",
    end: "20250929T235900",
  };

  return `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(
    event.title
  )}&dates=${event.start}/${event.end}&details=${encodeURIComponent(
    event.description
  )}&location=${encodeURIComponent(event.location)}&ctz=Asia/Makassar`;
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/event.webp");

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: eventRef.value,
        start: "top 60%",
        end: "center center",
        toggleActions: "play none none reverse",
      }
    });

    // Set initial states
    gsap.set([dateLocationRef.value, timelineRef.value, saveTheDateRef.value, qrRef.value, mapButtonRef.value], {
      opacity: 0,
      y: 30
    });

    gsap.set(timelineRef.value, {
      x: -30
    });

    // Animation sequence
    tl.to(dateLocationRef.value, {
      y: 0,
      opacity: 1,
      duration: 1,
      ease: "power3.out"
    })
    .to(timelineRef.value, {
      x: 0,
      y: 0,
      opacity: 1,
      duration: 1,
      ease: "power3.out"
    }, "-=0.7")
    .to(saveTheDateRef.value, {
      y: 0,
      opacity: 1,
      duration: 1,
      ease: "back.out(1.7)"
    }, "-=0.7")
    .to([qrRef.value, mapButtonRef.value], {
      y: 0,
      opacity: 1,
      duration: 1,
      stagger: 0.2,
      ease: "power3.out"
    }, "-=0.7");

    // Parallax effect
    gsap.to(eventRef.value, {
      backgroundPosition: "50% 30%",
      ease: "none",
      scrollTrigger: {
        trigger: eventRef.value,
        start: "top top",
        end: "bottom top",
        scrub: 1
      }
    });

  } catch (error) {
    console.error("Error loading image:", error);
  }
});
</script>

<template>
  <section
    ref="eventRef"
    id="event"
    class="min-h-screen flex items-start justify-center relative bg-cover bg-center z-[2]"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/event.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
  >
    <div class="w-full h-screen flex items-center justify-center z-[2] px-4 py-12">
      <div class="flex flex-col gap-12 p-4">
        <!-- Date and Location -->
        <div ref="dateLocationRef" class="flex flex-col">
          <h2 class="font-poly text-center text-white text-2xl lg:text-3xl">
            Senin, 29 September 2025
          </h2>
          <span class="font-poly text-white text-center tracking-widest">
            Delod Sema Village, Tegalalang, Taro
          </span>
        </div>

        <!-- Timeline -->
        <div ref="timelineRef" class="flex gap-8">
          <div class="relative">
            <div class="border-r border-white mt-4 h-[135px]"></div>
            <div class="absolute left-[-4px] top-[12px] w-2 h-2 bg-white rounded-full"></div>
            <div class="absolute left-[-4px] top-[105px] w-2 h-2 bg-white rounded-full"></div>
          </div>
          <div class="flex flex-col gap-8">
            <div class="flex flex-col gap-1">
              <h1 class="font-wittgenstein text-left text-white text-2xl font-bold">
                Pawiwahan
              </h1>
              <h6 class="text-white font-wittgenstein">05:00 - 15:00 Wita</h6>
            </div>
            <div class="flex flex-col gap-1">
              <h1 class="font-wittgenstein text-left text-white text-2xl font-bold">
                Resepsi
              </h1>
              <h6 class="text-white font-wittgenstein">16:00 Wita - Selesai</h6>
            </div>
          </div>
        </div>

        <!-- Save the Date Button -->
        <div ref="saveTheDateRef" class="flex items-center justify-center">
          <a
            :href="createGoogleCalendarLink()"
            target="_blank"
            class="save-date-button border border-white rounded-xl px-6 py-1 text-white uppercase"
          >
            Save The Date
          </a>
        </div>

        <!-- QR Code and Map -->
        <div class="flex flex-col gap-12 mt-4 lg:mt-16">
          <div ref="qrRef" class="flex items-center justify-center">
            <div class="bg-white p-2 rounded-md shadow-lg qr-container">
              <img src="/assets/images/qr.png" alt="QR Code" class="w-24 h-24" />
            </div>
          </div>

          <div ref="mapButtonRef" class="flex items-center justify-center">
            <a
              href="https://maps.app.goo.gl/7GP44pSQC3d3h8WN8"
              target="_blank"
              class="map-button border border-white rounded-xl px-6 py-1 text-white uppercase"
            >
              <span class="relative z-10">OPEN MAP</span>
              <div class="absolute inset-0 bg-white transform origin-bottom scale-y-0 group-hover:scale-y-100"></div>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Overlay -->
    <div
      class="absolute inset-0 bg-[#4D4D4D]/30"
      :class="{ 'opacity-100': imageLoaded, 'opacity-0': !imageLoaded }"
    ></div>
  </section>
</template>

<style scoped>
.save-date-button {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.save-date-button:hover {
  transform: translateY(-2px);
  background-color: white;
  color: #4D4D4D;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.save-date-button:active {
  transform: translateY(0);
}

.map-button {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
  overflow: hidden;
}

.map-button:hover {
  background-color: white;
  color: #4D4D4D;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.map-button:active {
  transform: translateY(0);
}

.qr-container {
  transition: transform 0.3s ease;
}

.qr-container:hover {
  transform: scale(1.05);
}

section {
  transition: background-image 0.5s ease-in-out;
}

.absolute {
  transition: opacity 0.5s ease-in-out;
}
</style>