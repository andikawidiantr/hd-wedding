<script setup>
import { onMounted } from 'vue';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
  gsap.from('.torn-paper-top', {
    y: -100,
    opacity: 0,
    duration: 1.5,
    ease: 'power3.out',
    scrollTrigger: {
      trigger: '.torn-paper-top',
      start: 'top bottom',
      end: 'bottom center',
      toggleActions: 'play none none reverse'
    }
  });
});
</script>

<template>
  <div class="torn-paper-top absolute top-0 left-0 right-0">
    <!-- Menggunakan image langsung dengan rotate -->
    <img 
      src="/assets/textures/top-paper.png" 
      alt="torn paper"
      class="w-full object-cover object-top transform rotate-180"
    />
  </div>
</template>

<style scoped>
.torn-paper-top {
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
  z-index: 10;
}

.torn-paper-top img {
  width: 100%;
  height: auto;
  min-height: 100px;
  max-height: 150px;
}

/* Responsive */
@media (max-width: 1024px) {
  .torn-paper-top img {
    min-height: 80px;
    max-height: 120px;
  }
}

@media (max-width: 768px) {
  .torn-paper-top img {
    min-height: 60px;
    max-height: 100px;
  }
}

@media (max-width: 480px) {
  .torn-paper-top img {
    min-height: 40px;
    max-height: 80px;
  }
}
</style>