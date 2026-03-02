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

// Preload gambar
// const preloadImage = () => {
//   return new Promise((resolve) => {
//     const img = new Image();
//     img.src = '/assets/images/opening.webp';

//     if (img.complete) {
//       // imageLoaded.value = true;
//       resolve();
//     } else {
//       img.onload = () => {
//         // imageLoaded.value = true;
//         resolve();
//       };
//       img.onerror = () => {
//         // imageLoaded.value = true;
//         resolve();
//       };
//     }
//   });
// };

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
      <div class="text-center flex flex-col items-center gap-6">
        <!-- GIF Loading Animation -->
        <div class="relative flex flex-col items-center">
          <img
            src="/assets/images/spinner.gif"
            alt="Loading"
            class="w-24 h-24 object-contain"
          />
          <!-- Percentage Text -->
          <div class="mt-2">
            <span class="text-white font-eyesome text-xl">
              {{ Math.min(loadingProgress, 100) }}%
            </span>
            <p class="text-white font-eyesome animate-pulse text-xl">Loading...</p>
          </div>
        </div>
      </div>
    </div>
    <!-- Main Content -->
    <div
      v-show="imageLoaded"
      ref="containerRef"
      class="container relative max-w-md w-full h-screen"
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
        class="relative h-full w-full flex flex-col justify-between items-center py-16"
      >
        <div class="p-6 text-white flex flex-col gap-12">
          <!-- Title -->
          <h1
            ref="titleRef"
            class="title font-eyesome text-7xl tracking-wide text-center"
          >
            WEDDING <br />DAY
          </h1>

          <!-- Names -->
          <div
            ref="namesRef"
            class="names flex items-center justify-center flex-col gap-4"
          >
            <h2 class="font-eyesome text-2xl tracking-wider font-light">
              Hendra & Dinda
            </h2>
          </div>
        </div>

        <div class="p-6 flex flex-col gap-8 items-center">
          <!-- Guest Info -->
          <div ref="guestInfoRef" class="guest-info flex flex-col gap-4">
            <h3 class="font-eyesome text-white text-center text-2xl">Untuk</h3>
            <div class="flex flex-col">
              <p class="text-white text-center font-eyesome font-semibold">
                Bapak/Ibu/Saudara/i
              </p>
              <p class="text-white text-center text-xl font-eyesome font-bold">
                {{ guest }}
              </p>
            </div>
          </div>

          <!-- Button -->
          <button
            ref="buttonRef"
            type="button"
            class="button-open flex items-center border rounded-xl px-12 py-2 text-white border-white gap-2 hover:bg-white hover:text-black transition-colors duration-300 font-eyesome font-bold"
            @click="close"
          >
            <mdicon name="email-heart-outline" />
            Buka Undangan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  transform: translateY(100%);
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
</style>
