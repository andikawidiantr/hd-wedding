<script setup>
import { onMounted, ref } from "vue";
import gsap from "gsap";

const props = defineProps({
  show: Boolean,
  guest: String,
});

const emit = defineEmits(["close", "music"]);
const imageLoaded = ref(false);
const loadingProgress = ref(0);

// Preload gambar dengan progress
const preloadImage = () => {
  return new Promise((resolve) => {
    const img = new Image();
    img.src = "/assets/images/opening_img.jpg";

    // Simulasi progress loading
    const simulateProgress = () => {
      const interval = setInterval(() => {
        if (loadingProgress.value < 98) {
          loadingProgress.value += Math.floor(Math.random() * 10) + 1;
          if (loadingProgress.value > 98) loadingProgress.value = 98;
        }
      }, 100);

      return interval;
    };

    const interval = simulateProgress();

    if (img.complete) {
      clearInterval(interval);
      loadingProgress.value = 100;
      setTimeout(() => {
        imageLoaded.value = true;
        resolve();
      }, 500);
    } else {
      img.onload = () => {
        clearInterval(interval);
        loadingProgress.value = 100;
        setTimeout(() => {
          imageLoaded.value = true;
          resolve();
        }, 500);
      };
      img.onerror = () => {
        clearInterval(interval);
        loadingProgress.value = 100;
        setTimeout(() => {
          imageLoaded.value = true;
          resolve();
        }, 500);
      };
    }
  });
};
// Refs untuk GSAP
const containerRef = ref(null);
const titleRef = ref(null);
const namesRef = ref(null);
const guestInfoRef = ref(null);
const buttonRef = ref(null);

// Animasi close
const close = () => {
  const tl = gsap.timeline({
    onComplete: () => {
      emit("close");
      emit("music", true);
    },
  });

  tl.to(".button-open", { y: 50, opacity: 0, duration: 0.3 })
    .to(".guest-info", { y: 50, opacity: 0, duration: 0.3 }, "-=0.2")
    .to(".names", { y: 50, opacity: 0, duration: 0.3 }, "-=0.2")
    .to(".title", { y: 50, opacity: 0, duration: 0.3 }, "-=0.2")
    .to(".container", { y: "100%", duration: 0.5 });
};

// Animasi masuk
const startEntryAnimation = () => {
  gsap.set(".container", { y: "100%" });
  gsap.set([".title", ".names", ".guest-info", ".button-open"], {
    y: 50,
    opacity: 0,
  });

  const tl = gsap.timeline();

  tl.to(".container", {
    y: "0%",
    duration: 1,
    ease: "power4.out",
  })
    .to(".title", {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: "back.out",
    })
    .to(
      ".names",
      {
        y: 0,
        opacity: 1,
        duration: 0.8,
        ease: "back.out",
      },
      "-=0.4"
    )
    .to(
      ".guest-info",
      {
        y: 0,
        opacity: 1,
        duration: 0.8,
        ease: "back.out",
      },
      "-=0.4"
    )
    .to(
      ".button-open",
      {
        y: 0,
        opacity: 1,
        duration: 0.8,
        ease: "back.out",
      },
      "-=0.4"
    );
};

onMounted(async () => {
  if (props.show) {
    // Tunggu gambar selesai load
    await preloadImage();
    // Mulai animasi setelah gambar siap
    startEntryAnimation();
  }
});
</script>

