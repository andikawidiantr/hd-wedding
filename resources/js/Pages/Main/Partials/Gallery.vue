<script setup>
import { ref, onMounted } from "vue";
import VueEasyLightbox from "vue-easy-lightbox";

const imageLoaded = ref(false);
const images = ref([]);
const visibleRef = ref(false);
const indexRef = ref(0);
const isLoading = ref(true);

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
  // Load only first 9 images
  for (let i = 1; i <= 9; i++) {
    initialImages.push({
      src: `/assets/images/gallery/${i}.webp`,
      title: `Gallery Image ${i}`,
    });
  }
  images.value = initialImages;
  isLoading.value = false;
};

// Load remaining images only when needed
const loadRemainingImages = () => {
  const remainingImages = [];
  for (let i = 10; i <= 40; i++) {
    remainingImages.push({
      src: `/assets/images/gallery/${i}.webp`,
      title: `Gallery Image ${i}`,
    });
  }
  images.value = [...images.value, ...remainingImages];
};

const showImg = (index) => {
  if (images.value.length <= 9) {
    loadRemainingImages();
  }
  indexRef.value = index;
  visibleRef.value = true;
};

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

    <div class="relative w-full min-h-screen flex items-start justify-center z-[2] px-4 py-12">
      <div class="flex flex-col gap-16 w-full max-w-6xl">
        <!-- Header -->
        <div class="flex flex-col gap-2">
          <h1 class="text-4xl text-white text-center font-wittgenstein uppercase tracking-wider">
            Gallery
          </h1>
          <h6 class="text-white text-center font-wittgenstein tracking-widest uppercase">
            Dharma & Astri
          </h6>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex justify-center items-center min-h-[400px]">
          <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-white"></div>
        </div>

        <!-- Gallery Grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
          <!-- First 6 images -->
          <div
            v-for="(image, index) in images.slice(0, 6)"
            :key="index"
            class="w-28 h-28 overflow-hidden rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
            @click="showImg(index)"
          >
            <img
              :src="image.src"
              :alt="image.title"
              class="w-full h-full object-cover"
              loading="lazy"
            />
          </div>

          <!-- Last 3 images in span -->
          <div class="md:col-span-2 grid grid-cols-2 gap-4">
            <div
              v-for="(image, index) in images.slice(6, 8)"
              :key="index + 6"
              class="w-28 h-28 overflow-hidden rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
              @click="showImg(index + 6)"
            >
              <img
                :src="image.src"
                :alt="image.title"
                class="w-full h-full object-cover"
                loading="lazy"
              />
            </div>
          </div>

          <!-- More Images Button -->
          <div
            class="w-28 h-28 overflow-hidden rounded-lg cursor-pointer relative"
            @click="showImg(8)"
          >
            <img
              :src="images[8]?.src"
              :alt="images[8]?.title"
              class="w-full h-full object-cover"
              loading="lazy"
            />
            <div class="absolute inset-0 bg-black/60 flex items-center justify-center hover:bg-black/70">
              <span class="text-white text-xl font-bold">+31 More</span>
            </div>
          </div>
        </div>

        <!-- Lightbox -->
        <vue-easy-lightbox
          :visible="visibleRef"
          :imgs="images"
          :index="indexRef"
          @hide="handleHide"
        />
      </div>
    </div>
  </section>
</template>

<style scoped>
.vue-easy-lightbox {
  z-index: 9999;
}

img {
  transform: translateZ(0);
}
</style>