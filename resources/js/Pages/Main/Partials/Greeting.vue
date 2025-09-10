<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch, nextTick } from "vue";
import gsap from 'gsap';
import ScrollTrigger from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const greetingRef = ref(null);
const formRef = ref(null);
const messageListRef = ref(null);
const titleRef = ref(null);

const props = defineProps({
  greeting: Object,
});

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
  message: "",
  attendance: "1",
});

const setupAnimations = () => {
  // Title Animation
  gsap.fromTo(titleRef.value,
    { y: 30, opacity: 0 },
    {
      y: 0,
      opacity: 1,
      duration: 1,
      ease: "power2.out",
      scrollTrigger: {
        trigger: greetingRef.value,
        start: "top center+=100",
        toggleActions: "play none none reverse"
      }
    }
  );

  // Form Animation
  gsap.fromTo(formRef.value,
    { y: 50, opacity: 0 },
    {
      y: 0,
      opacity: 1,
      duration: 1,
      delay: 0.3,
      ease: "power2.out",
      scrollTrigger: {
        trigger: greetingRef.value,
        start: "top center+=100",
        toggleActions: "play none none reverse"
      }
    }
  );

  // Message List Animation
  const messages = gsap.utils.toArray('.message-item');
  messages.forEach((message, index) => {
    gsap.fromTo(message,
      { opacity: 0, y: 20 },
      {
        opacity: 1,
        y: 0,
        duration: 0.5,
        delay: 0.1 * index,
        ease: "power2.out",
        scrollTrigger: {
          trigger: messageListRef.value,
          start: "top bottom-=100",
          toggleActions: "play none none none"
        }
      }
    );
  });
};

const handleSubmit = async () => {
  axios
    .post(route("greeting.store"), {
      name: form.name,
      message: form.message,
      attendance: form.attendance,
    })
    .then((response) => {
      // Animate new message
      gsap.fromTo('.message-item:first-child',
        { y: -20, opacity: 0 },
        {
          y: 0,
          opacity: 1,
          duration: 0.5,
          ease: "power2.out"
        }
      );
      
      form.reset();
      router.reload({
        only: ["greeting"],
      });
    })
    .catch((error) => {
      console.error("Error submitting greeting:", error);
      if (error.response?.data?.errors) {
        for (const key in error.response.data.errors) {
          form.setError(key, error.response.data.errors[key][0]);
        }
      }
    });
};

// Watch for greeting changes
watch(() => props.greeting, (newGreeting) => {
  if (newGreeting) {
    nextTick(() => {
      const messages = gsap.utils.toArray('.message-item');
      messages.forEach((message, index) => {
        gsap.fromTo(message,
          { opacity: 0, y: 20 },
          {
            opacity: 1,
            y: 0,
            duration: 0.5,
            delay: 0.1 * index,
            ease: "power2.out"
          }
        );
      });
    });
  }
}, { deep: true });

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
          <h2 class="font-wittgenstein text-center text-white text-3xl">
            Ucapan dan Doa
          </h2>
        </div>

        <form
          ref="formRef"
          class="flex flex-col gap-2 border rounded-lg border-white bg-black/10 w-full p-4 transition-all duration-300 hover:bg-black/20"
          @submit.prevent="handleSubmit"
        >
          <div class="flex flex-col gap-1">
            <label for="nama" class="text-sm font-wittgenstein text-white">Nama</label>
            <input
              type="text"
              v-model="form.name"
              :disabled="form.processing"
              id="nama"
              name="name"
              placeholder="Masukan nama anda"
              class="transition-all duration-300 ease-in flex items-center justify-center border placeholder:text-gray-400 placeholder:text-sm text-white border-white bg-white/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px]"
              :class="{ 'border-red-400': form.errors.name }"
            />
          </div>

          <div class="flex flex-col gap-1">
            <label for="message" class="text-sm font-wittgenstein text-white">Ucapan</label>
            <textarea
              v-model="form.message"
              :disabled="form.processing"
              id="message"
              name="message"
              placeholder="Masukan ucapan anda"
              class="transition-all duration-300 ease-in flex items-center justify-center border placeholder:text-gray-400 placeholder:text-sm text-white border-white bg-white/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px]"
              :class="{ 'border-red-400': form.errors.message }"
            />
          </div>

          <div class="flex flex-col gap-1">
            <label for="kehadiran" class="text-sm font-wittgenstein text-white">Kehadiran</label>
            <select
              name="kehadiran"
              id="kehadiran"
              :disabled="form.processing"
              class="transition-all duration-300 ease-in flex items-center justify-center border placeholder:text-gray-400 placeholder:text-sm text-white border-white bg-white/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px]"
              :class="{ 'border-red-400': form.errors.attendance }"
              v-model="form.attendance"
            >
              <option value="" disabled>Pilih kehadiran anda</option>
              <option value="1">Hadir</option>
              <option value="2">Tidak Hadir</option>
              <option value="3">Ragu</option>
            </select>
          </div>

          <div class="w-full mt-2">
            <button
              :disabled="form.processing"
              class="transition-all duration-300 border border-white bg-white/10 w-full rounded-lg px-4 py-2 text-white hover:bg-white/20 hover:translate-y-[-2px] active:translate-y-0"
              :class="{
                'opacity-50 cursor-not-allowed animate-pulse': form.processing,
              }"
            >
              {{ form.processing ? "Loading..." : "Submit" }}
            </button>
          </div>
        </form>

        <div ref="messageListRef" class="flex flex-col gap-2 mb-2">
          <h3 class="font-wittgenstein text-white">Ucapan yang telah dikirim</h3>
          <div class="overflow-auto w-full h-[14rem] border-l pl-4 pb-4 custom-scrollbar">
            <div
              v-for="(item, index) in greeting"
              :key="index"
              class="message-item flex flex-col gap-0 border-b border-white/20 pb-2 transition-all duration-300 hover:bg-white/5"
              :style="{ opacity: 1 }"
            >
              <div class="flex items-center gap-2 py-2">
                <h4 class="font-wittgenstein text-white text-md font-bold">{{ item.name }}</h4>
                <span
                  class="text-xs px-2 py-0.5 rounded-lg transition-all duration-300"
                  :class="{
                    'bg-green-600 text-white': item.attendance === 1,
                    'bg-red-600 text-white': item.attendance === 2,
                    'bg-yellow-400 text-black': item.attendance === 3,
                  }"
                >
                  {{ item.attendance_status }}
                </span>
              </div>
              <p class="font-wittgenstein text-white text-sm">{{ item.message }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/100 via-black/50 to-black/100"></div>
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

input:focus, textarea:focus, select:focus {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

button:not(:disabled):hover {
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Optimize animations performance */
* {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>