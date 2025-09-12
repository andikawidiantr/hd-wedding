<script setup>
import { onMounted, ref } from 'vue';
import gsap from 'gsap';

const props = defineProps({
  show: Boolean,
  guest: String,
});

const emit = defineEmits(["close", "music"]);
const close = () => {
  // Animasi keluar
  const tl = gsap.timeline({
    onComplete: () =>{
      emit("close");
      emit("music", true);
    }
  });
  
  tl.to(".button-open", { y: 50, opacity: 0, duration: 0.3 })
    .to(".guest-info", { y: 50, opacity: 0, duration: 0.3 }, "-=0.2")
    .to(".names", { y: 50, opacity: 0, duration: 0.3 }, "-=0.2")
    .to(".title", { y: 50, opacity: 0, duration: 0.3 }, "-=0.2")
    .to(".container", { y: "100%", duration: 0.5 });
};

const containerRef = ref(null);
const titleRef = ref(null);
const namesRef = ref(null);
const guestInfoRef = ref(null);
const buttonRef = ref(null);

onMounted(() => {
  if (props.show) {
    // Reset posisi
    gsap.set(".container", { y: "100%" });
    gsap.set([".title", ".names", ".guest-info", ".button-open"], {
      y: 50,
      opacity: 0
    });

    // Animasi masuk
    const tl = gsap.timeline();
    
    tl.to(".container", { 
      y: "0%", 
      duration: 1,
      ease: "power4.out"
    })
    .to(".title", { 
      y: 0, 
      opacity: 1, 
      duration: 0.8,
      ease: "back.out"
    })
    .to(".names", { 
      y: 0, 
      opacity: 1, 
      duration: 0.8,
      ease: "back.out"
    }, "-=0.4")
    .to(".guest-info", { 
      y: 0, 
      opacity: 1, 
      duration: 0.8,
      ease: "back.out"
    }, "-=0.4")
    .to(".button-open", { 
      y: 0, 
      opacity: 1, 
      duration: 0.8,
      ease: "back.out"
    }, "-=0.4");
  }
});
</script>

<template>
  <div class="fixed inset-0 flex items-center justify-center z-[100] overflow-hidden">
    <div 
      ref="containerRef"
      class="container relative max-w-md w-full h-screen"
    >
      <!-- Background -->
      <div
        class="absolute inset-0 bg-[url('/assets/images/opening.webp')] bg-cover bg-center"
      ></div>

      <!-- Overlay -->
      <div class="absolute inset-0 bg-black/50"></div>

      <!-- Content -->
      <div
        class="relative h-full w-full flex flex-col justify-between items-center py-16"
      >
        <div class="p-6 text-white flex flex-col gap-12">
          <!-- Title -->
          <h1 
            ref="titleRef"
            class="title font-eyesome  text-7xl tracking-wide text-center"
          >
            WEDDING <br>DAY
          </h1>

          <!-- Names -->
          <div 
            ref="namesRef"
            class="names flex items-center justify-center flex-col gap-4"
          >
            <h2 class="font-eyesome  text-2xl tracking-wider font-light">Dharma & Astri</h2>
           
          </div>
        </div>

        <div class="p-6 flex flex-col gap-8 items-center">
          <!-- Guest Info -->
          <div 
            ref="guestInfoRef"
            class="guest-info flex flex-col gap-4"
          >
            <h3 class="font-eyesome text-white text-center text-2xl">Untuk</h3>
            <div class="flex flex-col e">
              <p class="text-white text-center  font-eyesome font-semibold">Bapak/Ibu/Saudara/i</p>
              <p class="text-white text-center text-xl font-eyesome font-bold">
                {{ guest }}
              </p>
            </div>
          </div>

          <!-- Button -->
          <button
            ref="buttonRef"
            type="button"
            class="button-open flex items-center border rounded-xl px-12 py-2 text-white border-white gap-2 
                   hover:bg-white hover:text-black transition-colors duration-300 font-eyesome font-bold"
            @click="close"
          >
            <mdicon name="email-heart-outline" />
            Buka Undangan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.container {
  transform: translateY(100%);
}
:deep(.fixed) {
  position: fixed;
  z-index: 100;
}

/* Optional: Tambahkan hover effect pada button */
.button-open:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 6px rgba(255, 255, 255, 0.1);
}
</style>