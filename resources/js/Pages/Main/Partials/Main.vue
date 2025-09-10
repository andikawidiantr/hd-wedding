<script setup>
import { onMounted, onUnmounted, ref, watch } from "vue";
import gsap from "gsap";
import ScrollToPlugin from "gsap/ScrollToPlugin";
import { show } from "../Utils/index";

gsap.registerPlugin(ScrollToPlugin);

const sectionRef = ref(null);
const imageLoaded = ref(false);
const animationTriggered = ref(false);
let observer = null;

// Preload image di awal
const imageUrl = "/assets/images/main.webp";
const img = new Image();
img.src = imageUrl;
img.onload = () => {
  imageLoaded.value = true;
  // Set background langsung visible setelah load
  if (sectionRef.value) {
    gsap.set(sectionRef.value, {
      opacity: 1,
      backgroundImage: `url(${imageUrl})`,
    });
  }
};

// Fungsi untuk mengatur initial state
const setInitialState = () => {
  gsap.set(sectionRef.value, {
    opacity: 1,
  });

  gsap.set([".title", ".subtitle", ".date"], {
    opacity: 0,
    y: 50,
  });

  gsap.set(".divider", {
    opacity: 0,
    scaleY: 0,
  });
};

// Animasi ketika show.value = true (hanya background)
const showBackgroundOnly = () => {
  const tl = gsap.timeline();
 
  tl.to([".subtitle", ".title", ".date", ".divider"], {
    opacity: 0,
    y: 20,
    duration: 0.3, // Dipercepat
    ease: "power2.in",
  })
    .set(".divider", {
      scaleY: 0,
      y:0,
      transformOrigin: "top",
    })
    .to(sectionRef.value, {
      opacity: 1,
      duration: 0.5, // Dipercepat
      ease: "power2.out",
    });
};

const startAnimation = () => {
  if (animationTriggered.value) return;
  animationTriggered.value = true;
 
  const tl = gsap.timeline({
    defaults: {
      duration: 0.6, // Dipercepat
      ease: "power2.out",
    },
  });
 
  // Reset posisi awal
  gsap.set([".subtitle", ".title", ".date"], {
    opacity: 0,
    y: 20, // Kurangi jarak
  });
 
  gsap.set(".divider", {
    opacity: 0,
    scaleY: 0,
    transformOrigin: "top",
  });
 
  // Animasi berurutan
  tl.to(".subtitle", {
    opacity: 1,
    y: 0,
    duration: 0.5,
  })
    .to(".title", {
      opacity: 1,
      y: 0,
      duration: 0.5,
    }, "-=0.3")
    .to(".divider", {
      opacity: 1,
      scaleY: 1,
      duration: 0.4,
      ease: "power1.out",
    }, "-=0.2")
    .to(".date", {
      opacity: 1,
      y: 0,
      duration: 0.5,
    }, "-=0.2");
};
 
// Modifikasi watch untuk show
watch(show, (newValue) => {
  if (newValue) {
    animationTriggered.value = false;
    showBackgroundOnly();
  } else {
    // Kurangi delay
    setTimeout(() => {
      startAnimation();
    }, 100); // Dipercepat dari 300 ke 100
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

  observer.observe(sectionRef.value);
};

// Scroll to section function
const scrollToSection = () => {
  gsap.to(window, {
    duration: 1,
    scrollTo: {
      y: "#main",
      offsetY: 0,
    },
    ease: "power2.inOut",
  });
};

onMounted(() => {
  setInitialState();
  setupIntersectionObserver();

  // Initial animation based on show value
  if (show.value) {
    showBackgroundOnly();
  }

  // Event listeners
  window.addEventListener("focus", () => {
    if (!show.value) {
      startAnimation();
    }
  });

  // Optional: Scroll to section on mount
  // scrollToSection();
});

onUnmounted(() => {
  if (observer) {
    observer.disconnect();
  }
  window.removeEventListener("focus", () => {});
});
</script>

<template>
  <section
    ref="sectionRef"
    id="main"
    class="min-h-screen flex items-start justify-center relative bg-cover bg-center bg-[#4D4D4D]"
    :style="{
      backgroundImage: imageLoaded ? `url(${imageUrl})` : 'none',
    }"
    tabindex="0"
    @focus="!show && startAnimation()"
  >
    <!-- Background Overlay -->
    <div
      class="absolute inset-0 bg-[#4D4D4D]/30 transition-opacity duration-500"
      :class="{ 'opacity-50': show, 'opacity-30': !show }"
    ></div>

    <!-- Main Content -->
    <div class="flex flex-col gap-4 justify-center items-center relative z-10 pt-20 px-4">
      <h1
        class="subtitle font-eyesome text-6xl md:text-7xl text-white opacity-0 text-center"
        :class="{ 'blur-sm': show }"
      >
        Wedding Day
      </h1>

      <h6
        class="title font-eyesome text-white text-2xl md:text-3xl tracking-wide opacity-0 text-center"
        :class="{ 'blur-sm': show }"
      >
        Dharma & Astrid
      </h6>

      <div class="divider opacity-0 transform-gpu" :class="{ 'blur-sm': show }">
        <div class="w-[1px] bg-white h-full transform-gpu"></div>
      </div>

      <p
        class="date font-eyesome text-white text-center text-2xl md:text-3xl opacity-0"
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

    <!-- Optional Loading Indicator -->
    <div
      v-if="!imageLoaded"
      class="absolute inset-0 flex items-center justify-center bg-[#4D4D4D] z-20"
    >
      <div class="animate-pulse text-white font-poly"></div>
    </div>
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

/* Styling untuk divider */
.divider {
  height: 50px;
  width: 1px;
  position: relative;
  margin: 0.5rem 0; 
  transform-origin: top;
}

/* Hover effect */
.title:hover,
.subtitle:hover,
.date:hover {
  text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
  transition: text-shadow 0.3s ease;
}

/* Blur transition */
.blur-sm {
  filter: blur(3px); 
  transition: filter 0.3s ease;
}

/* Animation Classes */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
  .divider {
    height: 35px;
  }
}

.transform-gpu {
  transform: translateZ(0);
  backface-visibility: hidden;
}
</style>