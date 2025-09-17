<script setup>
import { onMounted, onUnmounted, ref, watch } from "vue";
import gsap from "gsap";
import ScrollToPlugin from "gsap/ScrollToPlugin";
import { show } from "../Utils/index";

gsap.registerPlugin(ScrollToPlugin);

const sectionRef = ref(null);
const currentImageIndex = ref(0);
const imagesLoaded = ref(0);
const isLoading = ref(true);
const loadingError = ref(false);
const animationTriggered = ref(false);
let observer = null;
let slideInterval = null;

const imageUrls = [
  "/assets/images/main.webp",
  "/assets/images/main-2.webp",
  "/assets/images/main-4.webp",
];

// Preload gambar dengan strategi progressive loading
const preloadImages = async () => {
  try {
    const loadImage = (url) => {
      return new Promise((resolve, reject) => {
        const img = new Image();

        img.onload = () => {
          imagesLoaded.value++;
          resolve(img);
        };

        img.onerror = () => {
          console.error(`Failed to load image: ${url}`);
          reject(new Error(`Failed to load image: ${url}`));
        };

        // Cache busting untuk development
        const cacheBuster =
          process.env.NODE_ENV === "development" ? `?t=${Date.now()}` : "";
        img.src = url + cacheBuster;
      });
    };

    // Load gambar pertama dengan prioritas
    await loadImage(imageUrls[0]);
    initializeBackgrounds(); // Mulai dengan gambar pertama

    // Load gambar sisanya
    const remainingImages = imageUrls.slice(1);
    await Promise.all(remainingImages.map((url) => loadImage(url)));

    isLoading.value = false;
  } catch (error) {
    console.error("Image loading failed:", error);
    loadingError.value = true;
    isLoading.value = false;
  }
};

// Inisialisasi backgrounds dengan optimasi
const initializeBackgrounds = () => {
  if (!sectionRef.value) return;

  const slideContainer = document.createElement("div");
  slideContainer.className = "absolute inset-0 overflow-hidden";

  imageUrls.forEach((url, index) => {
    const slide = document.createElement("div");
    slide.className = "slide absolute inset-0 bg-cover bg-center transform-gpu";
    slide.style.backgroundImage = `url(${url})`;
    slide.style.opacity = index === 0 ? "1" : "0";

    // Optimasi untuk mobile
    if (window.innerWidth <= 768) {
      slide.style.transform = "translateZ(0)";
      slide.style.willChange = "opacity";
    }

    slideContainer.appendChild(slide);
  });

  sectionRef.value.insertBefore(slideContainer, sectionRef.value.firstChild);
  startSlideshow();
};

// Fungsi untuk slideshow dengan optimasi
const startSlideshow = () => {
  if (slideInterval) clearInterval(slideInterval);

  slideInterval = setInterval(() => {
    const nextIndex = (currentImageIndex.value + 1) % imageUrls.length;
    transitionToNextImage(nextIndex);
  }, 5000);
};

// Fungsi transisi dengan GSAP
const transitionToNextImage = (nextIndex) => {
  const slides = sectionRef.value.querySelectorAll(".slide");

  gsap.to(slides[currentImageIndex.value], {
    opacity: 0,
    duration: 1.5,
    ease: "power2.inOut",
  });

  gsap.to(slides[nextIndex], {
    opacity: 1,
    duration: 1.5,
    ease: "power2.inOut",
  });

  currentImageIndex.value = nextIndex;
};

// Initial state setup
const setInitialState = () => {
  gsap.set([".title", ".subtitle", ".date"], {
    opacity: 0,
    y: 50,
  });

  gsap.set(".divider", {
    opacity: 0,
    scaleY: 0,
  });
};

// Show background only animation
const showBackgroundOnly = () => {
  const tl = gsap.timeline();

  tl.to([".subtitle", ".title", ".date", ".divider"], {
    opacity: 0,
    y: 20,
    duration: 0.3,
    ease: "power2.in",
  }).set(".divider", {
    scaleY: 0,
    y: 0,
    transformOrigin: "top",
  });
};

// Start animation sequence
const startAnimation = () => {
  if (animationTriggered.value) return;
  animationTriggered.value = true;

  const tl = gsap.timeline({
    defaults: {
      duration: 0.6,
      ease: "power2.out",
    },
  });

  gsap.set([".subtitle", ".title", ".date"], {
    opacity: 0,
    y: 20,
  });

  gsap.set(".divider", {
    opacity: 0,
    scaleY: 0,
    transformOrigin: "top",
  });

  tl.to(".subtitle", {
    opacity: 1,
    y: 0,
    duration: 0.5,
  })
    .to(
      ".title",
      {
        opacity: 1,
        y: 0,
        duration: 0.5,
      },
      "-=0.3"
    )
    .to(
      ".divider",
      {
        opacity: 1,
        scaleY: 1,
        duration: 0.4,
        ease: "power1.out",
      },
      "-=0.2"
    )
    .to(
      ".date",
      {
        opacity: 1,
        y: 0,
        duration: 0.5,
      },
      "-=0.2"
    );
};

// Watch for show changes
watch(show, (newValue) => {
  if (newValue) {
    animationTriggered.value = false;
    showBackgroundOnly();
  } else {
    setTimeout(() => {
      startAnimation();
    }, 100);
  }
});

