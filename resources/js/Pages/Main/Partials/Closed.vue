<script setup>
import { onMounted, ref } from "vue";

const sectionRef = ref(null);
const isVisible = ref(false);

// Simple Intersection Observer setup
const setupIntersectionObserver = () => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isVisible.value = true;
        }
      });
    },
    {
      threshold: 0.2,
      rootMargin: "-100px"
    }
  );

  if (sectionRef.value) {
    observer.observe(sectionRef.value);
  }

  return observer;
};

onMounted(() => {
  const observer = setupIntersectionObserver();
  return () => observer.disconnect();
});
</script>

<template>
  <section
    ref="sectionRef"
    id="closed"
    class="min-h-screen flex items-center justify-center relative bg-cover bg-center -mt-20 z-[3] overflow-hidden"
    style="background-image: url('/assets/images/full-torn.png')"
  >
    <div
      class=" h-full mx-auto px-4 md:px-12 flex flex-col items-start justify-center"
    >
      <!-- Title -->
      <h1
        class="font-wittgenstein text-2xl md:text-3xl text-black mb-6 text-center select-none transition-all duration-700 delay-100"
        :class="{ 'translate-y-8 opacity-0': !isVisible }"
      >
        Sapta Padi (Tujuh Langkah Suci)
      </h1>

      <!-- Sanskrit Text -->
      <p
        class="font-wittgenstein text-center text-black w-full text-lg md:text-xl mb-8 select-none transition-all duration-700 delay-300"
        :class="{ 'translate-y-8 opacity-0': !isVisible }"
      >
        "Ekam iṣe viṣṇuḥ tvā anvetu..."
      </p>

      <!-- Translation -->
      <p
        class="font-wittgenstein text-center text-black max-w-2xl text-md leading-relaxed select-none transition-all duration-700 delay-500"
        :class="{ 'translate-y-8 opacity-0': !isVisible }"
      >
        Dengan langkah pertama, semoga kita mendapatkan kesejahteraan. Dengan langkah
        kedua, semoga kita mendapatkan kekuatan. Dengan langkah ketiga, semoga kita
        memperoleh kekayaan. Dengan langkah keempat, semoga kita memperoleh kebahagiaan.
        Dengan langkah kelima, semoga kita diberkati dengan anak-anak. Dengan langkah
        keenam, semoga kita mendapatkan musim yang indah. Dengan langkah ketujuh, semoga
        kita tetap bersahabat dan setia selamanya." Singkatnya "Dengan tujuh langkah suci,
        kami berjanji untuk hidup dalam kesejahteraan, kebahagiaan, dan kesetiaan
        selamanya.
      </p>
    </div>

    <!-- Decoration -->
    <div
      class="absolute -bottom-5 z-[3] transition-all duration-700 delay-700"
      :class="{ 'opacity-0 translate-y-8': !isVisible }"
    >
      <div class="p-4">
        <img
          src="/assets/images/flower.png"
          alt="Decoration"
          class="w-32 h-32 lg:w-40 lg:h-40"
        />
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Optimize transitions */
.transition-all {
  will-change: transform, opacity;
}

/* Smooth background transition */
section {
  transition: background-image 0.5s ease-in-out;
}

/* Mobile padding */
@media (max-width: 640px) {
  .container {
    padding: 2rem;
  }
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
  .transition-all {
    transition: none !important;
  }
  
  section {
    transition: none !important;
  }
}
</style>