<template>
  <div class="fixed inset-0 flex items-center justify-center z-[100] overflow-hidden">
    <!-- Loading Screen -->
    <div
      v-if="!imageLoaded"
      class="fixed inset-0 bg-black flex items-center justify-center z-[101]"
    >
      <div class="text-center flex flex-col items-center gap-4 sm:gap-6">
        <!-- GIF Loading Animation -->
        <div class="relative flex flex-col items-center">
          <img
            src="/assets/images/spinner.gif"
            alt="Loading"
            class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 object-contain"
          />
          <!-- Percentage Text -->
          <div class="mt-2">
            <span class="text-white font-eyesome text-base sm:text-lg md:text-xl">
              {{ Math.min(loadingProgress, 100) }}%
            </span>
            <p class="text-white font-eyesome animate-pulse text-base sm:text-lg md:text-xl">Loading...</p>
          </div>
        </div>
      </div>
    </div>
    <!-- Main Content -->
    <div
      v-show="imageLoaded"
      ref="containerRef"
      class="container relative w-full h-full max-w-md mx-auto"
    >
      <!-- Background dengan optimasi -->
      <div class="absolute inset-0">
        <img
          src="/assets/images/opening_img.jpg"
          alt="Wedding Background"
          class="absolute inset-0 w-full h-full object-cover"
          loading="eager"
          decoding="async"
          fetchpriority="high"
        />
      </div>

      <!-- Overlay -->
      <div class="absolute inset-0 bg-black/50"></div>

      <!-- Content -->
      <div
        class="relative h-full w-full flex flex-col justify-between items-center py-8 sm:py-12 md:py-16"
      >
        <!-- Top Content -->
        <div class="p-4 sm:p-5 md:p-6 text-white flex flex-col gap-6 sm:gap-8 md:gap-12 mt-4 sm:mt-6 md:mt-8">
          <!-- Title -->
          <h1
            ref="titleRef"
            class="title font-fanwood text-4xl sm:text-4xl md:text-5xl tracking-wide text-center mt-16"
          >
            WEDDING <br />DAY
          </h1>

          <!-- Names -->
          <div
            ref="namesRef"
            class="names flex items-center justify-center flex-col gap-2 sm:gap-3 md:gap-4"
          >
            <h2 class="font-playball text-3xl sm:text-3xl md:text-4xl tracking-wider font-light pt-48 sm:pt-2">
              Hendra & Dinda
            </h2>
          </div>
        </div>

        <!-- Bottom Content - Guest Info and Button -->
        <div class="p-4 sm:p-5 md:p-6 flex flex-col gap-6 sm:gap-7 md:gap-8 items-center mb-4 sm:mb-6 md:mb-8">
          <!-- Guest Info -->
          <div ref="guestInfoRef" class="guest-info flex flex-col gap-3 sm:gap-4">
            <h3 class="font-playball text-white text-center text-xl sm:text-xl md:text-2xl">Untuk</h3>
            <div class="flex flex-col">
              <p class="text-white text-center font-playball text-lg sm:text-lg md:text-xl">
                Bapak/Ibu/Saudara/i
              </p>
              <p class="text-white text-center text-2xl sm:text-3xl md:text-4xl font-playball font-bold pt-4 sm:pt-6 md:pt-8">
                {{ guest }}
              </p>
            </div>
          </div>

          <!-- Button -->
          <button
            ref="buttonRef"
            type="button"
            class="button-open flex items-center border rounded-xl px-8 sm:px-10 md:px-12 py-2 text-white border-white gap-1 sm:gap-2 hover:bg-white hover:text-black transition-colors duration-300 mt-2 sm:mt-3 md:mt-4"
            @click="close"
          >
            <mdicon name="email-heart-outline" class="text-base sm:text-lg md:text-xl" />
            <h1 class="font-fanwood tracking-wider font-light text-sm sm:text-base md:text-lg">
              Buka Undangan
            </h1>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  transform: translateY(100%);
  height: 100vh; /* Memastikan container mengisi tinggi layar */
  width: 100%; /* Memastikan container mengisi lebar layar */
  max-width: 100%; /* Pada perangkat kecil, mengisi seluruh lebar */
}

@media (min-width: 640px) {
  .container {
    max-width: 640px; /* Untuk layar yang lebih besar dari sm */
  }
}

@media (min-width: 768px) {
  .container {
    max-width: 768px; /* Untuk layar yang lebih besar dari md */
  }
}

:deep(.fixed) {
  position: fixed;
  z-index: 100;
}

/* Loading Spinner */
.loading-spinner {
  width: 40px;
  height: 40px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-radius: 50%;
  border-top-color: white;
  animation: spin 1s ease-in-out infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Mobile Optimizations */
img {
  -webkit-transform: translateZ(0);
  -webkit-backface-visibility: hidden;
  -webkit-perspective: 1000;
}

/* Button Hover Effect */
.button-open {
  transition: all 0.3s ease;
}

.button-open:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(255, 255, 255, 0.1);
}

/* Safe area inset untuk perangkat dengan notch */
@supports (padding: max(0px)) {
  .container {
    padding-top: max(0px, env(safe-area-inset-top));
    padding-bottom: max(0px, env(safe-area-inset-bottom));
    padding-left: max(0px, env(safe-area-inset-left));
    padding-right: max(0px, env(safe-area-inset-right));
  }
}

/* Penyesuaian untuk perangkat dengan layar sangat kecil */
@media screen and (max-height: 568px) {
  .title {
    font-size: 1.75rem; /* Lebih kecil untuk iPhone SE atau serupa */
  }
  
  .names h2 {
    font-size: 1.5rem;
  }
  
  .guest-info p:last-child {
    font-size: 1.5rem;
    padding-top: 0.5rem;
  }
  
  .button-open {
    padding: 0.375rem 1.5rem;
  }
}

/* Penyesuaian untuk perangkat dengan layar tinggi */
@media screen and (min-height: 800px) {
  .container {
    justify-content: space-between;
  }
}
</style>
