<script setup>
import { onMounted, ref } from 'vue';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const regVedaRef = ref(null);
const titleRef = ref(null);
const sanskritRef = ref(null);
const translationRef = ref(null);

onMounted(() => {
  // Set initial state
  gsap.set([titleRef.value, sanskritRef.value, translationRef.value], {
    y: 50,
    opacity: 0
  });

  // Create animation timeline
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: regVedaRef.value,
      start: "top center+=100",
      end: "bottom center",
      toggleActions: "play none none reverse"
    }
  });

  // Add animations to timeline
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
});
</script>

<template>
  <section
    ref="regVedaRef"
    id="regVeda"
    class="min-h-screen flex items-start justify-center relative bg-cover bg-center -mt-20 z-[3]"
    style="background-image: url('/assets/images/full-torn.png')"
  >
    <div
      class="container mx-auto px-12 flex flex-col items-center justify-center mt-20"
    >
      <h1 
        ref="titleRef"
        class="font-wittgenstein text-2xl text-black mb-4"
      >
        Ṛg Veda X.85.47
      </h1>
      <p 
        ref="sanskritRef"
        class="font-wittgenstein text-center text-black max-w-2xl text-xl"
      >
        "Tvāṁ patīṁ gr̥hṇāmi, tvamasi me sahadharmacārīni."
      </p>
      <p 
        ref="translationRef"
        class="font-wittgenstein text-center text-black max-w-2xl mt-8"
      >
        Aku menerima engkau sebagai suamiku/istriku; engkau adalah sahabatku dalam Dharma.
        Bersama kita akan hidup, mencintai, dan membangun kebahagiaan.
      </p>
    </div>

    <div class="absolute -bottom-2 z-[3] ">
      <img src="/assets/images/cambodia.png" alt="Decoration" class="w-24 h-24" />
    </div>

  </section>
</template>

<style scoped>
/* Optional: Add transition classes for smoother animation fallback */
.fade-up-enter-active,
.fade-up-leave-active {
  transition: all 0.8s ease;
}

.fade-up-enter-from,
.fade-up-leave-to {
  opacity: 0;
  transform: translateY(50px);
}
</style>