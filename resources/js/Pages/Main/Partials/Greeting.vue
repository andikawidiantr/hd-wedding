<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { onMounted, ref, computed, watch } from "vue"; 
import moment from "moment";
import { useI18n } from 'vue-i18n'; // Import useI18n composable

// Initialize i18n
const { t, locale } = useI18n();

const imageLoaded = ref(false);
const isVisible = ref(false);
const sectionRef = ref(null);
const wishesImageLoaded = ref(false);

const props = defineProps({
  greeting: Object,
});

// Set moment locale based on current i18n locale
const updateMomentLocale = () => {
  moment.locale(locale.value);
};

// Watch for locale changes to update moment
watch(locale, () => {
  updateMomentLocale();
});

// Form setup
const form = useForm({
  name: "",
  message: "",
});

// Image preload
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

// Preload wishes image
const preloadWishesImage = (url) => {
  return new Promise((resolve, reject) => {
    const img = new Image();
    img.src = url;
    img.onload = () => {
      wishesImageLoaded.value = true;
      resolve();
    };
    img.onerror = reject;
  });
};

// Simple Intersection Observer
const setupIntersectionObserver = () => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isVisible.value = true;
        }
      });
    },
    { threshold: 0.1 }
  );

  if (sectionRef.value) {
    observer.observe(sectionRef.value);
  }

  return observer;
};

// Form submission
const handleSubmit = async () => {
  if (form.processing) return;

  try {
    form.processing = true;
    await axios.post(route("greeting.store"), {
      name: form.name,
      message: form.message,
    });

    form.reset();
    await router.reload({ only: ["greeting"] });
  } catch (error) {
    if (error.response?.data?.errors) {
      for (const key in error.response.data.errors) {
        form.setError(key, error.response.data.errors[key][0]);
      }
    }
  } finally {
    form.processing = false;
  }
};

// Responsive height
const isSmallScreen = computed(() => {
  if (typeof window !== 'undefined') {
    return window.innerHeight <= 895;
  }
  return false;
});

onMounted(async () => {
  try {
    // Set moment locale
    updateMomentLocale();
    
    await Promise.all([
      preloadImage("/assets/images/greeting.webp"),
      preloadWishesImage("/assets/images/743.JPG") // Replace with your actual image path
    ]);
    
    const observer = setupIntersectionObserver();
    
    // Simple resize listener
    const handleResize = () => {
      isSmallScreen.value = window.innerHeight <= 700;
    };
    window.addEventListener('resize', handleResize);
    
    return () => {
      observer.disconnect();
      window.removeEventListener('resize', handleResize);
    };
  } catch (error) {
    console.error("Error loading images:", error);
  }
});
</script>

