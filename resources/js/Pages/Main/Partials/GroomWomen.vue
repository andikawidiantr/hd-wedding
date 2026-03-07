<script setup>
import { computed, onMounted, ref, onBeforeUnmount } from "vue";
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';
import { useI18n } from 'vue-i18n';

// Initialize i18n
const { locale } = useI18n();

const brideContent = {
  id: {
    title: 'Mempelai Wanita',
    name: 'dr. Ni Luh Putu Dinda Rahayu Dermana, S.Ked',
    description: 'Putri Pertama dari Bapak I Wayan Dermana Putra & Ibu Ns. Ni Wayan Sudianingsih, S.Kep'
  },
  en: {
    title: 'The Bride',
    name: 'dr. Ni Luh Putu Dinda Rahayu Dermana, S.Ked',
    description: 'First Daughter of Mr. I Wayan Dermana Putra & Mrs. Ns. Ni Wayan Sudianingsih, S.Kep'
  }
};

const brideCopy = computed(() => brideContent[locale.value] || brideContent.id);

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const groomWomenRef = ref(null);
const titleRef = ref(null);
const lineRef = ref(null);
const nameRef = ref(null);
const descRef = ref(null);
const socialRef = ref(null);
const animationTriggered = ref(false);
const currentImageIndex = ref(0);

// Array of image URLs
const imageUrls = [
  "/assets/images/bride_1.jpeg",
  "/assets/images/bride_2.JPG"
];

let slideInterval = null;

// Preload images
const preloadImages = async () => {
  try {
    // Load first image
    await preloadImage(imageUrls[0]);
    
    // Initialize slideshow
    initializeBackgrounds();
    
    // Load second image
    await preloadImage(imageUrls[1]);
  } catch (error) {
    console.error("Error loading images:", error);
  }
};

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

// Initialize backgrounds with optimization
const initializeBackgrounds = () => {
  if (!groomWomenRef.value) return;

  const slideContainer = document.createElement("div");
  slideContainer.className = "absolute inset-0 m-4 md:m-8 lg:m-12 overflow-hidden";

  imageUrls.forEach((url, index) => {
    const slide = document.createElement("div");
    slide.className = "slide absolute inset-0 bg-cover bg-center transform-gpu";
    slide.style.backgroundImage = `url(${url})`;
    slide.style.backgroundPosition = '50% 50%';
    slide.style.backgroundSize = 'cover';
    slide.style.backgroundRepeat = 'no-repeat';
    slide.style.opacity = index === 0 ? "1" : "0";

    slideContainer.appendChild(slide);
  });

  // Insert the slide container before any other children
  if (groomWomenRef.value.firstChild) {
    groomWomenRef.value.insertBefore(slideContainer, groomWomenRef.value.firstChild);
  } else {
    groomWomenRef.value.appendChild(slideContainer);
  }
};

// Function for slideshow with optimization
const startSlideshow = () => {
  if (slideInterval) return;

  slideInterval = setInterval(() => {
    const nextIndex = (currentImageIndex.value + 1) % imageUrls.length;
    transitionToNextImage(nextIndex);
  }, 4000);
};

const stopSlideshow = () => {
  if (slideInterval) {
    clearInterval(slideInterval);
    slideInterval = null;
  }
};

// Transition function with GSAP
const transitionToNextImage = (nextIndex) => {
  const slides = groomWomenRef.value.querySelectorAll(".slide");
  if (!slides || slides.length < 2) return;

  gsap.to(slides[currentImageIndex.value], {
    opacity: 0,
    duration: 1.2, // Durasi fade out
    ease: "power2.inOut",
  });

  gsap.to(slides[nextIndex], {
    opacity: 1,
    duration: 1.2, // Durasi fade in
    ease: "power2.inOut",
  });

  currentImageIndex.value = nextIndex;
};

onMounted(async () => {
  // Start preloading images
  await preloadImages();

  // Set initial states for content elements
  gsap.set(
    [titleRef.value, lineRef.value, nameRef.value, descRef.value, socialRef.value],
    {
      x: 50,
      opacity: 0,
    }
  );
    
  // Animasi setelah gambar dimuat
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: groomWomenRef.value,
      start: "top 80%",
      end: "bottom 20%",
      toggleActions: "play none none reverse",
      onToggle: (self) => {
        animationTriggered.value = self.isActive;
        if (self.isActive) {
          startSlideshow();
        } else {
          stopSlideshow();
        }
      }
    }
  });

  // Animasi sequence
  tl.to(titleRef.value, {
    x: 0,
    opacity: 1,
    duration: 0.8,
    ease: "power3.out"
  })
  .to(lineRef.value, {
    x: 0,
    opacity: 1,
    duration: 0.6,
    ease: "power3.out"
  }, "-=0.4")
  .to(nameRef.value, {
    x: 0,
    opacity: 1,
    duration: 0.8,
    ease: "power3.out"
  }, "-=0.4")
  .to(descRef.value, {
    x: 0,
    opacity: 1,
    duration: 0.8,
    ease: "power3.out"
  }, "-=0.4")
  .to(socialRef.value, {
    x: 0,
    opacity: 1,
    duration: 0.8,
    ease: "power3.out"
  }, "-=0.4");

  // Parallax effect pada background
  const slides = groomWomenRef.value.querySelectorAll(".slide");
  slides.forEach(slide => {
    gsap.to(slide, {
      backgroundPosition: "50% 30%",
      ease: "none",
      scrollTrigger: {
        trigger: groomWomenRef.value,
        start: "top top",
        end: "bottom top",
        scrub: true
      }
    });
  });
});

// Clean up interval when component is unmounted
onBeforeUnmount(() => {
  stopSlideshow();
});
</script>

<template>
  <section
    ref="groomWomenRef"
    id="groomWomen"
    class="min-h-screen flex items-start justify-center relative z-[2] mt-10 overflow-hidden py-4 md:py-8 lg:py-12 px-0"
    :style="{
      backgroundColor: 'transparent',
    }"
  >
    <!-- Content -->
    <div :key="locale" class="w-full h-screen flex items-end justify-end z-[2] px-4 py-12 relative"> <!-- Ubah justify-start menjadi justify-end -->
      <div class="flex flex-col gap-4 p-4 items-end"> <!-- Tambah items-end untuk align kanan -->
        <h2
          ref="titleRef"
          class="text-xl uppercase font-wittgenstein text-white drop-shadow-lg"
        >
          {{ brideCopy.title }}
        </h2>
        <div ref="lineRef" class="border-t w-48 border-white"></div>
        <h1 ref="nameRef" class="text-3xl font-wittgenstein text-white">
          {{ brideCopy.name }}
        </h1>
        <p ref="descRef" class="font-wittgenstein text-white text-md text-right">
          {{ brideCopy.description }}
        </p>
        <a  
          ref="socialRef"  
          href="https://www.instagram.com/dindadermanaa_"  
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
    <div 
      class="absolute inset-0 m-4 md:m-8 lg:m-12 bg-black/10 transition-opacity duration-500 z-[1]"
    ></div>
  </section>
</template>

<style scoped>
.transition-all {
  transition: all 0.3s ease-in-out;
}

.slide {
  transition: opacity 1.2s ease;
  will-change: opacity;
  backface-visibility: hidden;
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
