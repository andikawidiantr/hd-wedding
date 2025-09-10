<script setup>
import { onMounted, ref } from 'vue';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const regVedaRef = ref(null);
const titleRef = ref(null);
const sanskritRef = ref(null);
const translationRef = ref(null);
const decorationRef = ref(null);
const containerRef = ref(null);

onMounted(() => {
  // Reset container transform
  gsap.set(containerRef.value, {
    clearProps: "all",
    transform: "none"
  });

  // Set initial states
  gsap.set([titleRef.value, sanskritRef.value, translationRef.value], {
    y: 50,
    opacity: 0
  });

  gsap.set(decorationRef.value, {
    opacity: 1,
    rotation: 0
  });

  // Create animation timeline for text
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: regVedaRef.value,
      start: "top center+=100",
      end: "bottom center",
      toggleActions: "play none none reverse"
    }
  });

  // Text animations
  tl.to(titleRef.value, {
    y: 0,
    opacity: 1,
    duration: 1,
    ease: "power3.out"
  })
  .to(sanskritRef.value, {
    y: 0,
    opacity: 1,
    duration: 1,
    ease: "power3.out"
  }, "-=0.7")
  .to(translationRef.value, {
    y: 0,
    opacity: 1,
    duration: 1,
    ease: "power3.out"
  }, "-=0.7");

  // Decoration rotation animation
  gsap.to(decorationRef.value, {
    rotation: 360,
    ease: "none",
    scrollTrigger: {
      trigger: regVedaRef.value,
      start: "top top",
      end: "bottom bottom",
      scrub: 1,
      onUpdate: (self) => {
        const bounce = Math.sin(self.progress * Math.PI) * 15;
        gsap.to(decorationRef.value, {
          y: bounce,
          duration: 0.1
        });
      }
    }
  });
});
</script>

<template>
  <section
    ref="regVedaRef"
    id="regVeda"
    class="min-h-screen flex items-start justify-center relative bg-cover bg-center -mt-20 z-[3] overflow-visible"
    style="background-image: url('/assets/images/full-torn.png')"
  >
    <div
      ref="containerRef"
      class="container mx-auto px-12 flex flex-col items-center justify-center pt-20"
      :style="{ transform: 'none' }"
    >
      <h1 
        ref="titleRef"
        class="font-wittgenstein text-2xl md:text-3xl text-black mb-4 text-center select-none opacity-0"
      >
        Ṛg Veda X.85.47
      </h1>
      
      <p 
        ref="sanskritRef"
        class="font-wittgenstein text-center text-black max-w-2xl text-lg md:text-xl mb-8 select-none opacity-0"
      >
        "Tvāṁ patīṁ gr̥hṇāmi, tvamasi me sahadharmacārīni."
      </p>
      
      <p 
        ref="translationRef"
        class="font-wittgenstein text-center text-black max-w-2xl text-base md:text-lg leading-relaxed select-none opacity-0"
      >
        Aku menerima engkau sebagai suamiku/istriku; engkau adalah sahabatku dalam Dharma.
        Bersama kita akan hidup, mencintai, dan membangun kebahagiaan.
      </p>
    </div>

    <div 
      ref="decorationRef"
      class="absolute z-[4] transition-transform duration-300 ease-linear will-change-transform"
      style="bottom: -2rem;"
    >
      <div class="p-4 overflow-visible">
        <img 
          src="/assets/images/cambodia.png" 
          alt="Decoration" 
          class="w-24 h-24 hover:scale-110 transition-transform duration-300 object-contain"
        />
      </div>
    </div>
  </section>
</template>

<style scoped>
section {
  transition: background-image 0.5s ease-in-out;
}

/* Reset transform pada container */
.container {
  transform: none !important;
  translate: none !important;
  rotate: none !important;
  scale: none !important;
}

/* Optimasi performa untuk animasi */
.will-change-transform {
  will-change: transform;
  transform-style: preserve-3d;
  backface-visibility: hidden;
}

/* Responsive padding */
@media (max-width: 640px) {
  .container {
    padding: 2rem;
  }
}

/* Optional: smooth transition untuk hover */
img {
  transition: transform 0.3s ease-in-out;
}

img:hover {
  transform: scale(1.1);
}
</style>