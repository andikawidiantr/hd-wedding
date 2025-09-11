<script setup>
import { onMounted, ref } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const sectionRef = ref(null);
const containerRef = ref(null);
const titleRef = ref(null);
const sanskritRef = ref(null);
const translationRef = ref(null);
const decorationRef = ref(null);

const initAnimations = () => {
  // Reset transform pada container
  gsap.set(containerRef.value, {
    clearProps: "all",
    transform: "none",
  });

  // Reset initial states
  gsap.set([titleRef.value, sanskritRef.value, translationRef.value], {
    autoAlpha: 0,
    y: 50,
  });

  gsap.set(decorationRef.value, {
    autoAlpha: 0,
  });

  // Animasi konten
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: sectionRef.value,
      start: "top center",
      end: "center center",
      toggleActions: "play none none reverse",
    },
  });

  tl.to(containerRef.value, {
    y: 0,
    duration: 0,
    immediateRender: true,
  })
    .to([titleRef.value, sanskritRef.value, translationRef.value], {
      duration: 1.2,
      autoAlpha: 1,
      y: 0,
      ease: "power3.out",
      stagger: 0.2,
    })
    .to(
      decorationRef.value,
      {
        duration: 1,
        autoAlpha: 1,
        ease: "power2.out",
      },
      "-=1"
    );
};

onMounted(() => {
  requestAnimationFrame(() => {
    initAnimations();
  });
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
      ref="containerRef"
      class="container mx-auto px-4 md:px-12 flex flex-col items-center justify-center"
      :style="{ transform: 'none' }"
    >
      <h1
        ref="titleRef"
        class="font-wittgenstein text-2xl md:text-3xl text-black mb-6 text-center select-none opacity-0"
      >
         Sapta Padi (Tujuh Langkah Suci)
      </h1>

      <p
        ref="sanskritRef"
        class="font-wittgenstein text-center text-black max-w-2xl text-lg md:text-xl mb-8 select-none opacity-0"
      >
           "Ekam iṣe viṣṇuḥ tvā anvetu..."
      </p>

      <p
        ref="translationRef"
        class="font-wittgenstein text-center text-black max-w-2xl text-md leading-relaxed select-none opacity-0"
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

    <div
      ref="decorationRef"
      class="absolute -bottom-4 z-[3] opacity-0 transition-opacity duration-300"
    >
      <div class="p-4">
        <img
          src="/assets/images/flower.png"
          alt="Decoration"
          class="w-48 h-48"
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

@media (max-width: 640px) {
  .container {
    padding: 2rem;
  }
}

/* Transisi opacity untuk dekorasi */
.opacity-0 {
  opacity: 0;
}

.transition-opacity {
  transition: opacity 0.3s ease-in-out;
}
</style>