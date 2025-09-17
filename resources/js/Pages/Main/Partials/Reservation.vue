<script setup>
import { amplop, setAmplop } from "../Utils";
import { router, useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch } from "vue";

const imageLoaded = ref(false);
const isVisible = ref(false);
const formRef = ref(null);
const sectionRef = ref(null);

// Form setup
const form = useForm({
  name: "",
  guest: "",
  attendance: "1",
});

const messages = ref(null);

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

// Intersection Observer setup
const setupIntersectionObserver = () => {
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isVisible.value = true;
        }
      });
    },
    {
      threshold: 0.1,
      rootMargin: "0px"
    }
  );

  if (sectionRef.value) {
    observer.observe(sectionRef.value);
  }

  return observer;
};

// Form submission
const handleSubmit = async () => {
  messages.value = null;
  form.clearErrors();
  form.processing = true;

  try {
    const response = await axios.post(route("reservation.store"), {
      name: form.name,
      guest: form.guest,
      attendance: form.attendance,
    });
    
    form.reset();
    messages.value = response.data.message;
  } catch (error) {
    console.error("Error submitting greeting:", error);
    if (error.response?.data?.errors) {
      for (const key in error.response.data.errors) {
        form.setError(key, error.response.data.errors[key][0]);
      }
    }
  } finally {
    form.processing = false;
  }
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/greeting.webp");
    const observer = setupIntersectionObserver();
    return () => observer.disconnect();
  } catch (error) {
    console.error("Error loading image:", error);
  }
});
</script>

<template>
  <section
    ref="sectionRef"
    id="greeting"
    class="min-h-screen w-full flex items-start justify-center relative bg-cover bg-center bg-gray-200 z-[2] -mt-10"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/greeting.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
  >
    <div 
      class="w-full h-screen flex items-center justify-center z-[2] px-4 py-12 transition-transform duration-1000"
      :class="{ 'translate-y-20 opacity-0': !isVisible }"
    >
      <div class="w-full flex flex-col gap-12 p-4">
        <!-- Title -->
        <div 
          class="flex flex-col gap-0 transition-all duration-700 delay-100"
          :class="{ 'translate-y-20 opacity-0': !isVisible }"
        >
          <h2 class="font-wittgenstein text-center text-white text-2xl lg:text-3xl">
            Reservasi
          </h2>
        </div>

        <!-- Form -->
        <form
          class="flex flex-col gap-2 border rounded-lg border-white bg-white/90 w-full p-4 transition-all duration-700 delay-200"
          :class="{ 'translate-y-20 opacity-0': !isVisible }"
          @submit.prevent="handleSubmit"
        >
          <div class="flex flex-col gap-2">
            <!-- Nama Field -->
            <div class="flex flex-col gap-1">
              <label for="nama" class="font-wittgenstein text-xs lg:text-md text-gray-400">
                Nama
              </label>
              <input
                v-model="form.name"
                type="text"
                id="nama"
                :disabled="form.processing"
                class="form-input"
                :class="{ 'border-red-400': form.errors.name }"
                placeholder="Masukan nama anda"
              />
              <span v-if="form.errors.name" class="text-red-500 text-xs mt-1">
                {{ form.errors.name }}
              </span>
            </div>

            <!-- Kehadiran Field -->
            <div class="flex flex-col gap-1">
              <label for="kehadiran" class="font-wittgenstein text-xs lg:text-md text-gray-400">
                Kehadiran
              </label>
              <select
                v-model="form.attendance"
                id="kehadiran"
                :disabled="form.processing"
                class="form-input"
                :class="{ 'border-red-400': form.errors.attendance }"
              >
                <option value="" disabled>Pilih kehadiran anda</option>
                <option value="1">Hadir</option>
                <option value="2">Tidak Hadir</option>
                <option value="3">Ragu</option>
              </select>
              <span v-if="form.errors.attendance" class="text-red-500 text-xs mt-1">
                {{ form.errors.attendance }}
              </span>
            </div>

            <!-- Jumlah Tamu Field -->
            <div class="flex flex-col gap-1">
              <label for="jumlah_tamu" class="font-wittgenstein text-xs lg:text-md text-gray-400">
                Jumlah Tamu
              </label>
              <select
                v-model="form.guest"
                id="jumlah_tamu"
                :disabled="form.processing"
                class="form-input"
                :class="{ 'border-red-400': form.errors.guest }"
              >
                <option value="" disabled>Pilih jumlah tamu</option>
                <option v-for="index in 4" :key="index" :value="index">
                  {{ index }}
                </option>
              </select>
              <span v-if="form.errors.guest" class="text-red-500 text-xs mt-1">
                {{ form.errors.guest }}
              </span>
            </div>

            <!-- Submit Button -->
            <button
              :disabled="form.processing"
              class="submit-button"
              :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
            >
              {{ form.processing ? "Loading..." : "Submit" }}
            </button>
          </div>

          <!-- Success Message -->
          <div 
            v-if="messages" 
            class="text-xs text-green-800 mt-2 transition-all duration-300"
          >
            {{ messages }}
          </div>
        </form>

        <!-- Amplop Section -->
        <div 
          class="flex flex-col gap-4 transition-all duration-700 delay-300"
          :class="{ 'translate-y-20 opacity-0': !isVisible }"
        >
          <h6 class="font-wittgenstein text-center text-white text-xs lg:text-md tracking-wide">
            Kehadiran Anda merupakan hadiah terindah. Namun, apabila Anda hendak
            memberikan tanda kasih kepada kami, dapat melalui fitur di bawah ini.
          </h6>
          <div class="w-full flex items-center justify-center">
            <button
              @click="setAmplop(true)"
              class="amplop-button"
            >
              <mdicon name="email-heart-outline" height="20" width="20" />
              Amplop Digital
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="absolute inset-0 bg-gradient-to-t from-black/100 via-black/50 to-black/100"></div>
  </section>
</template>

<style scoped>
/* Base Input Styles */
.form-input {
  @apply transition-all duration-300 ease-in flex items-center justify-center text-xs h-9 lg:h-10 border placeholder:text-gray-400 placeholder:text-sm text-gray-400 border-gray-400 bg-gray-400/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px];
}

/* Button Styles */
.submit-button {
  @apply transition-all duration-300 border border-gray-400 bg-green-700/50 rounded-lg px-4 py-1 text-sm text-white hover:bg-green-900/20 hover:translate-y-[-2px] active:translate-y-0 mt-2 w-full;
}

.amplop-button {
  @apply flex items-center gap-2 bg-green-700/80 font-poly text-xs lg:text-sm w-fit uppercase rounded-lg text-white hover:bg-green-900/80 hover:translate-y-[-2px] active:translate-y-0 px-6 py-1;
}

/* Optimize transitions */
.transition-all {
  will-change: transform, opacity;
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Reduced motion */
@media (prefers-reduced-motion: reduce) {
  .transition-all {
    transition: none !important;
  }
  
  html {
    scroll-behavior: auto !important;
  }
}
</style>