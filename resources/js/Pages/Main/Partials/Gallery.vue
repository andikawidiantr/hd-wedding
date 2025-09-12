<script setup>
import { ref, onMounted, defineAsyncComponent } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { Autoplay, EffectCards } from 'swiper/modules';

// Lazy load lightbox
const VueEasyLightbox = defineAsyncComponent(() => 
  import('vue-easy-lightbox')
);

const loadStyles = async () => {
  await Promise.all([
    import('swiper/css'),
    import('swiper/css/effect-cards')
  ]);
};

const imageLoaded = ref(false);
const images = ref([]);
const isLoading = ref(true);
const visibleRef = ref(false);
const indexRef = ref(0);
const swiperLoaded = ref(false);
const loadingProgress = ref(0);

// Constants
const CHUNK_SIZE = 8;
const TOTAL_IMAGES = 40;
const DELAY_BETWEEN_CHUNKS = 800;

const preloadImage = (url) => {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.onload = () => {
      imageLoaded.value = true;
      resolve();
    };
    img.onerror = reject;
    img.src = url;
  });
};

const createImageObject = (index) => ({
  id: index,
  src: `/assets/images/gallery/${index}.webp`,
  title: `Gallery Image ${index}`,
});

const loadImageChunk = async (startIndex) => {
  const endIndex = Math.min(startIndex + CHUNK_SIZE, TOTAL_IMAGES);
  const newImages = [];

  for (let i = startIndex + 1; i <= endIndex; i++) {
    newImages.push(createImageObject(i));
  }

  images.value = [...images.value, ...newImages];
  loadingProgress.value = (endIndex / TOTAL_IMAGES) * 100;
};

const loadInitialImages = async () => {
  isLoading.value = true;
  try {
    await loadStyles(); // Load Swiper styles
    await loadImageChunk(0);
    isLoading.value = false;
    loadRemainingImages();
  } catch (error) {
    console.error("Error loading initial images:", error);
    isLoading.value = false;
  }
};

const loadRemainingImages = async () => {
  for (let i = CHUNK_SIZE; i < TOTAL_IMAGES; i += CHUNK_SIZE) {
    await new Promise(resolve => setTimeout(resolve, DELAY_BETWEEN_CHUNKS));
    try {
      await loadImageChunk(i);
    } catch (error) {
      console.error(`Error loading chunk starting at ${i}:`, error);
    }
  }
};

const showImg = (index) => {
  indexRef.value = index;
  visibleRef.value = true;
};

const handleHide = () => {
  visibleRef.value = false;
};

const observeGallery = () => {
  const observer = new IntersectionObserver(
    async (entries) => {
      if (entries[0].isIntersecting && !swiperLoaded.value) {
        swiperLoaded.value = true;
        await loadInitialImages();
      }
    },
    { threshold: 0.1 }
  );

  const gallerySection = document.getElementById('gallery');
  if (gallerySection) {
    observer.observe(gallerySection);
    return () => observer.disconnect();
  }
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/gallery.webp");
    observeGallery();
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
        <div 
          v-if="isLoading || !swiperLoaded" 
          class="flex flex-col gap-4 justify-center items-center min-h-[400px]"
        >
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
          <div class="text-white text-sm">
            Loading Gallery...
          </div>
        </div>

        <!-- Gallery Container -->
        <div v-else class="flex flex-col items-center justify-center gap-8">
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
                :key="image.id"
                @click="showImg(index)"
                class="gallery-slide"
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

          <p class="text-white text-center text-sm opacity-80">
            Swipe to view more photos or tap to enlarge
          </p>
        </div>

        <!-- Lightbox -->
        <vue-easy-lightbox
          v-if="visibleRef"
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
.gallery-swiper {
  width: 240px !important;
  height: 520px !important;
  margin: 0 auto;
}

.gallery-slide {
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

:deep(.swiper-wrapper) {
  width: 240px !important;
  align-items: center;
}

.gallery-slide:hover {
  transform: scale(1.02);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

:deep(.vel-modal) {
  z-index: 9999;
}

:deep(.vel-img) {
  object-fit: contain;
}

@media (max-width: 640px) {
  .gallery-swiper,
  .gallery-slide,
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

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>