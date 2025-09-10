<script setup>
import { onMounted, ref } from "vue";
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const mapRef = ref(null);
const titleRef = ref(null);
const qrRef = ref(null);
const mapButtonRef = ref(null);

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
    await preloadImage("/assets/images/map.webp");

    // Animasi setelah gambar dimuat
    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: mapRef.value,
        start: "top center+=100",
        end: "center center",
        toggleActions: "play none none reverse"
      }
    });

    // Set initial states
    gsap.set(titleRef.value, {
      y: 30,
      opacity: 0
    });
    gsap.set(qrRef.value, {
      scale: 0.8,
      opacity: 0
    });
    gsap.set(mapButtonRef.value, {
      y: 20,
      opacity: 0
    });

    // Animasi sequence
    tl.to(titleRef.value, {
      y: 0,
      opacity: 1,
      duration: 1,
      ease: "power3.out"
    })
    .to(qrRef.value, {
      scale: 1,
      opacity: 1,
      duration: 0.8,
      ease: "back.out(1.7)"
    }, "-=0.5")
    .to(mapButtonRef.value, {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: "power3.out"
    }, "-=0.5");

    // Parallax effect pada background
    gsap.to(mapRef.value, {
      backgroundPosition: "50% 30%",
      ease: "none",
      scrollTrigger: {
        trigger: mapRef.value,
        start: "top top",
        end: "bottom top",
        scrub: true
      }
    });

  } catch (error) {
    console.error("Error loading image:", error);
  }
});
</script>

<template>
  <section
    ref="mapRef"
    id="map"
    class="min-h-screen flex items-start justify-center relative bg-cover bg-center bg-gray-200 z-[2] -mt-10"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/map.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
  >
    <div class="w-full h-screen flex items-center justify-center z-[2] px-4 py-12">
      <div class="flex flex-col gap-12 p-4">
        <div ref="titleRef" class="flex flex-col gap-0">
          <h2 class="font-poly text-center text-white text-3xl drop-shadow-lg">
            Delod Sema Village, Tegalalang, Taro
          </h2>
        </div>

        <div ref="qrRef" class="flex gap-8 items-center justify-center">
          <div class="bg-white p-2 rounded-md shadow-lg transform transition-transform duration-300 hover:scale-105">
            <img src="/assets/images/qr.png" alt="QR Code" class="w-full h-full" />
          </div>
        </div>

        <div ref="mapButtonRef" class="flex items-center justify-center">
          <a
            href="https://maps.app.goo.gl/7GP44pSQC3d3h8WN8"
            target="_blank"
            class="map-button border border-white rounded-xl px-6 py-1 text-white uppercase relative overflow-hidden group"
          >
            <span class="relative z-10">OPEN MAP</span>
            <div class="absolute inset-0 bg-white transform origin-bottom transition-transform duration-300 ease-out scale-y-0 group-hover:scale-y-100"></div>
          </a>
        </div>
      </div>
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-black/70"></div>
  </section>
</template>

<style scoped>
.map-button {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  isolation: isolate;
}

.map-button:hover {
  transform: translateY(-2px);
  color: #000;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.map-button:active {
  transform: translateY(0);
}

.map-button::before {
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

.map-button:hover::before {
  opacity: 1;
}

/* QR Code hover effect */
.bg-white {
  transition: all 0.3s ease;
}

.bg-white:hover {
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

/* Title text shadow */
.drop-shadow-lg {
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}
</style>