<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Navigation } from "swiper/modules";
import "viewerjs/dist/viewer.css";
import "swiper/css";
import "swiper/css/navigation";
import Viewer from "viewerjs";
 
const imageLoaded = ref(false);
const swiperRef = ref(null);
let viewer = null;
 
// Kurangi jumlah gambar total
const allImages = Array.from({ length: 30 }, (_, i) => ({
  id: i + 1,
  src: `/assets/images/gallery/${i + 1}.webp`,
}));
 
// Data slider tetap 6 gambar
const slideImages = ref(allImages.slice(0, 6));
 
const swiperOptions = {
  modules: [Autoplay, Navigation],
  slidesPerView: 1,
  spaceBetween: 30,
  centeredSlides: true,
  autoplay: {
    delay: 3000, // Perlambat autoplay
    disableOnInteraction: true, // Hentikan autoplay saat user interaksi
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  loop: true,
};
 
// Sederhanakan preload image
const preloadImage = (url) => {
  return new Promise((resolve) => {
    const img = new Image();
    img.onload = () => {
      imageLoaded.value = true;
      resolve();
    };
    img.onerror = () => resolve(); // Tetap resolve meski error
    img.src = url;
  });
};
 
// Sederhanakan viewer initialization
const initViewer = () => {
  if (viewer) return;
 
  const container = document.createElement('div');
  container.className = 'hidden-gallery';
  
  allImages.forEach(image => {
    const img = document.createElement('img');
    img.src = image.src;
    img.alt = `Gallery Image ${image.id}`;
    container.appendChild(img);
  });
  
  document.body.appendChild(container);
  
  viewer = new Viewer(container, {
    navbar: true,
    title: false,
    toolbar: false, // Hilangkan toolbar untuk ringankan
    transition: false,
    loading: true,
    loop: true,
    keyboard: false, // Matikan keyboard control
    zoomRatio: 0.3,
    minZoomRatio: 0.1,
    maxZoomRatio: 3,
    zIndex: 2015,
    hidden() {
      // Bersihkan viewer setelah ditutup
      viewer.destroy();
      container.remove();
      viewer = null;
    }
  });
};
 
// Sederhanakan show gallery
const showGallery = (index = 0) => {
  initViewer();
  if (viewer) {
    viewer.show();
    setTimeout(() => viewer.view(index), 50);
  }
};
 
// Gunakan lazy mounting
let isMounted = false;
onMounted(async () => {
  if (isMounted) return;
  try {
    await preloadImage("/assets/images/gallery.webp");
    isMounted = true;
  } catch (error) {
    console.error("Error loading gallery:", error);
  }
});
 
// Cleanup yang lebih agresif
onUnmounted(() => {
  if (viewer) {
    viewer.destroy();
    viewer = null;
  }
  const container = document.querySelector('.hidden-gallery');
  if (container) {
    container.remove();
  }
  isMounted = false;
});
</script>

<template>
  <section
    id="gallery"
    class="relative min-h-screen flex items-center justify-center bg-gray-200 z-[2]"
    :class="{ 'bg-cover bg-center': imageLoaded }"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/gallery.webp)' : 'none',
    }"
  >
    <div class="relative w-full h-full flex items-center justify-center z-[2] px-4 py-12">
      <div class="h-full w-full flex flex-col gap-16">
        <!-- Header -->
        <div class="flex flex-col gap-2">
          <h1 class="text-4xl text-white text-center font-wittgenstein uppercase tracking-wider">
            Our Gallery
          </h1>
          <h6 class="text-white text-center font-wittgenstein tracking-widest uppercase">
            Dharma & Astri
          </h6>
        </div>

        <!-- Gallery -->
        <div class="flex flex-col items-center justify-center gap-8">
          <div class="w-full mx-auto overflow-hidden relative max-w-md px-4">
            <Swiper
              v-bind="swiperOptions"
              ref="swiperRef"
              class="gallery-swiper"
            >
              <SwiperSlide
                v-for="(image, index) in slideImages"
                :key="image.id"
                class="gallery-slide"
              >
                <div 
                  class="w-full h-[500px] overflow-hidden cursor-pointer rounded-lg"
                  @click="showGallery(index)"
                >
                  <img
                    :src="image.src"
                    :alt="`Gallery Image ${image.id}`"
                    class="w-full h-full object-cover"
                    loading="lazy"
                  />
                </div>
              </SwiperSlide>

              <div class="navigation-wrapper">
                <button class="swiper-button-prev custom-nav-btn">
                  <mdicon name="chevron-left" size="24" />
                </button>
                <button class="swiper-button-next custom-nav-btn">
                  <mdicon name="chevron-right" size="24" />
                </button>
              </div>
            </Swiper>
          </div>

          <p class="text-white text-center text-sm opacity-80">
            Tap image to view all photos
          </p>
        </div>
      </div>
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/60 to-black/80"></div>
  </section>
</template>

<style scoped>
/* Styles untuk gallery tersembunyi */
:deep(.hidden-gallery) {
  display: none;
}

/* Viewer custom styles */
:deep(.viewer-container) {
  background-color: rgba(0, 0, 0, 0.9);
}

:deep(.viewer-toolbar) {
  background-color: rgba(0, 0, 0, 0.5);
}

:deep(.viewer-navbar) {
  background-color: rgba(0, 0, 0, 0.5);
}
:deep(.viewer-loading) {
  color: #fff;
}

:deep(.viewer-loading::after) {
  border-color: rgba(255, 255, 255, 0.2);
  border-top-color: white;
}


/* Swiper styles */
.gallery-swiper {
  width: 100%;
  height: 500px;
}

.gallery-slide {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.navigation-wrapper {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
  z-index: 10;
}

.custom-nav-btn {
  background-color: rgba(255, 255, 255, 0.3);
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #000;
  cursor: pointer;
  transition: all 0.3s ease;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: auto;
}

.swiper-button-prev {
  left: 10px;
}

.swiper-button-next {
  right: 10px;
}

.custom-nav-btn:hover {
  background-color: rgba(255, 255, 255, 0.8);
}

/* Hide default swiper navigation */
:deep(.swiper-button-next::after),
:deep(.swiper-button-prev::after) {
  display: none;
}

@media (max-width: 640px) {
  .gallery-swiper {
    height: 400px;
  }
  
  .custom-nav-btn {
    width: 32px;
    height: 32px;
  }
}
</style>