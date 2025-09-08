<script setup>
import { onMounted, onUnmounted, ref, watch } from "vue";
import gsap from "gsap";
import SplitType from "split-type";
import { show } from "../Utils/index";
import TornPaperBottom from "../Components/TornPaperBottom.vue";

const sectionRef = ref(null);
const imageLoaded = ref(false);
const animationTriggered = ref(false);
let observer = null;

// Fungsi untuk preload image
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

// Animasi masuk
const startAnimation = () => {
  if (animationTriggered.value) return;
  animationTriggered.value = true;

  // Reset posisi awal
  gsap.set([".title", ".subtitle", ".date"], {
    opacity: 0,
    y: 50,
  });

  gsap.set(".divider", {
    scaleY: 0,
    opacity: 0,
  });

  gsap.set(sectionRef.value, {
    opacity: 0,
  });

  // Timeline animasi
  const tl = gsap.timeline({
    defaults: {
      duration: 1,
      ease: "power3.out",
    },
  });

  tl.to(sectionRef.value, {
    opacity: 1,
    duration: 1.5,
  })
    .to(
      ".title",
      {
        opacity: 1,
        y: 0,
        duration: 1,
      },
      "-=1"
    )
    .to(
      ".subtitle",
      {
        opacity: 1,
        y: 0,
        duration: 1,
      },
      "-=0.7"
    )
    .to(
      ".divider",
      {
        opacity: 1,
        scaleY: 1,
        duration: 0.8,
      },
      "-=0.5"
    )
    .to(
      ".date",
      {
        opacity: 1,
        y: 0,
        duration: 1,
      },
      "-=0.5"
    );
};

// Watch untuk show (popup)
watch(show, (newValue) => {
  if (!newValue && imageLoaded.value) {
    // Ketika popup ditutup dan image sudah di-load
    startAnimation();
  }
});

// Intersection Observer untuk mengecek visibility
const setupIntersectionObserver = () => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting && !show.value) {
          // Jalankan animasi ketika section terlihat dan popup sudah tertutup
          startAnimation();
        } else if (!entry.isIntersecting) {
          gsap.to(window, {
            duration: 0.5,
            ease: "power2.inOut",
            scrollTo: {
              y: "#regVeda",
              offsetY: 0,
            },
          });
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

onMounted(async () => {
  try {
    await preloadImage("/assets/images/main.webp");
    setupIntersectionObserver();

    // Handle focus event
    window.addEventListener('focus', () => {
      if (!show.value && imageLoaded.value) {
        startAnimation();
      }
    });

  } catch (error) {
    console.error("Error loading image:", error);
  }
});

onUnmounted(() => {
  if (observer) {
    observer.disconnect();
  }
  // Cleanup focus event listener
  window.removeEventListener('focus', () => {});
});
</script>

<template>
  <section
    ref="sectionRef"
    id="main"
    class="min-h-screen flex items-start justify-center relative bg-cover bg-center opacity-0"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/main.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
    tabindex="0"
    @focus="startAnimation"
  >
    <div class="absolute inset-0 bg-[#4D4D4D]/30"></div>

    <div class="flex flex-col gap-4 justify-center items-center relative z-10 pt-20">
      <h1 class="title font-dancing text-white text-4xl tracking-wide">
        Dharma & Astrid
      </h1>

      <h6 class="subtitle font-poly text-xl text-white">Wedding Day</h6>

      <div class="divider border-r border-white h-20">&nbsp;</div>

      <p class="date font-poly text-white text-center text-2xl">29.09.2025</p>
    </div>
 <div 
      class="absolute -top-20 left-0 right-0 h-20 pointer-events-none"
      style="background: linear-gradient(to bottom, transparent, rgba(0,0,0,0.1))"
    ></div>
    <div
      v-if="!imageLoaded"
      class="absolute inset-0 flex items-center justify-center bg-[#4D4D4D] z-20"
    >
      <div class="animate-pulse text-white font-poly">Loading...</div>
    </div>
  </section>
</template>

<style scoped>
.title,
.subtitle,
.divider,
.date {
  will-change: transform, opacity;
}

section {
  transition: background-image 0.5s ease-in-out;
  outline: none; /* Remove focus outline */
}

/* Optional: Tambahkan efek hover pada text */
.title:hover,
.subtitle:hover,
.date:hover {
  text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
  transition: text-shadow 0.3s ease;
}
</style>