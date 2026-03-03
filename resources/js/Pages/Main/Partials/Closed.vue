<script setup>
import { onMounted, ref, onBeforeUnmount } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import { useI18n } from "vue-i18n"; // Import useI18n

// Initialize i18n
const { t } = useI18n();

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);

const sectionRef = ref(null);
const headingRef = ref(null);
const namesRef = ref(null);
const withLoveRef = ref(null);
const lineRef = ref(null);
const currentImageIndex = ref(0);
const isLoading = ref(true);
const loadingError = ref(false);
const imagesLoaded = ref(0);

let slideInterval = null;

const imageUrls = [
  "/assets/images/raw-1208.jpg",
  "/assets/images/edit-34.jpg",
  "/assets/images/raw-15.jpg"
];

// Preload images with progressive loading strategy
const preloadImages = async () => {
  try {
    const loadImage = (url) => {
      return new Promise((resolve, reject) => {
        const img = new Image();

        img.onload = () => {
          imagesLoaded.value++;
          resolve(img);
        };

        img.onerror = () => {
          console.error(`Failed to load image: ${url}`);
          reject(new Error(`Failed to load image: ${url}`));
        };

        // Cache busting for development
        const cacheBuster =
          process.env.NODE_ENV === "development" ? `?t=${Date.now()}` : "";
        img.src = url + cacheBuster;
      });
    };

    // Load first image with priority
    await loadImage(imageUrls[0]);
    initializeBackgrounds(); // Start with the first image

    // Load remaining images
    const remainingImages = imageUrls.slice(1);
    await Promise.all(remainingImages.map((url) => loadImage(url)));

    isLoading.value = false;
  } catch (error) {
    console.error("Image loading failed:", error);
    loadingError.value = true;
    isLoading.value = false;
  }
};

// Initialize backgrounds with optimization
const initializeBackgrounds = () => {
  if (!sectionRef.value) return;

  const slideContainer = document.createElement("div");
  slideContainer.className = "absolute inset-0 overflow-hidden";

  imageUrls.forEach((url, index) => {
    const slide = document.createElement("div");
    slide.className = "slide absolute inset-0 bg-cover bg-center transform-gpu";
    slide.style.backgroundImage = `url(${url})`;
    slide.style.opacity = index === 0 ? "1" : "0";

    // Optimization for mobile
    if (window.innerWidth <= 768) {
      slide.style.transform = "translateZ(0)";
      slide.style.willChange = "opacity";
    }

    slideContainer.appendChild(slide);
  });

  sectionRef.value.insertBefore(slideContainer, sectionRef.value.firstChild);
  startSlideshow();
};

// Function for slideshow with optimization
const startSlideshow = () => {
  if (slideInterval) clearInterval(slideInterval);

  slideInterval = setInterval(() => {
    const nextIndex = (currentImageIndex.value + 1) % imageUrls.length;
    transitionToNextImage(nextIndex);
  }, 5000);
};

// Transition function with GSAP
const transitionToNextImage = (nextIndex) => {
  const slides = sectionRef.value.querySelectorAll(".slide");

  gsap.to(slides[currentImageIndex.value], {
    opacity: 0,
    duration: 1.5,
    ease: "power2.inOut",
  });

  gsap.to(slides[nextIndex], {
    opacity: 1,
    duration: 1.5,
    ease: "power2.inOut",
  });

  currentImageIndex.value = nextIndex;
};

onMounted(() => {
  // Start preloading images
  preloadImages();
  
  // Animation for the heading
  gsap.from(headingRef.value, {
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
  
  // Animation for the line
  gsap.fromTo(lineRef.value, 
    { width: 0 },
    {
      width: "100%",
      duration: 1.5,
      ease: "power2.inOut",
      delay: 0.3,
      scrollTrigger: {
        trigger: sectionRef.value,
        start: "top center",
        toggleActions: "play none none reverse",
      },
    }
  );
  
  // Animation for "With love"
  gsap.from(withLoveRef.value, {
    opacity: 0,
    y: 50,
    duration: 1.2,
    ease: "power2.out",
    delay: 0.6,
    scrollTrigger: {
      trigger: sectionRef.value,
      start: "top center",
      toggleActions: "play none none reverse",
    },
  });
  
  // Animation for names
  gsap.from(namesRef.value, {
    opacity: 0,
    y: 70,
    duration: 1.2,
    ease: "power2.out",
    delay: 0.6,
    scrollTrigger: {
      trigger: sectionRef.value,
      start: "top center",
      toggleActions: "play none none reverse",
    },
  });
});

// Clean up interval when component is unmounted
onBeforeUnmount(() => {
  if (slideInterval) {
    clearInterval(slideInterval);
  }
});
</script>

<template>
  <section
    ref="sectionRef"
    id="closed"
    class="min-h-screen flex flex-col items-center justify-between relative bg-cover bg-center -mt-20 z-[3] overflow-hidden"
  >
    <!-- Loading overlay -->
    <div v-if="isLoading" class="absolute inset-0 bg-black flex items-center justify-center z-50">
      <div class="text-white">{{ t('closed.loading', 'Loading...') }}</div>
    </div>
    
    <!-- Error message -->
    <div v-if="loadingError" class="absolute inset-0 bg-black/80 flex items-center justify-center z-50">
      <div class="text-white">{{ t('closed.loading_error', 'Failed to load images. Please refresh the page.') }}</div>
    </div>
    
    <div class="absolute inset-0 bg-black/30 z-[1]"></div>
    
    <!-- Top heading with line -->
    <div class="relative z-10 w-full pt-16 px-6 md:px-12">
      <div class="flex items-center">
        <h2 
          ref="headingRef"
          class="text-white text-xl md:text-2xl font-light tracking-widest uppercase"
        >
          {{ t('closed.heading', 'SEE YOU ON OUR BIG DAY') }}
        </h2>
        <div 
          ref="lineRef"
          class="ml-4 flex-grow h-[10px] bg-white"
        ></div>
      </div>
    </div>
    
    <!-- Bottom content -->
    <div class="relative z-10 w-full pb-16 px-6 md:px-12 flex justify-end">
      <div class="text-right">
        <p 
          ref="withLoveRef"
          class="text-white text-lg md:text-xl mb-2"
        >
          {{ t('closed.with_love', 'With love,') }}
        </p>
        <h1 
          ref="namesRef"
          class="text-white text-5xl md:text-7xl font-poly "
        >
          {{  t('closed.groom_name', 'Hendra') }}<br>{{ t('closed.bride_name', 'Dinda') }}
        </h1>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Font styling */
.font-serif {
  font-family: 'Times New Roman', serif;
  letter-spacing: 2px;
}

/* Mobile adjustments */
@media (max-width: 640px) {
  h1 {
    font-size: 2.5rem;
  }
  
  h2 {
    font-size: 1.1rem;
  }
}

/* Slideshow styles */
.slide {
  transition: opacity 1.5s ease;
  will-change: opacity;
  backface-visibility: hidden;
}
</style>
