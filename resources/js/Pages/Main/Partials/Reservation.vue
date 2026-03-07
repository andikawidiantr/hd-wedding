<script setup>
import { amplop, setAmplop } from "../Utils";
import { router, useForm } from "@inertiajs/vue3";
import { computed, onMounted, ref, watch } from "vue";
import { useI18n } from 'vue-i18n'; // Import useI18n

// Initialize i18n
const { locale } = useI18n();

const reservationContent = {
  id: {
    title: 'RSVP',
    subtitle: 'Silakan isi konfirmasi kehadiran di bawah ini.',
    name: 'Nama',
    namePlaceholder: 'Nama...',
    attendance: 'Konfirmasi Kehadiran',
    select: 'Pilih...',
    attending: 'Ya, saya akan hadir',
    notAttending: 'Tidak, saya tidak dapat hadir',
    guests: 'Jumlah Orang',
    loading: 'Memuat...',
    submit: 'Kirim'
  },
  en: {
    title: 'RSVP',
    subtitle: 'Please, fill confirmation of attendance below.',
    name: 'Name',
    namePlaceholder: 'Name...',
    attendance: 'Confirmation of Attendance',
    select: 'Select...',
    attending: 'Yes, I will attend',
    notAttending: 'No, I am unable to attend',
    guests: 'Number of Person(s)',
    loading: 'Loading...',
    submit: 'Submit'
  }
};

const reservationCopy = computed(() => reservationContent[locale.value] || reservationContent.id);

const imageLoaded = ref(false);
const isVisible = ref(false);
const formRef = ref(null);
const sectionRef = ref(null);

// Form setup
const form = useForm({
  name: "",
  guest: "",
  attendance: "",
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
    await preloadImage("/assets/images/edit-26.jpg");
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
    class="w-full flex items-start justify-center relative bg-cover bg-center bg-no-repeat z-[2] pb-10"
    :style="{  
      backgroundColor: 'transparent',  
    }" 
  >
    <div
      :key="locale"
      class="w-full flex flex-col items-center justify-center z-[2] px-4 py-12 transition-transform duration-1000 pt-16"
      :class="{ 'translate-y-20 opacity-0': !isVisible }"
    >
      <div class="w-full max-w-md flex flex-col gap-6 p-4">
        <!-- Title -->
        <div 
          class="flex flex-col gap-0 transition-all duration-700 delay-100"
          :class="{ 'translate-y-20 opacity-0': !isVisible }"
        >
          <div class="flex items-center justify-center w-full mb-12">
            <h2 class="font-serif text-white text-4xl">
              {{ reservationCopy.title }}
            </h2>
            <div class="flex-grow border-t border-white ml-2"></div>
          </div>
          <p class="text-center text-white text-sm mb-8">
            {{ reservationCopy.subtitle }}
          </p>
        </div>

        <!-- Form -->
        <form
          class="flex flex-col gap-5 w-full transition-all duration-700 delay-200"
          :class="{ 'translate-y-20 opacity-0': !isVisible }"
          @submit.prevent="handleSubmit"
        >
          <!-- Nama Field -->
          <div class="flex flex-col gap-2">
            <label for="nama" class="text-white text-sm">
              {{ reservationCopy.name }}
            </label>
            <input
              v-model="form.name"
              type="text"
              id="nama"
              :disabled="form.processing"
              class="form-input white-placeholder"
              :class="{ 'border-red-400': form.errors.name }"
              :placeholder="reservationCopy.namePlaceholder"
            />
            <span v-if="form.errors.name" class="text-red-500 text-xs mt-1">
              {{ form.errors.name }}
            </span>
          </div>

          <!-- Kehadiran Field -->
          <div class="flex flex-col gap-2">
            <label for="kehadiran" class="text-white text-sm flex">
              {{ reservationCopy.attendance }} <span class="text-red-500 ml-1">*</span>
            </label>
            <select
              v-model="form.attendance"
              id="kehadiran"
              :disabled="form.processing"
              class="form-input"
              :class="{ 'border-red-400': form.errors.attendance }"
            >
              <option value="" disabled>{{ reservationCopy.select }}</option>
              <option value="1">{{ reservationCopy.attending }}</option>
              <option value="2">{{ reservationCopy.notAttending }}</option>
            </select>
            <span v-if="form.errors.attendance" class="text-red-500 text-xs mt-1">
              {{ form.errors.attendance }}
            </span>
          </div>

          <!-- Jumlah Tamu Field -->
          <div class="flex flex-col gap-2">
            <label for="jumlah_tamu" class="text-white text-sm">
              {{ reservationCopy.guests }}
            </label>
            <select
              v-model="form.guest"
              id="jumlah_tamu"
              :disabled="form.processing"
              class="form-input"
              :class="{ 'border-red-400': form.errors.guest }"
            >
              <option value="" disabled>{{ reservationCopy.select }}</option>
              <option v-for="index in 4" :key="index" :value="index">
                {{ index }}
              </option>
            </select>
            <span v-if="form.errors.guest" class="text-red-500 text-xs mt-1">
              {{ form.errors.guest }}
            </span>
          </div>

          <!-- Submit Button -->
          <div class="flex justify-center mt-4">
            <button
              :disabled="form.processing"
              class="submit-button"
              :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
            >
              {{ form.processing ? reservationCopy.loading : reservationCopy.submit }}
            </button>
          </div>

          <!-- Success Message -->
          <div 
            v-if="messages" 
            class="text-xs text-green-400 mt-2 text-center transition-all duration-300"
          >
            {{ messages }}
          </div>
        </form>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Base Input Styles */
.form-input {
  @apply transition-all duration-300 ease-in w-full h-12 px-3 border border-white bg-transparent text-white rounded focus:outline-none focus:border-white;
}

/* Button Styles */
.submit-button {
  @apply transition-all duration-300 border border-white bg-white/50 rounded px-8 py-2 text-sm text-white hover:bg-white/20 active:bg-white/20 mt-2;
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

select {
  appearance: none;
  background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 16px;
}
/* Target the placeholder text in different browsers */
.white-placeholder::placeholder {
  color: white;
}
</style>
