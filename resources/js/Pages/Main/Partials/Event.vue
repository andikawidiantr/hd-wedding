<script setup>
import moment from "moment";
import { onBeforeUnmount, onMounted, ref } from "vue";
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const eventRef = ref(null);
const countdownRef = ref(null);
const dateLocationRef = ref(null);
const timelineRef = ref(null);
const saveTheDateRef = ref(null);

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

  const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(
    event.title
  )}&dates=${event.start}/${event.end}&details=${encodeURIComponent(
    event.description
  )}&location=${encodeURIComponent(event.location)}&ctz=Asia/Makassar`;

  return url;
};

const countdown = ref({
  days: "00",
  hours: "00",
  minutes: "00",
  seconds: "00",
});

const targetDate = moment("2025-09-29T05:00:00+08:00");
let timer;

const updateCountdown = () => {
  const now = moment();
  const diff = targetDate.diff(now);

  if (diff <= 0) {
    countdown.value = {
      days: "00",
      hours: "00",
      minutes: "00",
      seconds: "00",
    };
    if (timer) clearInterval(timer);
    return;
  }

  const duration = moment.duration(diff);

  countdown.value = {
    days: String(Math.floor(duration.asDays())).padStart(2, "0"),
    hours: String(duration.hours()).padStart(2, "0"),
    minutes: String(duration.minutes()).padStart(2, "0"),
    seconds: String(duration.seconds()).padStart(2, "0"),
  };
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/event.webp");
    updateCountdown();
    timer = setInterval(updateCountdown, 1000);

    // Animasi setelah gambar dimuat
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: eventRef.value,
        start: "top center",
        end: "center center",
        toggleActions: "play none none reverse"
      }
    });

    // Set initial states
    gsap.set(countdownRef.value, {
      y: 50,
      opacity: 0
    });
    gsap.set(dateLocationRef.value, {
      y: 30,
      opacity: 0
    });
    gsap.set(timelineRef.value, {
      x: -30,
      opacity: 0
    });
    gsap.set(saveTheDateRef.value, {
      y: 20,
      opacity: 0,
      scale: 0.9
    });

    // Animasi sequence
    tl.to(countdownRef.value, {
      y: 0,
      opacity: 1,
      duration: 1,
      ease: "power3.out"
    })
    .to(dateLocationRef.value, {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: "power3.out"
    }, "-=0.5")
    .to(timelineRef.value, {
      x: 0,
      opacity: 1,
      duration: 0.8,
      ease: "power3.out"
    }, "-=0.5")
    .to(saveTheDateRef.value, {
      y: 0,
      opacity: 1,
      scale: 1,
      duration: 0.8,
      ease: "back.out(1.7)"
    }, "-=0.5");

    // Parallax effect pada background
    gsap.to(eventRef.value, {
      backgroundPosition: "50% 30%",
      ease: "none",
      scrollTrigger: {
        trigger: eventRef.value,
        start: "top top",
        end: "bottom top",
        scrub: true
      }
    });

  } catch (error) {
    console.error("Error loading image:", error);
  }
});

onBeforeUnmount(() => {
  if (timer) clearInterval(timer);
});
</script>

<template>
  <section
    ref="eventRef"
    id="event"
    class="min-h-screen flex items-start justify-center relative bg-cover bg-center bg-gray-200 z-[2]"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/event.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
  >
    <div class="w-full h-screen flex items-center justify-center z-[2] px-4 py-12">
      <div class="flex flex-col gap-12 p-4">
        <div ref="countdownRef" class="flex gap-4 justify-center items-center">
          <div class="flex flex-col items-center">
            <span class="font-poly text-4xl text-white countdown-number">{{ countdown.days }}</span>
            <span class="font-wittgenstein text-sm text-white">Hari</span>
          </div>
          <span class="font-poly text-4xl text-white">:</span>
          <div class="flex flex-col items-center">
            <span class="font-poly text-4xl text-white countdown-number">{{ countdown.hours }}</span>
            <span class="font-wittgenstein text-sm text-white">Jam</span>
          </div>
          <span class="font-poly text-4xl text-white">:</span>
          <div class="flex flex-col items-center">
            <span class="font-poly text-4xl text-white countdown-number">{{ countdown.minutes }}</span>
            <span class="font-wittgenstein text-sm text-white">Menit</span>
          </div>
          <span class="font-poly text-4xl text-white">:</span>
          <div class="flex flex-col items-center">
            <span class="font-poly text-4xl text-white countdown-number">{{ countdown.seconds }}</span>
            <span class="font-wittgenstein text-sm text-white">Detik</span>
          </div>
        </div>

        <div ref="dateLocationRef" class="flex flex-col gap-0">
          <h2 class="font-poly text-center text-white text-3xl">
            Senin, 29 September 2025
          </h2>
          <span class="font-poly text-white text-center">Delod Sema Village, Tegalalang, Taro</span>
        </div>

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

        <div ref="saveTheDateRef" class="flex items-center justify-center">
          <a
            :href="createGoogleCalendarLink()"
            target="_blank"
            class="save-date-button border border-white rounded-xl px-6 py-1 text-white uppercase transition-all duration-300 hover:bg-white hover:text-black"
          >
            Save The Date
          </a>
        </div>
      </div>
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-black/70"></div>
  </section>
</template>

<style scoped>
.countdown-number {
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.save-date-button {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.save-date-button:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.save-date-button:active {
  transform: translateY(0);
}
</style>