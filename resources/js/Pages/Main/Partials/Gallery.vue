<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import lightGallery from 'lightgallery';
import lgZoom from 'lightgallery/plugins/zoom';
import 'lightgallery/css/lightgallery.css';
import 'lightgallery/css/lg-zoom.css';
import { useI18n } from 'vue-i18n'; // Import useI18n

// Initialize i18n
const { t } = useI18n();

const imageLoaded = ref(false);
const swiperRef = ref(null);
let gallery = null;

// Kurangi jumlah gambar di slider
const slideImages = ref([
  { id: 1, src: '/assets/images/gallery/1.webp' },
  { id: 2, src: '/assets/images/gallery/2.webp' },
  { id: 3, src: '/assets/images/gallery/3.webp' },
  { id: 4, src: '/assets/images/gallery/4.webp' },
  { id: 5, src: '/assets/images/gallery/5.webp' },
  { id: 6, src: '/assets/images/gallery/6.webp' },
]);

// Semua gambar untuk gallery
const allImages = Array.from({ length: 40 }, (_, i) => ({
  id: i + 1,
  src: `/assets/images/gallery/${i + 1}.webp`,
}));

const swiperOptions = {
  modules: [Autoplay, Navigation],
  slidesPerView: 1,
  spaceBetween: 30,
  centeredSlides: true,
  autoplay: {
    delay: 3000,
    disableOnInteraction: true,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  loop: true,
};

const preloadImage = (url) => {
  return new Promise((resolve) => {
    const img = new Image();
    img.onload = () => {
      imageLoaded.value = true;
      resolve();
    };
    img.onerror = () => resolve();
    img.src = url;
  });
};

const showGallery = (index = 0) => {
  try {
    if (!gallery) {
      const container = document.createElement('div');
      container.id = 'gallery-container';
      document.body.appendChild(container);
      
      // Buat element untuk setiap gambar
      allImages.forEach(image => {
        const a = document.createElement('a');
        a.href = image.src;
        a.dataset.src = image.src;
        container.appendChild(a);
      });

      // Inisialisasi lightGallery
      gallery = lightGallery(container, {
        plugins: [lgZoom],
        speed: 500,
        download: false,
        counter: false,
        closeOnTap: true,
        hideBarsDelay: 3000,
        enableSwipe: true,
        enableDrag: true,
        swipeToClose: true,
        mobileSettings: {
          showCloseIcon: true,
          download: false
        }
      });

      // Cleanup saat gallery ditutup
      container.addEventListener('lgAfterClose', () => {
        gallery.destroy();
        gallery = null;
        container.remove();
      });
    }

    // Buka gallery pada index tertentu
    gallery.openGallery(index);

  } catch (error) {
    console.error('Error showing gallery:', error);
    if (gallery) {
      gallery.destroy();
      gallery = null;
    }
  }
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/gallery/1.webp");
  } catch (error) {
    console.error("Error loading gallery:", error);
  }
});

onUnmounted(() => {
  if (gallery) {
    gallery.destroy();
    gallery = null;
  }
});
</script>

<template>
  <section
    id="gallery"
    class="relative flex items-center justify-center bg-gray-200 z-[2]"
    :class="{ 'bg-cover bg-center': imageLoaded }"
    :style="{
      backgroundColor: 'transparent',  
    }"
  >
    <div class="relative w-full h-full flex items-center justify-center z-[2] px-4 py-12">
      <div class="h-full w-full flex flex-col gap-16">
        <!-- Header -->
        <div class="flex flex-col gap-2">
          <h1 class="text-4xl text-white text-center font-wittgenstein uppercase tracking-wider">
            {{ t('gallery.title', 'Our Gallery') }}
          </h1>
          <h6 class="text-white text-center font-wittgenstein tracking-widest uppercase">
            Hendra & Dinda
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
                    :alt="t('gallery.image_alt', 'Gallery Image') + ' ' + image.id"
                    class="w-full h-full object-cover"
                    loading="lazy"
                  />
                </div>
              </SwiperSlide>

              <div class="navigation-wrapper">
                <div class="swiper-button-prev custom-nav-btn">
                  <mdicon name="chevron-left" size="24" class="text-white" />
                </div>
                <div class="swiper-button-next custom-nav-btn">
                  <mdicon name="chevron-right" size="24" class="text-white" />
                </div>
              </div>
            </Swiper>
          </div>

          <p class="text-white text-center text-sm opacity-80">
            {{ t('gallery.tap_to_view', 'Tap image to view all photos') }}
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<style>
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
  inset: 0;
  pointer-events: none;
  z-index: 10;
}

.custom-nav-btn {
  background-color: rgba(255, 255, 255, 0.3);
  width: 40px;
  height: 40px;
  border-radius: 50%;
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  pointer-events: auto;
  display: grid;
  place-items: center;
}

.swiper-button-prev {
  left: 10px;
}

.swiper-button-next {
  right: 10px;
}

/* Hide default swiper arrows */
.swiper-button-prev::after,
.swiper-button-next::after {
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

/* Hide gallery container */
#gallery-container {
  display: none;
}
</style>
