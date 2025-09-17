<script setup>
import moment from "moment";
import { onBeforeUnmount, onMounted, ref } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const videoLoaded = ref(false);
const thumbnailLoaded = ref(false);
const eventRef = ref(null);
const countdownRef = ref(null);
const videoRef = ref(null);
const isInViewport = ref(false);

const countdown = ref({
  days: "00",
  hours: "00",
  minutes: "00",
  seconds: "00",
});

const targetDate = moment("2025-09-29T05:00:00+08:00");
let timer;
let observer;

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

const preloadThumbnail = (url) => {
  return new Promise((resolve) => {
    const img = new Image();
    img.onload = () => {
      thumbnailLoaded.value = true;
      resolve();
    };
    img.src = url;
  });
};

const handleVideoLoad = () => {
  videoLoaded.value = true;
  // Video akan diplay hanya jika dalam viewport
  if (isInViewport.value) {
    playVideo();
  }
};

const playVideo = async () => {
  if (videoRef.value) {
    try {
      videoRef.value.defaultMuted = true;
      videoRef.value.muted = true;
      videoRef.value.volume = 0;
      await videoRef.value.play();
      console.log("Video playing");
    } catch (error) {
      console.error("Video play failed:", error);
    }
  }
};

const pauseVideo = () => {
  if (videoRef.value) {
    videoRef.value.pause();
    console.log("Video paused");
  }
};

// Intersection Observer callback
const handleIntersection = (entries) => {
  entries.forEach((entry) => {
    isInViewport.value = entry.isIntersecting;

    if (entry.isIntersecting) {
      if (videoLoaded.value) {
        playVideo();
      }
    } else {
      pauseVideo();
    }
  });
};

onMounted(async () => {
  try {
    await preloadThumbnail("/assets/images/thumbnail.webp");
    updateCountdown();
    timer = setInterval(updateCountdown, 1000);

    // Setup Intersection Observer
    observer = new IntersectionObserver(handleIntersection, {
      threshold: 0.3, // Trigger ketika 30% section terlihat
    });

    if (eventRef.value) {
      observer.observe(eventRef.value);
    }

    // GSAP animation
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: eventRef.value,
        start: "top center",
        end: "center center",
        toggleActions: "play none none reverse",
      },
    });

    gsap.set(countdownRef.value, {
      y: 50,
      opacity: 0,
    });

    tl.to(countdownRef.value, {
      y: 0,
      opacity: 1,
      duration: 1,
      ease: "power3.out",
    });
  } catch (error) {
    console.error("Error:", error);
  }
});

onBeforeUnmount(() => {
  if (timer) clearInterval(timer);
  if (observer) observer.disconnect();
  pauseVideo();
});
</script>
<template>
  <section
    ref="eventRef"
    id="event"
    class="min-h-screen flex items-start justify-center relative z-[2] overflow-hidden"
  >
    <!-- Thumbnail loading state -->
    <div
      v-if="!videoLoaded && thumbnailLoaded"
      class="absolute inset-0 bg-cover bg-center"
      :style="{
        backgroundImage: `url(/assets/images/thumbnail.webp)`,
      }"
    ></div>

    <!-- Video background dengan multiple sources -->
    <video
      ref="videoRef"
      class="absolute w-full h-full object-cover"
      :class="{ 'opacity-0': !videoLoaded }"
      muted
      loop
      autoplay
      playsinline
      preload="auto"
      crossorigin="anonymous"
      @loadeddata="handleVideoLoad"
    >
      <source src="/assets/images/video.mp4" type="video/mp4" />
      <source src="/assets/images/video.webm" type="video/webm" />
      <!-- Pesan fallback jika video tidak dapat diputar -->
      Your browser does not support the video tag.
    </video>

    <!-- Overlay untuk meningkatkan keterbacaan teks -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- Konten countdown -->
    <div class="w-full h-screen flex items-center justify-center z-[2] px-4 py-12">
      <div class="flex flex-col gap-12 p-4">
        <div ref="countdownRef" class="flex gap-4 justify-center items-center">
          <!-- Days -->
          <div
            class="flex flex-col items-center bg-white/20 p-3 lg:p-4 rounded-lg backdrop-blur-sm transition-all duration-300 ease-in-out hover:bg-white/30 hover:scale-105"
          >
            <span class="font-poly text-2xl lg:text-3xl text-white countdown-number">
              {{ countdown.days }}
            </span>
            <span class="font-wittgenstein text-sm md:text-base text-white">Hari</span>
          </div>

          <span class="font-poly text-3xl text-white">:</span>

          <!-- Hours -->
          <div
            class="flex flex-col items-center bg-white/20  p-3 lg:p-4 rounded-lg backdrop-blur-sm transition-all duration-300 ease-in-out hover:bg-white/30 hover:scale-105"
          >
            <span class="font-poly text-2xl lg:text-3xl text-white countdown-number">
              {{ countdown.hours }}
            </span>
            <span class="font-wittgenstein text-sm md:text-base text-white">Jam</span>
          </div>

          <span class="font-poly text-3xl text-white">:</span>

          <!-- Minutes -->
          <div
            class="flex flex-col items-center bg-white/20  p-3 lg:p-4 rounded-lg backdrop-blur-sm transition-all duration-300 ease-in-out hover:bg-white/30 hover:scale-105"
          >
            <span class="font-poly text-2xl lg:text-3xl text-white countdown-number">
              {{ countdown.minutes }}
            </span>
            <span class="font-wittgenstein text-sm md:text-base text-white">Menit</span>
          </div>

          <span class="font-poly text-2xl lg:text-3xl text-white">:</span>

          <!-- Seconds -->
          <div
            class="flex flex-col items-center bg-white/20  p-3 lg:p-4 rounded-lg backdrop-blur-sm transition-all duration-300 ease-in-out hover:bg-white/30 hover:scale-105"
          >
            <span class="font-poly text-2xl lg:text-3xl text-white countdown-number">
              {{ countdown.seconds }}
            </span>
            <span class="font-wittgenstein text-sm md:text-base text-white">Detik</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.opacity-0 {
  opacity: 0;
}

/* Animasi smooth untuk transisi opacity */
video {
  transition: opacity 0.5s ease-in-out;
}

/* Animasi hover untuk countdown boxes */
.countdown-number {
  transition: transform 0.2s ease-in-out;
}

.countdown-number:hover {
  transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .countdown-number {
    font-size: 1.5rem;
  }
}
</style>
