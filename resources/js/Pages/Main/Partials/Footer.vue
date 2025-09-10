<script setup>
import { onMounted, ref } from "vue";
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const footerRef = ref(null);
const thankYouRef = ref(null);
const namesRef = ref(null);
const creditsRef = ref(null);

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

const setupAnimations = () => {
  // Thank You text animation
  gsap.fromTo(thankYouRef.value,
    { 
      opacity: 0,
      y: 30
    },
    {
      opacity: 1,
      y: 0,
      duration: 1.5,
      ease: "power4.out",
      scrollTrigger: {
        trigger: footerRef.value,
        start: "top center+=100",
        toggleActions: "play none none reverse"
      }
    }
  );

  // Names animation with sparkle effect
  gsap.fromTo(namesRef.value,
    {
      opacity: 0,
      scale: 0.95,
      filter: 'blur(5px)'
    },
    {
      opacity: 1,
      scale: 1,
      filter: 'blur(0px)',
      duration: 2,
      delay: 0.5,
      ease: "power4.out",
      scrollTrigger: {
        trigger: footerRef.value,
        start: "top center+=100",
        toggleActions: "play none none reverse"
      }
    }
  );

  // Credits fade up animation
  gsap.fromTo(creditsRef.value,
    {
      opacity: 0,
      y: 20
    },
    {
      opacity: 1,
      y: 0,
      duration: 1,
      delay: 1,
      ease: "power3.out",
      scrollTrigger: {
        trigger: footerRef.value,
        start: "top center+=100",
        toggleActions: "play none none reverse"
      }
    }
  );
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/footer.webp");
    setupAnimations();
  } catch (error) {
    console.error("Error loading image:", error);
  }
});
</script>

<template>
  <section
    ref="footerRef"
    id="footer"
    class="min-h-screen flex items-start justify-center bg-cover bg-center relative bg-gray-200 z-[1] -mt-10"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/footer.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
  >
    <div class="w-full h-screen flex items-center justify-center z-[2] px-4 py-12">
      <div class="h-full w-full flex gap-12 p-4 items-end justify-center">
        <div class="flex flex-col gap-8">
          <div class="flex flex-col gap-0">
            <h2 
              ref="thankYouRef"
              class="font-poly text-center text-white text-xl animate-fade-in"
            >
              Thank You
            </h2>
            <h2 
              ref="namesRef"
              class="font-eyesome text-center text-white text-3xl animate-sparkle"
            >
              Dharma & Astri
            </h2>
          </div>
          <div 
            ref="creditsRef"
            class="flex flex-col gap-4 text-center animate-fade-up"
          >
            <p class="text-gray-200">
              Designed and Developed by
              <a
                href="https://www.instagram.com/kiranika.id/"
                target="_blank"
                class="underline hover:text-white transition-colors duration-300 hover:scale-105 inline-block"
              >Kiranika</a>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div
      class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-black/70"
    ></div>
  </section>
</template>

<style scoped>
/* Fade In Animation */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Sparkle Animation */
@keyframes sparkle {
  0% {
    opacity: 0;
    transform: scale(0.95);
    filter: blur(5px);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.02);
    filter: blur(0);
  }
  100% {
    opacity: 1;
    transform: scale(1);
    filter: blur(0);
  }
}

/* Fade Up Animation */
@keyframes fadeUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Apply animations as fallback */
.animate-fade-in {
  animation: fadeIn 1.5s ease-out forwards;
}

.animate-sparkle {
  animation: sparkle 2s ease-out forwards;
}

.animate-fade-up {
  animation: fadeUp 1s ease-out forwards;
  animation-delay: 1s;
}

/* Optimize performance */
.font-poly,
.font-eyesome {
  transform: translateZ(0);
  backface-visibility: hidden;
  will-change: transform, opacity;
}

/* Hover effect for link */
a {
  transition: all 0.3s ease;
  display: inline-block;
}

a:hover {
  text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
  transform: translateY(-1px);
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Optimize animations */
* {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>