<template>
  <section
    ref="sectionRef"
    id="greeting"
    class="min-h-[120vh] w-full flex items-start justify-center relative bg-cover bg-center bg-gray-200 z-[2] -mt-10 pt-10"
    :style="{
      backgroundColor: 'transparent',  
    }"
  >
    <div class="w-full h-screen flex items-center justify-center z-[2] px-4">
      <div 
        class="w-full flex flex-col justify-start gap-4 h-full p-4 transition-all duration-700"
        :class="{ 'translate-y-20 opacity-0': !isVisible }"
      >
        <!-- Title with Image -->
        <div class="w-full flex flex-col gap-6">
          <!-- Image above wishes -->
          <div class="w-full flex justify-center mb-2">
            <div class="w-full max-w-md h-40 overflow-hidden shadow-lg">
              <img 
                src="/assets/images/743.JPG" 
                alt="Wedding Wishes" 
                class="w-full h-full object-cover transition-opacity duration-500"
                :class="{ 'opacity-0': !wishesImageLoaded, 'opacity-100': wishesImageLoaded }"
                @load="wishesImageLoaded = true"
              />
            </div>
          </div>
          
          <div class="flex items-center justify-center w-full my-6">
            <div class="flex-grow border-t border-white mr-4"></div>
            <h2 class="font-serif text-white text-4xl">
              {{ t('greeting') }} 
            </h2>
          </div>
          
          <p class="text-center text-white text-sm pb-3">
            {{ t('please_give_wishes', 'Please, give us your best wishes and prayers.') }}
          </p>
          
          <!-- Form -->
          <form
            class="flex flex-col gap-2 border rounded-lg bg-black/40 w-full p-4 mb-5"
            @submit.prevent="handleSubmit"
          >
            <!-- Name Input -->
            <div class="flex flex-col gap-1">
              <label for="nama" class="text-xs lg:text-sm font-wittgenstein text-white">
                {{ t('name') }}
              </label>
              <input
                v-model="form.name"
                type="text"
                id="nama"
                :disabled="form.processing"
                :placeholder="t('enter_your_name', 'Enter your name')"
                class="input-style"
                :class="{ 'border-red-400': form.errors.name }"
              />
            </div>

            <!-- Message Input -->
            <div class="flex flex-col gap-1">
              <label for="message" class="text-sm font-wittgenstein text-white">
                {{ t('message') }}
              </label>
              <textarea
                v-model="form.message"
                id="message"
                :disabled="form.processing"
                :placeholder="t('give_your_wish', 'Give your wish')"
                class="input-style"
                :class="{ 'border-red-400': form.errors.message }"
              />
            </div>

            <!-- Submit Button -->
            <button
              :disabled="form.processing"
              class="submit-button"
              :class="{ 'opacity-50': form.processing }"
            >
              <span v-if="form.processing" class="inline-flex items-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ t('loading', 'Loading...') }}
              </span>
              <span v-else>{{ t('submit') }}</span>
            </button>
          </form>
        </div>

        <!-- Message List -->
        <div
          class="flex flex-col gap-2 border rounded-lg bg-black/40"
          :class="[isSmallScreen ? 'h-[22rem]' : 'h-[32rem]']" 
        >
          <h3 class="font-wittgenstein text-white px-4 py-4">
            {{ t('the_wishes', 'The Wish') }}
          </h3>
          <div
            class="overflow-auto w-full custom-scrollbar px-4 pb-4"
            :class="[isSmallScreen ? 'max-h-[14rem]' : 'max-h-[20rem]']"
          >
            <template v-if="!greeting?.length">
              <p class="text-xs text-gray-500 text-start mt-4">
                {{ t('no_greetings_yet', 'Belum ada ucapan yang dikirim.') }}
              </p>
            </template>
            
            <div
              v-for="(item, index) in greeting"
              :key="item.id || index"
              class="message-item"
            >
              <div class="flex w-full items-center gap-2 py-2">
                <div class="text-rose-400">
                  <mdicon
                    name="heart-circle-outline"
                    :width="isSmallScreen ? 32 : 40"
                  />
                </div>
                <div class="w-full flex flex-col gap-1">
                  <div class="flex justify-between w-full items-center gap-4">
                    <h4 class="font-wittgenstein text-white text-sm font-bold truncate max-w-[200px]">
                      {{ item.name }}
                    </h4>
                    <span class="text-xs text-white shrink-0">
                      {{ moment(item.created_at).fromNow() }}
                    </span>
                  </div>
                  <p class="font-wittgenstein text-white text-xs">
                    {{ item.message }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Language Switcher -->
        <!-- <div class="language-switcher mt-4 flex justify-center">
          <button 
            @click="locale = 'id'" 
            :class="{ 'active-lang': locale === 'id' }"
            class="lang-button"
          >
            ID
          </button>
          <span class="divider">|</span>
          <button 
            @click="locale = 'en'" 
            :class="{ 'active-lang': locale === 'en' }"
            class="lang-button"
          >
            EN
          </button>
        </div> -->
      </div>
    </div>
  </section>
</template>

<style scoped>
.input-style {
  @apply transition-all duration-300 ease-in flex text-xs h-9 lg:h-10 items-center justify-center border placeholder:text-gray-400 placeholder:text-sm text-white border-white bg-gray-400/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px];
}

.submit-button {
  @apply transition-all duration-300 border border-white bg-white/50 w-full rounded-lg px-4 py-1 text-sm text-white hover:bg-white/20 mt-2;
}

.message-item {
  @apply w-full flex flex-col gap-0 border-b border-white/20 pb-2 hover:bg-white/5;
}

.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: rgba(54, 54, 54, 0.3) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(54, 54, 54, 0.3);
  border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

/* Language switcher styles */
.language-switcher {
  background-color: rgba(0, 0, 0, 0.4);
  border-radius: 20px;
  padding: 5px 10px;
  display: inline-flex;
  align-items: center;
}

.lang-button {
  background: none;
  border: none;
  color: white;
  padding: 5px 8px;
  cursor: pointer;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  transition: all 0.3s ease;
}

.active-lang {
  font-weight: bold;
  color: gold;
}

.divider {
  color: rgba(255, 255, 255, 0.5);
  margin: 0 2px;
}

@media (prefers-reduced-motion: reduce) {
  * {
    transition: none !important;
  }
}
</style>
