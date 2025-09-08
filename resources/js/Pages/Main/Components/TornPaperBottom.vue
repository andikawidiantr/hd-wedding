<script setup>
import { onMounted } from 'vue';
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

onMounted(() => {
  gsap.from('.torn-paper', {
    y: 100,
    opacity: 0,
    duration: 1.5,
    ease: 'power3.out',
    scrollTrigger: {
      trigger: '.torn-paper',
      start: 'top bottom-=100',
      end: 'bottom center',
      toggleActions: 'play none none reverse'
    }
  });
});
</script>

<template>
  <div class="torn-paper absolute bottom-0 left-0 right-0">
    <!-- Menggunakan image langsung -->
    <img 
      src="/assets/textures/bottom-paper.png" 
      alt="torn paper"
      class="w-full object-cover object-bottom"
    />
  </div>
</template>

<style scoped>
.torn-paper {
  filter: drop-shadow(0 -2px 4px rgba(0,0,0,0.1));
  z-index: 10;
}

.torn-paper img {
  width: 100%;
  height: auto;
  /* Sesuaikan tinggi sesuai kebutuhan */
  min-height: 100px; 
  max-height: 150px;
  /* Tambahkan transform untuk flip vertikal jika diperlukan */
  /* transform: scaleY(-1); */
}

/* Responsive */
@media (max-width: 1024px) {
  .torn-paper img {
    min-height: 80px;
    max-height: 120px;
  }
}

@media (max-width: 768px) {
  .torn-paper img {
    min-height: 60px;
    max-height: 100px;
  }
}

@media (max-width: 480px) {
  .torn-paper img {
    min-height: 40px;
    max-height: 80px;
  }
}
</style>