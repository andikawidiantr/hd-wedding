<script setup>
import { amplop, setAmplop } from "../Utils";
import { router, useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch, nextTick } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const greetingRef = ref(null);
const formRef = ref(null);
const messageListRef = ref(null);
const titleRef = ref(null);

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

const form = useForm({
  name: "",
  guest: "",
  attendance: "1",
});

const messages = ref(null);

const setupAnimations = () => {
  // Timeline untuk animasi berurutan
  const tl = gsap.timeline({
    scrollTrigger: {
      trigger: greetingRef.value,
      start: "top center+=100",
      toggleActions: "play none none reverse",
    },
  });

  // Title Animation
  tl.fromTo(
    titleRef.value,
    {
      y: 30,
      opacity: 0,
    },
    {
      y: 0,
      opacity: 1,
      duration: 0.8,
      ease: "power3.out",
    }
  );

  // Form Animation dengan stagger effect
  tl.fromTo(
    [
      ".nama-field",
      ".kehadiran-field",
      ".tamu-field",
      ".submit-button",
      ".amplop-section",
    ],
    {
      y: 40,
      opacity: 0,
    },
    {
      y: 0,
      opacity: 1,
      duration: 0.5,
      stagger: 0.1,
      ease: "power2.out",
    },
    "-=0.4"
  );
};

const handleSubmit = async () => {
  messages.value = null;
  form.clearErrors();
  form.processing = true;

  axios
    .post(route("reservation.store"), {
      name: form.name,
      guest: form.guest,
      attendance: form.attendance,
    })
    .then((response) => {
      form.reset();
      messages.value = response.data.message;
      form.processing = false;
    })
    .catch((error) => {
      console.error("Error submitting greeting:", error);
      if (error.response?.data?.errors) {
        for (const key in error.response.data.errors) {
          form.setError(key, error.response.data.errors[key][0]);
        }
      }
      form.processing = false;
    });
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/greeting.webp");
    setupAnimations();
  } catch (error) {
    console.error("Error loading image:", error);
  }
});
</script>

