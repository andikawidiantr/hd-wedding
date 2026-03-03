<script setup>
import { onMounted, onUnmounted, ref, watch } from "vue";
import gsap from "gsap";
import ScrollToPlugin from "gsap/ScrollToPlugin";
import { show } from "../Utils/index";
import { useI18n } from 'vue-i18n'; // Import useI18n

// Initialize i18n
const { t } = useI18n();

gsap.registerPlugin(ScrollToPlugin);

const sectionRef = ref(null);
const animationTriggered = ref(false);
let observer = null;

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
onMounted(() => {  
  setInitialState();  
  setupIntersectionObserver();  
  
  if (show.value) {  
    showBackgroundOnly();  
  }  
});  

onUnmounted(() => {
  if (observer) {
    observer.disconnect();
  }
});
</script>

<template>
  <section
    ref="sectionRef"
    id="main"
    class="min-h-screen flex items-center justify-center relative overflow-hidden"  
    tabindex="0"
    @focus="!show && startAnimation()"
  >
    <!-- Background Gradient Overlay -->  
    <!-- <div  
      class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/50 to-black/70 z-1"  
    ></div> -->

    <!-- Main Content -->
    <div
      class="flex flex-col gap-4 justify-center items-center relative z-10 w-full"
    >  
      <h1
        class="subtitle font-fanwood text-6xl font-light text-white opacity-0 text-center transform-gpu"
        :class="{ 'blur-sm': show }"
      >
        {{ t('wedding_day', 'Wedding Day') }}
      </h1>

      <h6
        class="title font-playball text-white text-4xl tracking-wide opacity-0 text-center transform-gpu pt-16"
        :class="{ 'blur-sm': show }"
      >
        Hendra & Dinda
      </h6>

      <div class="divider opacity-0 transform-gpu" :class="{ 'blur-sm': show }">
        <div class="w-[1px] bg-white h-full transform-gpu"></div>
      </div>

      <p
        class="date font-playball text-white text-center text-3xl opacity-0 transform-gpu"
        :class="{ 'blur-sm': show }"
      >
        {{ t('wedding_date', '25.03.2026') }}
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

/* Mobile Optimizations */
@media (max-width: 768px) {
  .divider {
    height: 35px;
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
