<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { onMounted, ref, watch, nextTick } from "vue";
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import moment from "moment";

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const greetingRef = ref(null);
const formRef = ref(null);
const messageListRef = ref(null);
const titleRef = ref(null);
const latestMessage = ref(null);

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
});

const setupAnimations = () => {
  // Title Animation
  gsap.fromTo(
    titleRef.value,
    { 
      y: 20, 
      opacity: 0 
    },
    {
      y: 0,
      opacity: 1,
      duration: 0.6,
      ease: "power2.out",
      scrollTrigger: {
        trigger: titleRef.value,
        start: "top center+=100",
        toggleActions: "play none none reverse"
      }
    }
  );

  // Form Animation
  gsap.fromTo(
    formRef.value,
    { 
      y: 20, 
      opacity: 0 
    },
    {
      y: 0,
      opacity: 1,
      duration: 0.6,
      delay: 0.2,
      ease: "power2.out",
      scrollTrigger: {
        trigger: formRef.value,
        start: "top center+=100",
        toggleActions: "play none none reverse"
      }
    }
  );

  // Message List Animation
  gsap.fromTo(
    messageListRef.value,
    {
      y: 20,
      opacity: 0
    },
    {
      y: 0,
      opacity: 1,
      duration: 0.6,
      delay: 0.4,
      ease: "power2.out",
      scrollTrigger: {
        trigger: messageListRef.value,
        start: "top bottom-=100",
        toggleActions: "play none none none"
      }
    }
  );
};

const animateNewMessage = (element) => {
  gsap.fromTo(
    element,
    {
      height: 0,
      opacity: 0,
      y: -20,
      scale: 0.95
    },
    {
      height: "auto",
      opacity: 1,
      y: 0,
      scale: 1,
      duration: 0.4,
      ease: "back.out(1.7)",
      clearProps: "all"
    }
  );
};

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

    // Animate new message
    nextTick(() => {
      const firstMessage = document.querySelector(".message-item:first-child");
      if (firstMessage) {
        animateNewMessage(firstMessage);
      }
    });

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

// Watch for new messages
watch(
  () => props.greeting,
  (newGreeting, oldGreeting) => {
    if (newGreeting && oldGreeting) {
      if (newGreeting.length > oldGreeting.length) {
        nextTick(() => {
          const newMessage = document.querySelector(".message-item:first-child");
          if (newMessage) {
            animateNewMessage(newMessage);
          }
        });
      }
    }
  },
  { deep: true }
);

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
      backgroundImage: imageLoaded ? 'url(/assets/images/gift.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
  >
    <div class="w-full h-screen flex items-center justify-center z-[2] px-4">
      <div class="w-full flex flex-col justify-start gap-4 h-full p-4">
        <div class="w-full flex flex-col gap-12">
          <div ref="titleRef" class="flex flex-col gap-0">
            <h2 class="font-wittgenstein text-center text-white text-3xl">
              Ucapan dan Doa
            </h2>
          </div>

          <form
            ref="formRef"
            class="flex flex-col gap-2 border rounded-lg border-white bg-white/90 w-full p-4 transition-all duration-300"
            @submit.prevent="handleSubmit"
          >
            <div class="flex flex-col gap-1">
              <label for="nama" class="text-sm font-wittgenstein text-gray-400">Nama</label>
              <input
                type="text"
                v-model="form.name"
                :disabled="form.processing"
                id="nama"
                name="name"
                placeholder="Masukan nama anda"
                class="transition-all duration-300 ease-in flex text-xs items-center justify-center border placeholder:text-gray-400 placeholder:text-sm text-gray-400 border-gray-400 bg-gray-400/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px]"
                :class="{ 'border-red-400': form.errors.name }"
              />
            </div>

            <div class="flex flex-col gap-1">
              <label for="message" class="text-sm font-wittgenstein text-gray-400">Ucapan</label>
              <textarea
                v-model="form.message"
                :disabled="form.processing"
                id="message"
                name="message"
                placeholder="Masukan ucapan anda"
                class="transition-all duration-300 ease-in text-xs flex items-center justify-center border placeholder:text-gray-400 placeholder:text-sm text-gray-400 border-gray-400 bg-gray-400/10 rounded-lg ring-0 focus:ring-0 focus:translate-y-[-2px]"
                :class="{ 'border-red-400': form.errors.message }"
              />
            </div>

            <div class="w-full mt-2">
              <button
                :disabled="form.processing"
                class="transition-all duration-300 border border-gray-400 bg-green-700/50 w-full rounded-lg px-4 py-2 text-white hover:bg-primary-900/20 hover:translate-y-[-2px] active:translate-y-0"
                :class="{
                  'opacity-50 cursor-not-allowed': form.processing,
                }"
              >
                <span v-if="form.processing" class="inline-flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                  </svg>
                  Loading...
                </span>
                <span v-else>Submit</span>
              </button>
            </div>
          </form>
        </div>

        <div
          ref="messageListRef"
          class="flex flex-col gap-2 rounded-md h-[22rem] bg-white/90"
        >
          <h3 class="font-wittgenstein text-gray-700 px-4 pt-4">Ucapan yang telah dikirim</h3>
          <div class="overflow-auto w-full max-h-[20rem] custom-scrollbar px-4 pb-4">
            <template v-if="!greeting || greeting.length === 0">
              <p class="text-xs text-gray-500 text-start mt-4">
                Belum ada ucapan yang dikirim.
              </p>
            </template>
            <div
              v-for="(item, index) in greeting"
              :key="item.id || index"
              class="message-item w-full flex flex-col gap-0 border-b border-white/20 pb-2 transition-all duration-300 hover:bg-white/5"
            >
              <div class="flex w-full items-center gap-2 py-2">
                <div class="text-rose-400">
                  <mdicon name="heart-circle-outline" width="40" />
                </div>
                <div class="w-full flex flex-col gap-1">
                  <div class="flex justify-between w-full items-center gap-4">
                    <h4 class="font-wittgenstein text-gray-500 text-sm font-bold truncate max-w-[200px]">
                      {{ item.name }}
                    </h4>
                    <span class="text-xs text-gray-400 shrink-0">
                      {{ moment(item.created_at).fromNow() }}
                    </span>
                  </div>
                  <p class="font-wittgenstein text-gray-400 text-xs">
                    {{ item.message }}
                  </p>
                </div>
              </div>
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
  will-change: transform, opacity;
  backface-visibility: hidden;
  transform: translateZ(0);
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

input:focus,
textarea:focus {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

button:not(:disabled):hover {
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

* {
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
</style>