<template>
  <section
    ref="greetingRef"
    id="greeting"
    class="min-h-screen w-full flex items-start justify-center relative bg-cover bg-center bg-gray-200 z-[2] -mt-10"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/greeting.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
  >
    <div class="w-full h-screen flex items-center justify-center z-[2] px-4 py-12">
      <div class="w-full flex flex-col gap-12 p-4">
        <div ref="titleRef" class="flex flex-col gap-0">
          <h2 class="font-wittgenstein text-center text-white text-2xl lg:text-3xl">Reservasi</h2>
        </div>

        <form
          ref="formRef"
          class="flex flex-col gap-2 border rounded-lg border-white bg-white/90 w-full p-4 transition-all duration-300"
          @submit.prevent="handleSubmit"
        >
          <!-- Nama Field -->
          <div class="flex flex-col gap-1 nama-field">
            <label for="nama" class=" font-wittgenstein text-xs lg:text-md text-gray-400">Nama</label>
            <input
              type="text"
              v-model="form.name"
              :disabled="form.processing"
              id="nama"
              name="name"
              placeholder="Masukan nama anda"
              class="transition-all duration-300 ease-in flex items-center justify-center text-xs h-9  lg:h-10 border placeholder:text-gray-400 placeholder:text-sm text-gray-400 border-gray-400 bg-gray-400/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px]"
              :class="{ 'border-red-400': form.errors.name }"
            />
          </div>

          <!-- Kehadiran Field -->
          <div class="flex flex-col gap-1 kehadiran-field">
            <label for="kehadiran" class="font-wittgenstein text-xs lg:text-md text-gray-400">
              Kehadiran
            </label>
            <select
              name="kehadiran"
              id="kehadiran"
              :disabled="form.processing"
              class="transition-all duration-300 ease-in flex items-center justify-center text-xs h-9  lg:h-10 border placeholder:text-gray-400 placeholder:text-sm text-gray-400 border-gray-400 bg-gray-400/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px]"
              :class="{ 'border-red-400': form.errors.attendance }"
              v-model="form.attendance"
            >
              <option value="" disabled>Pilih kehadiran anda</option>
              <option value="1">Hadir</option>
              <option value="2">Tidak Hadir</option>
              <option value="3">Ragu</option>
            </select>
          </div>

          <!-- Jumlah Tamu Field -->
          <div class="flex flex-col gap-1 tamu-field">
            <label for="jumlah_tamu" class=" font-wittgenstein text-xs lg:text-md text-gray-400">
              Jumlah Tamu
            </label>
            <select
              name="jumlah_tamu"
              id="jumlah_tamu"
              :disabled="form.processing"
              class="transition-all duration-300 ease-in flex items-center justify-center  text-xs h-9  lg:h-10 border placeholder:text-gray-400 placeholder:text-sm text-gray-400 border-gray-400 bg-gray-400/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px]"
              :class="{ 'border-red-400': form.errors.guest }"
              v-model="form.guest"
            >
              <option value="" disabled>Pilih jumlah tamu</option>
              <template v-for="index in 4" :key="index">
                <option :value="index">{{ index }}</option>
              </template>
            </select>
          </div>
          <div v-show="messages">
            <h6 class="text-xs message-alert text-green-800">{{ messages }}</h6>
          </div>
          <!-- Submit Button -->
          <div class="w-full mt-2 submit-button">
            <button
              :disabled="form.processing"
              class="transition-all duration-300 border border-gray-400 bg-green-700/50 w-full rounded-lg px-4 py-1 text-sm text-white hover:bg-primary-900/20 hover:translate-y-[-2px] active:translate-y-0"
              :class="{
                'opacity-50 cursor-not-allowed animate-pulse': form.processing,
              }"
            >
              {{ form.processing ? "Loading..." : "Submit" }}
            </button>
          </div>
        </form>

        <!-- Amplop Section -->
        <div class="flex flex-col gap-4 amplop-section">
          <h6 class="font-wittgenstein text-center text-white text-xs lg:text-md tracking-wide">
            Kehadiran Anda merupakan hadiah terindah. Namun, apabila Anda hendak
            memberikan tanda kasih kepada kami, dapat melalui fitur di bawah ini.
          </h6>
          <div class="w-full flex items-center justify-center">
            <button
              @click="setAmplop(true)"
              class="flex items-center gap-2 bg-green-700/80 font-poly text-xs lg:text-sm w-fit uppercase rounded-lg text-white hover:bg-primary-900/80 hover:translate-y-[-2px] active:translate-y-0 px-6 py-1"
            >
              <mdicon name="email-heart-outline" height="20" width="20" />
              Amplop Digital
            </button>
          </div>
        </div>
      </div>
    </div>

    <div
      class="absolute inset-0 bg-gradient-to-t from-black/100 via-black/50 to-black/100"
    ></div>
  </section>
</template>

<style scoped>
.message-item {
  transform: translateZ(0);
  backface-visibility: hidden;
  will-change: opacity, transform;
  opacity: 1;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.message-item {
  animation: fadeInUp 0.5s ease forwards;
}

.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.3);
  border-radius: 3px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}

input:focus,
textarea:focus,
select:focus {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

button:not(:disabled):hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Optimize animations performance */
* {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  transform-style: preserve-3d;
  backface-visibility: hidden;
}

/* Ensure smooth animations */
.nama-field,
.kehadiran-field,
.tamu-field,
.submit-button,
.amplop-section {
  will-change: transform, opacity;
}
/* Dalam <style scoped> */
.message-alert {
  transform-origin: top center;
  will-change: transform, opacity;
  backface-visibility: hidden;
}

@keyframes messageSlideIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.message-alert-enter-active {
  animation: messageSlideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
}

.message-alert-leave-active {
  animation: messageSlideIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) reverse;
}
</style>