// Intersection Observer setup
const setupIntersectionObserver = () => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          if (show.value) {
            showBackgroundOnly();
          } else {
            startAnimation();
          }
        }
      });
    },
    {
      threshold: 0.1,
      rootMargin: "0px",
    }
  );

  if (sectionRef.value) {
    observer.observe(sectionRef.value);
  }
};

// Lifecycle hooks
onMounted(async () => {
  setInitialState();
  setupIntersectionObserver();
  await preloadImages();

  if (show.value) {
    showBackgroundOnly();
  }

  // Handle page visibility
  document.addEventListener("visibilitychange", () => {
    if (document.visibilityState === "visible") {
      startSlideshow();
    } else {
      if (slideInterval) clearInterval(slideInterval);
    }
  });
});

onUnmounted(() => {
  if (observer) {
    observer.disconnect();
  }
  if (slideInterval) {
    clearInterval(slideInterval);
  }
  document.removeEventListener("visibilitychange", () => {});
});
</script>

<template>
  <section
    ref="sectionRef"
    id="main"
    class="min-h-screen flex items-start justify-center relative bg-[#4D4D4D] overflow-hidden"
    tabindex="0"
    @focus="!show && startAnimation()"
  >
    <!-- Loading Screen -->
    <div
      v-if="isLoading"
      class="fixed inset-0 bg-black flex items-center justify-center z-[101]"
    >
      <div class="relative flex flex-col justify-center items-center">
        <img
          src="/assets/images/spinner.gif"
          alt="Loading"
          class="w-24 h-24 object-contain"
        />
        <!-- Percentage Text -->
        <div class="mt-2">
          <span class="text-white text-center font-eyesome text-xl">
            {{ Math.round((imagesLoaded / imageUrls.length) * 100) }}%
          </span>
          <p class="text-white font-eyesome animate-pulse text-xl">Loading...</p>
        </div>
      </div>
    </div>

    <!-- Error Screen -->
    <div
      v-if="loadingError"
      class="fixed inset-0 flex items-center justify-center bg-[#4D4D4D] z-[100]"
    >
      <div class="text-center text-white">
        <p class="mb-4 font-eyesome">Failed to load images</p>
        <button
          @click="preloadImages"
          class="px-4 py-2 border border-white rounded hover:bg-white hover:text-black transition-colors"
        >
          Retry
        </button>
      </div>
    </div>

    <!-- Background Gradient -->
    <div
      class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/50 to-black/70"
    ></div>

    <!-- Main Content -->
    <div
      class="flex flex-col gap-4 justify-center items-center relative z-10 pt-20 px-4"
      :style="{ visibility: isLoading ? 'hidden' : 'visible' }"
    >
      <h1
        class="subtitle font-eyesome text-7xl text-white opacity-0 text-center transform-gpu"
        :class="{ 'blur-sm': show }"
      >
        Wedding Day
      </h1>

      <h6
        class="title font-eyesome text-white text-3xl tracking-wide opacity-0 text-center transform-gpu"
        :class="{ 'blur-sm': show }"
      >
        Dharma & Astri
      </h6>

      <div class="divider opacity-0 transform-gpu" :class="{ 'blur-sm': show }">
        <div class="w-[1px] bg-white h-full transform-gpu"></div>
      </div>

      <p
        class="date font-eyesome text-white text-center text-3xl opacity-0 transform-gpu"
        :class="{ 'blur-sm': show }"
      >
        29.09.2025
      </p>
    </div>

    <!-- Top Gradient -->
    <div
      class="absolute -top-20 left-0 right-0 h-20 pointer-events-none"
      style="background: linear-gradient(to bottom, transparent, rgba(0, 0, 0, 0.1))"
    ></div>
  </section>
</template>

<style scoped>
.title,
.subtitle,
.divider,
.date {
  will-change: transform, opacity;
  transition: all 0.3s ease-out;
  transform-style: preserve-3d;
  backface-visibility: hidden;
}

.divider {
  height: 50px;
  width: 1px;
  position: relative;
  margin: 0.5rem 0;
  transform-origin: top;
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

/* Slide Transitions */
.slide {
  will-change: opacity;
  transition: opacity 1.5s ease-in-out;
  transform: translateZ(0);
  backface-visibility: hidden;
}

/* Ken Burns Effect - Desktop Only */
@media (min-width: 769px) {
  @keyframes kenBurns {
    0% {
      transform: scale(1);
    }
    100% {
      transform: scale(1.1);
    }
  }

  .slide {
    animation: kenBurns 20s infinite alternate;
  }
}

/* Mobile Optimizations */
@media (max-width: 768px) {
  .divider {
    height: 35px;
  }

  .slide {
    animation: none !important;
    transform: translateZ(0);
    -webkit-transform: translateZ(0);
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
    perspective: 1000;
    -webkit-perspective: 1000;
  }

  /* Disable hover effects on mobile */
  .title:hover,
  .subtitle:hover,
  .date:hover {
    text-shadow: none;
  }

  /* Optimize transitions */
  .transform-gpu {
    transform: translateZ(0);
    transition: opacity 0.3s ease;
  }
}

/* Hardware Acceleration */
.transform-gpu {
  transform: translateZ(0);
  backface-visibility: hidden;
}

/* Prevent FOIT */
@font-face {
  font-display: swap;
}

/* Blur Transitions */
.blur-sm {
  filter: blur(3px);
  transition: filter 0.3s ease;
}

/* Fade Animations */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
