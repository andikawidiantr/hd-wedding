<script setup>
import { computed, onMounted, ref, onUnmounted, watch } from "vue";
import { useI18n } from 'vue-i18n'; // Import useI18n

// Initialize i18n
const { t, locale } = useI18n({ useScope: 'global' });

// Force vnode refresh when locale changes to prevent stale text in this section.
const localeRenderKey = ref(0);

watch(locale, () => {
  localeRenderKey.value += 1;
});

const regVedaCopy = computed(() => ({
  title: t('regveda.title') || 'Ṛg Veda X.85.47',
  sanskritText: t('regveda.sanskrit_text') || '"Tvāṁ patīṁ gr̥hṇāmi, tvamasi me sahadharmacārīni."',
  translation: t('regveda.translation') || 'Aku menerima engkau sebagai suamiku/istriku; engkau adalah sahabatku dalam Dharma. Bersama kita akan hidup, mencintai, dan membangun kebahagiaan.'
}));

const regVedaRef = ref(null);
const isVisible = ref(false);
let observer = null;

const setupIntersectionObserver = () => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isVisible.value = true;
        } else {
          isVisible.value = false;
        }
      });
    },
    {
      threshold: 0.2
    }
  );

  if (regVedaRef.value) {
    observer.observe(regVedaRef.value);
  }
};

onMounted(() => {
  setupIntersectionObserver();
});

onUnmounted(() => {
  if (observer) {
    observer.disconnect();
  }
});
</script>

<template>
  <section
    ref="regVedaRef"
    id="regVeda"
    class="min-h-screen flex items-center justify-center relative bg-cover bg-center -mt-20 z-[3]"
  >
    <!-- Background Gradient Overlay -->  
    <!-- <div  
      class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/50 to-black/70 z-1"  
    ></div> -->
    <!-- Reset Container Style -->
    <div 
      class="container mx-auto px-12"
      style="translate: none; rotate: none; scale: none; transform: none;"
    >
      <div :key="localeRenderKey" class="flex flex-col items-center justify-center">
        <!-- Title -->
        <div class="overflow-hidden mb-4">
          <h1
            class="font-wittgenstein text-2xl md:text-3xl text-white text-center select-none animate-item"
            :class="{ 'show': isVisible }"
          >
            {{ regVedaCopy.title }}
          </h1>
        </div>

        <!-- Sanskrit Text -->
        <div class="overflow-hidden mb-8">
          <p
            class="font-wittgenstein text-center text-white max-w-2xl text-lg md:text-xl select-none animate-item delay-200"
            :class="{ 'show': isVisible }"
          >
            {{ regVedaCopy.sanskritText }}
          </p>
        </div>

        <!-- Translation -->
        <div class="overflow-hidden">
          <p
            class="font-wittgenstein text-center text-white max-w-2xl text-base md:text-lg leading-relaxed select-none animate-item delay-400"
            :class="{ 'show': isVisible }"
          >
            {{ regVedaCopy.translation }}
          </p>
        </div>
      </div>
    </div>

    <!-- Decoration -->
    <!-- <div 
      class="absolute bottom-[-2rem] left-1/2 -translate-x-1/2 z-[4]"
      style="transform: translateX(-50%); translate: none;"
    >
      <div 
        class="p-4 animate-fade"
        :class="{ 'show': isVisible }"
      >
        <img
          src="/assets/images/flower.png"
          alt="Decoration"
          class="w-32 h-32 lg:w-48 lg:h-48 object-contain transition-transform duration-300 hover:scale-110"
        />
      </div>
    </div> -->
  </section>
</template>

<style scoped>
/* Reset any transform on container */
.container {
  transform: none !important;
  translate: none !important;
  rotate: none !important;
  scale: none !important;
}

/* Base animation setup */
.animate-item {
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-item.show {
  opacity: 1;
  transform: translateY(0);
}

.animate-fade {
  opacity: 0;
  transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-fade.show {
  opacity: 1;
}

/* Delays */
.delay-200 {
  transition-delay: 0.2s;
}

.delay-400 {
  transition-delay: 0.4s;
}

/* Performance optimizations */
.animate-item {
  will-change: transform, opacity;
  backface-visibility: hidden;
}

/* Responsive adjustments */
@media (max-width: 640px) {
  .container {
    padding: 2rem;
  }
}

</style>
