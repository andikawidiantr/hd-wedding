<script setup>
import { ref, onMounted } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, EffectCards } from 'swiper/modules';
import VueEasyLightbox from 'vue-easy-lightbox';
import 'swiper/css';
import 'swiper/css/effect-cards';

const imageLoaded = ref(false);
const images = ref([]);
const isLoading = ref(true);
const visibleRef = ref(false);
const indexRef = ref(0);

// Simplified image preload
const preloadImage = (url) => {
  return new Promise((resolve) => {
    const img = new Image();
    img.onload = () => {
      imageLoaded.value = true;
      resolve();
    };
    img.src = url;
  });
};

// Load only initial visible images
const loadInitialImages = () => {
  const initialImages = [];
  // Load only first 12 images for better performance
  for (let i = 1; i <= 40; i++) {
    initialImages.push({
      src: `/assets/images/gallery/${i}.webp`,
      title: `Gallery Image ${i}`,
    });
  }
  images.value = initialImages;
  isLoading.value = false;
};

// Handle image click
const showImg = (index) => {
  indexRef.value = index;
  visibleRef.value = true;
};

// Handle lightbox close
const handleHide = () => {
  visibleRef.value = false;
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/gallery.webp");
    loadInitialImages();
  } catch (error) {
    console.error("Error:", error);
    isLoading.value = false;
  }
});
</script>

<template>
  <section
    id="gallery"
    class="relative min-h-screen flex items-start justify-center bg-gray-200 z-[2]"
    :class="{ 'bg-cover bg-center': imageLoaded }"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/gallery.webp)' : 'none',
    }"
  >
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/60 to-black/80"></div>

    <div class="relative w-full min-h-screen flex items-center justify-center z-[2] px-4 py-12">
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

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center min-h-[400px]">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
        </div>

        <!-- Gallery Container -->
        <div v-else class="flex flex-col items-center justify-center gap-8">
          <!-- Swiper Container with Fixed Width -->
          <div class="w-full mx-auto overflow-hidden" style="max-width: 240px;">
            <Swiper
              :modules="[Autoplay, EffectCards]"
              :effect="'cards'"
              :grabCursor="true"
              :autoplay="{
                delay: 2500,
                disableOnInteraction: false,
              }"
              class="gallery-swiper"
            >
              <SwiperSlide
                v-for="(image, index) in images"
                :key="index"
                @click="showImg(index)"
              >
                <div class="w-full h-full rounded-lg overflow-hidden cursor-pointer">
                  <img
                    :src="image.src"
                    :alt="image.title"
                    class="w-full h-full object-cover"
                    loading="lazy"
                  />
                </div>
              </SwiperSlide>
            </Swiper>
          </div>

          <!-- Instruction Text -->
          <p class="text-white text-center text-sm opacity-80">
            Swipe to view more photos or tap to enlarge
          </p>
        </div>

        <!-- Lightbox -->
        <vue-easy-lightbox
          :visible="visibleRef"
          :imgs="images.map(img => img.src)"
          :index="indexRef"
          @hide="handleHide"
        />
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Gallery Swiper Styles */
.gallery-swiper {
  width: 240px !important;
  height: 520px !important;
  margin: 0 auto;
}

/* Swiper Slide Styles */
.swiper-slide {
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 18px;
  font-size: 22px;
  font-weight: bold;
  background-color: #fff;
  cursor: pointer;
  transition: all 0.3s ease;
  width: 240px !important;
  overflow: hidden;
}

/* Swiper Wrapper Styles */
:deep(.swiper-wrapper) {
  width: 240px !important;
  align-items: center;
}

/* Hover Effects */
.swiper-slide:hover {
  transform: scale(1.02);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

/* Image Styles */
img {
  transform: translateZ(0);
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

/* Lightbox Styles */
:deep(.vel-modal) {
  z-index: 9999;
}

:deep(.vel-img) {
  object-fit: contain;
}

/* Responsive Styles */
@media (max-width: 640px) {
  .gallery-swiper,
  .swiper-slide,
  :deep(.swiper-wrapper) {
    width: 200px !important;
    height: 440px !important;
  }
}

@media (max-height: 700px) {
  .gallery-swiper {
    height: 400px !important;
  }
}

/* Loading Animation */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>