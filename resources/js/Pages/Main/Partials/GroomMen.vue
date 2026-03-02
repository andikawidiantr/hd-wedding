<script setup>
import { onMounted, ref } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import { useI18n } from 'vue-i18n'; // Import useI18n

// Initialize i18n
const { t } = useI18n();

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const groomMenRef = ref(null);
const titleRef = ref(null);
const lineRef = ref(null);
const nameRef = ref(null);
const descRef = ref(null);
const socialRef = ref(null);
const animationTriggered = ref(false); 

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

onMounted(async () => {
  try {
    await preloadImage("/assets/images/170.jpg");

    // Set initial states - do this immediately after image loads
    gsap.set(
      [titleRef.value, lineRef.value, nameRef.value, descRef.value, socialRef.value],
      {
        x: -50,
        opacity: 0,
      }
    );

    // Animasi setelah gambar dimuat
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: groomMenRef.value,
        start: "top center+=100",
        end: "center center",
        toggleActions: "play none none reverse",
        onEnter: () => {
          animationTriggered.value = true;
        }
      },
    });

    // Animasi sequence
    tl.to(titleRef.value, {
      x: 0,
      opacity: 1,
      duration: 0.8,
      ease: "power3.out",
    })
      .to(
        lineRef.value,
        {
          x: 0,
          opacity: 1,
          duration: 0.6,
          ease: "power3.out",
        },
        "-=0.4"
      )
      .to(
        nameRef.value,
        {
          x: 0,
          opacity: 1,
          duration: 0.8,
          ease: "power3.out",
        },
        "-=0.4"
      )
      .to(
        descRef.value,
        {
          x: 0,
          opacity: 1,
          duration: 0.8,
          ease: "power3.out",
        },
        "-=0.4"
      )
      .to(
        socialRef.value,
        {
          x: 0,
          opacity: 1,
          duration: 0.8,
          ease: "power3.out",
        },
        "-=0.4"
      );

    // Parallax effect pada background
    gsap.to(".background-image", {
      backgroundPosition: "50% 30%",
      ease: "none",
      scrollTrigger: {
        trigger: groomMenRef.value,
        start: "top top",
        end: "bottom top",
        scrub: true,
      },
    });
  } catch (error) {
    console.error("Error loading image:", error);
  }
});
</script>

<template>
  <section
    ref="groomMenRef"
    id="groomMen"
    class="min-h-screen flex items-start justify-center relative z-[2] -mt-10 overflow-hidden py-4 md:py-8 lg:py-12 px-0"
    :style="{
      backgroundColor: 'transparent',
    }"
  >
    <!-- Background div with padding effect -->
    <div
      class="absolute inset-0 m-4 md:m-8 lg:m-12 background-image"
      :style="{
        backgroundImage: imageLoaded ? 'url(/assets/images/170.jpg)' : 'none',
        backgroundPosition: '50% 50%',
        backgroundSize: 'cover',
        backgroundRepeat: 'no-repeat',
      }"
    ></div>
    
    <!-- Content -->
    <div class="w-full h-screen flex items-end justify-start z-[2] px-4 py-12 relative">
      <div class="flex flex-col gap-4 p-4">
        <h2
          ref="titleRef"
          class="text-xl uppercase font-wittgenstein text-white drop-shadow-lg"
        >
          {{ t('groom.title', 'The Groom') }}
        </h2>
        <div ref="lineRef" class="border-t w-48 border-white"></div>
        <h1 ref="nameRef" class="text-3xl font-wittgenstein text-white">
          {{ t('groom.name', 'Ir. I Gede Agus Hendrawan, ST') }}
        </h1>
        <p ref="descRef" class="font-wittgenstein text-white text-md">
          {{ t('groom.description', 'Putra Pertama dari Bapak I Made Rama & Ibu Ni Ketut Rumaheni') }}
        </p>
        <a
          ref="socialRef"
          href="https://www.instagram.com/agushendrawan/"
          target="_blank"
          class="social-button transition-all ease-in flex items-center gap-2 font-wittgenstein text-white px-4 py-1 rounded-xl w-fit text-xs relative group border border-white bg-black/30 backdrop-blur-sm"  
        >
          <div class="relative z-10 flex items-center gap-2">
            <mdicon name="instagram" width="15" height="15" class="icon-instagram"/>   
            <span class="button-text">Instagram</span>
          </div>
          <div class="absolute inset-0 bg-black/50 rounded-xl transform origin-bottom transition-transform duration-300 ease-out scale-y-0 group-hover:scale-y-100"></div>  
        </a>
      </div>
    </div>
    
    <!-- Overlay - adjusted to be transparent or with minimal opacity -->
    <div
      class="absolute inset-0 m-4 md:m-8 lg:m-12 bg-black/10 transition-opacity duration-500"
      :class="{ 'opacity-100': imageLoaded, 'opacity-0': !imageLoaded }"
    ></div>
  </section>
</template>

<style scoped>
.transition-all {
  transition: all 0.3s ease-in-out;
}
 
.social-button {
  isolation: isolate;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
  border: 1px solid transparent;
}
 
.social-button:hover {
  transform: translateY(-4px);
  border-color: white;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
 
.social-button:active {
  transform: translateY(-2px);
}
 
.icon-instagram {
  transition: transform 0.3s ease;
}
 
.social-button:hover .icon-instagram {
  transform: scale(1.1);
}
 
.button-text {
  position: relative;
  z-index: 10;
}
 
/* Efek hover menggunakan pseudo-element */
.social-button::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(to bottom, rgba(255,255,255,0.1), rgba(255,255,255,0));
  opacity: 0;
  transition: opacity 0.3s ease;
}
 
.social-button:hover::before {
  opacity: 1;
}
 
/* Animasi untuk konten di dalam tombol */
.social-button > div {
  transition: transform 0.3s ease;
}
 
.social-button:hover > div:first-child {
  transform: translateY(-1px);
}
 
/* Efek ripple */
.social-button::after {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 5px;
  height: 5px;
  background: rgba(255, 255, 255, 0.5);
  opacity: 0;
  border-radius: 100%;
  transform: scale(1, 1) translate(-50%);
  transform-origin: 50% 50%;
}
 
.social-button:focus:not(:active)::after {
  animation: ripple 1s ease-out;
}
 
@keyframes ripple {
  0% {
    transform: scale(0, 0);
    opacity: 0.5;
  }
  20% {
    transform: scale(25, 25);
    opacity: 0.3;
  }
  100% {
    opacity: 0;
    transform: scale(40, 40);
  }
}
 
/* Media Queries untuk responsiveness */
@media (max-width: 768px) {
  .social-button:hover {
    transform: translateY(-2px);
  }
  
  .social-button:active {
    transform: translateY(0);
  }
}
 
/* Tambahan animasi untuk section bride */
.social-button {
  transform-origin: right;  /* Karena button berada di kanan */
}
 
/* Optional: Animasi khusus untuk section bride */
@media (min-width: 768px) {
  .social-button:hover {
    padding-left: 1.5rem;
    padding-right: 1.5rem;
  }
}
</style>
