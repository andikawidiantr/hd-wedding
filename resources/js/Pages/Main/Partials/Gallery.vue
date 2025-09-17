<script setup>
import { ref, onMounted } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Navigation } from "swiper/modules";
import "viewerjs/dist/viewer.css";
import "swiper/css";
import "swiper/css/navigation";
import Viewer from "viewerjs";

const imageLoaded = ref(false);
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

// Data untuk semua gambar (40 gambar)
const allImages = Array.from({ length: 40 }, (_, i) => ({
  id: i + 1,
  src: `/assets/images/gallery/${i + 1}.webp`,
}));

// Data untuk 6 gambar yang ditampilkan di slideshow
const slideImages = ref(allImages.slice(0, 6));
const containerRef = ref(null);
const swiperRef = ref(null);
let viewer = null;

// Konfigurasi Swiper
const swiperOptions = {
  modules: [Autoplay, Navigation],
  slidesPerView: 1,
  spaceBetween: 30,
  centeredSlides: true,
  autoplay: {
    delay: 2500,
    disableOnInteraction: false,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  loop: true,
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/gallery.webp");
    // Initialize viewer dengan semua gambar
    const viewerContainer = containerRef.value;

    // Tambahkan hidden container untuk semua gambar
    const hiddenContainer = document.createElement("div");
    hiddenContainer.style.display = "none";

    // Tambahkan semua gambar ke hidden container
    allImages.forEach((image) => {
      const img = document.createElement("img");
      img.src = image.src;
      hiddenContainer.appendChild(img);
    });

    viewerContainer.appendChild(hiddenContainer);

    // Initialize viewer
    viewer = new Viewer(viewerContainer, {
      navbar: true,
      title: false,
      toolbar: false,
      tooltip: false,
      loading: true,
      hidden: function () {
        viewer.destroy();
        initializeViewer();
      },
    });
  } catch (error) {
    console.error("Error loading background image:", error);
  }
});

const initializeViewer = () => {
  viewer = new Viewer(containerRef.value, {
    navbar: true,
    title: false,
    toolbar: false,
    tooltip: false,
    loading: true,
    hidden: function () {
      viewer.destroy();
      initializeViewer();
    },
  });
};

const showAllImages = () => {
  if (viewer) {
    viewer.show();
  }
};
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
          <h1
            class="text-4xl text-white text-center font-wittgenstein uppercase tracking-wider"
          >
            Our Gallery
          </h1>
          <h6 class="text-white text-center font-wittgenstein tracking-widest uppercase">
            Dharma & Astri
          </h6>
        </div>

        <!-- Gallery Container -->
        <div ref="containerRef" class="flex flex-col items-center justify-center gap-8">
          <div class="w-full mx-auto overflow-hidden relative max-w-md px-4">
            <Swiper
              v-bind="swiperOptions"
              ref="swiperRef"
              class="gallery-swiper"
              @click="showAllImages"
            >
              <SwiperSlide
                v-for="image in slideImages"
                :key="image.id"
                class="gallery-slide"
              >
                <div class="w-full h-[500px] overflow-hidden cursor-pointer rounded-lg">
                  <!-- Ubah dari h-80 ke h-[500px] -->
                  <img
                    :src="image.src"
                    :alt="`Gallery Image ${image.id}`"
                    class="w-full h-full object-cover"
                    loading="lazy"
                  />
                </div>
              </SwiperSlide>

              <!-- Custom Navigation Arrows -->
              <div class="navigation-wrapper">
                <button class="swiper-button-prev custom-nav-btn" @click.stop>
                  <mdicon name="chevron-left" size="24" />
                </button>
                <button class="swiper-button-next custom-nav-btn" @click.stop>
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
    <div
      class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/60 to-black/80"
    ></div>
  </section>
</template>

<style scoped>
/* Gallery swiper styles */
.gallery-swiper {
  width: 100% !important;
  height: 500px !important;
  margin: 0 auto;
}

.gallery-slide {
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #fff;
  cursor: pointer;
  overflow: hidden;
}

.navigation-wrapper {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none; /* Agar tidak menghalangi klik pada slide */
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
  pointer-events: auto; /* Mengaktifkan klik pada tombol */
}

.swiper-button-prev {
  left: 10px;
}

.swiper-button-next {
  right: 10px;
}

.custom-nav-btn:hover {
  background-color: rgba(255, 255, 255, 1);
  transform: translateY(-50%) scale(1.1);
}

/* Hide default navigation arrows */
:deep(.swiper-button-next::after),
:deep(.swiper-button-prev::after) {
  display: none;
}

/* Hapus style yang menyembunyikan default navigation */
/* :deep(.swiper-button-next),
:deep(.swiper-button-prev) {
  display: none;
} */

/* Responsive styles */
@media (max-width: 640px) {
  .custom-nav-btn {
    width: 32px;
    height: 32px;
  }

  .swiper-button-prev {
    left: 5px;
  }

  .swiper-button-next {
    right: 5px;
  }
}
/* ViewerJS Custom Styles */
:deep(.viewer-container) {
  z-index: 99999;
}

:deep(.viewer-canvas) {
  background-color: rgba(0, 0, 0, 0.9);
}

:deep(.viewer-footer),
:deep(.viewer-navbar) {
  background-color: rgba(0, 0, 0, 0.7);
}

:deep(.viewer-tooltip) {
  display: none;
}

/* Responsive styles */
@media (max-width: 640px) {
  .gallery-swiper {
    height: 400px !important; /* Ubah dari 300px ke 400px */
  }
}

@media (max-height: 700px) {
  .gallery-swiper {
    height: 350px !important; /* Ubah dari 280px ke 350px */
  }
}
</style>
