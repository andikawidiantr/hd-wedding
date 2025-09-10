<script setup>
import { onMounted, ref } from "vue";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const titleRef = ref(null);
const bcaRef = ref(null);
const sectionRef = ref(null);
const bankInfoRef = ref(null);
const isCopied = ref(false);
const copyTimeout = ref(null);

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

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText("123 456 7890");
    isCopied.value = true;
    
    // Clear existing timeout if any
    if (copyTimeout.value) clearTimeout(copyTimeout.value);
    
    // Reset after 5 seconds
    copyTimeout.value = setTimeout(() => {
      isCopied.value = false;
    }, 5000);

    // Animate the copy icon
    gsap.from(".copy-icon", {
      scale: 0.5,
      rotation: 360,
      duration: 0.5,
      ease: "back.out"
    });
  } catch (err) {
    console.error("Failed to copy:", err);
  }
};

const initAnimations = () => {
  // Fade in animation for title
  gsap.from(titleRef.value, {
    opacity: 0,
    y: 30,
    duration: 1.2,
    ease: "power2.out",
    scrollTrigger: {
      trigger: sectionRef.value,
      start: "top center",
      toggleActions: "play none none reverse",
    },
  });

  // Animate bank info
  gsap.from(bankInfoRef.value, {
    opacity: 0,
    x: -30,
    duration: 1,
    delay: 0.3,
    ease: "power2.out",
    scrollTrigger: {
      trigger: bankInfoRef.value,
      start: "top bottom-=100",
      toggleActions: "play none none reverse",
    },
  });

  // Slide in animation for BCA logo
  gsap.from(bcaRef.value, {
    x: -50,
    opacity: 0,
    duration: 1,
    delay: 0.5,
    ease: "power2.out",
    scrollTrigger: {
      trigger: bcaRef.value,
      start: "top bottom-=100",
      toggleActions: "play none none reverse",
    },
  });
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/gift.webp");
    initAnimations();
  } catch (error) {
    console.error("Error loading image:", error);
  }
});
</script>

<template>
  <section
    ref="sectionRef"
    id="gift"
    class="min-h-screen flex items-start justify-center relative bg-cover bg-center bg-gray-200 z-[2] pb-20 "
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/gift.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
  >
    <div class="w-full h-screen flex items-start z-[2] px-4 py-12 relative">
      <div class="h-full w-full flex flex-col justify-between gap-12 p-4">
        <div class="flex flex-col gap-0">
          <h2 ref="titleRef" class="font-poly text-center text-white text-3xl">
            Wedding Gift
          </h2>
        </div>
        <div class="flex flex-col gap-4">
          <div ref="bankInfoRef" class="flex items-center gap-12">
            <div>
              <h3 class="text-white">BCA</h3>
              <p class="text-white">123 456 7890</p>
              <p class="text-white">a/n Dharma Kumala</p>
            </div>
            <div>
              <button 
                @click="copyToClipboard" 
                class="text-white p-2 rounded-full hover:bg-white/10 transition-all duration-300"
              >
                <mdicon 
                  :name="isCopied ? 'check-all' : 'content-copy'" 
                  class="copy-icon transition-all duration-300"
                  :class="{ 'text-green-400': isCopied }"
                  width="15"
                />
              </button>
            </div>
          </div>

          <div 
            ref="bcaRef" 
            class="w-fit flex justify-start items-center gap-4"
          >
            <div>
              <img src="/assets/images/bca.png" alt="" class="max-w-[200px]" />
            </div>
          </div>
        </div>
      </div>
    </div>
    <div
      class="absolute inset-0 bg-gradient-to-t from-black/100 via-black/10 to-black/100"
    ></div>
  </section>
</template>

<style scoped>
section {
  transition: background-image 0.5s ease-in-out;
}

.copy-icon {
  transform-origin: center;
}

@keyframes copied {
  0% { transform: scale(1); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}

.copied {
  animation: copied 0.3s ease-in-out;
}
</style>