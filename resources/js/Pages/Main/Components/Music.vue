<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
  play: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(["play"]);
const audio = ref(null);
const canAutoPlay = ref(false);

onMounted(() => {
  audio.value = new Audio('/assets/music/music.mp3');
  audio.value.preload = 'auto';
});

// Watch untuk perubahan props.play
watch(() => props.play, async (newValue) => {
  if (!audio.value) return;

  try {
    if (newValue) {
      // Cek apakah browser mengizinkan autoplay
      const playPromise = audio.value.play();
      if (playPromise !== undefined) {
        playPromise.catch(error => {
          console.log('Autoplay prevented:', error);
          // Reset play state jika autoplay gagal
          emit('play', false);
        });
      }
    } else {
      audio.value.pause();
    }
  } catch (error) {
    console.log('Playback failed:', error);
    emit('play', false);
  }
});

onBeforeUnmount(() => {
  if (audio.value) {
    audio.value.pause();
    audio.value = null;
  }
});
</script>

<template>
  <div class="fixed bottom-4 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">
    <div>
      <button
        class="bg-rose-400 w-10 h-10 flex items-center justify-center rounded-full text-white hover:bg-rose-500 transition-all duration-300 shadow-lg relative overflow-hidden group"
        :class="{ 'animate-pulse': play }"
        @click="$emit('play', !play)"
      >
        <!-- Background Ripple Effect -->
        <div
          class="absolute inset-0 bg-white/20 scale-0 group-hover:scale-100 transition-transform duration-500 rounded-full"
        ></div>
        
        <!-- Icon Container -->
        <div class="relative z-10">
          <Transition 
            name="fade" 
            mode="out-in"
          >
            <mdicon 
              :key="play ? 'pause' : 'play'"
              :name="play ? 'pause' : 'play'" 
              size="24"
              class="transform transition-transform duration-300 group-hover:scale-110"
            />
          </Transition>
        </div>

        <!-- Animated Rings -->
       <div   
    v-if="play"
    class="absolute inset-0 -z-10"
  >
    <div class="absolute inset-0 music-wave"></div>
    <div class="absolute inset-0 music-wave delay-1"></div>
    <div class="absolute inset-0 music-wave delay-2"></div>
  </div>
      </button>
    </div>
  </div>
</template>

<style scoped>
@keyframes musicWave {
  0% {
    transform: scale(1);
    opacity: 0.8;
  }
  50% {
    opacity: 0.3;
  }
  100% {
    transform: scale(1.5);
    opacity: 0;
  }
}
 
.music-wave {
  border: 2px solid rgba(255, 255, 255, 0.5);
  border-radius: 50%;
  animation: musicWave 2s ease-out infinite;
}
 
.delay-1 {
  animation-delay: 0.6s;
}
 
.delay-2 {
  animation-delay: 1.2s;
}
 
/* Update animasi pulse untuk button */
@keyframes gentlePulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}
 
.animate-pulse {
  animation: gentlePulse 2s ease-in-out infinite;
}
 
/* Update hover effect agar lebih smooth */
button {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
 
button:hover {
  transform: translateY(-2px) scale(1.05);
}
 
button:active {
  transform: translateY(0) scale(0.95);
}
 
/* Update gradient untuk lebih soft */
button {
  background: linear-gradient(145deg, 
    rgba(255, 107, 107, 0.9), 
    rgba(255, 135, 135, 0.9)
  );
  backdrop-filter: blur(5px);
}
 
/* Update shadow untuk lebih natural */
button {
  box-shadow: 
    0 4px 6px -1px rgba(0, 0, 0, 0.1),
    0 2px 4px -2px rgba(0, 0, 0, 0.1),
    inset 0 1px 1px rgba(255, 255, 255, 0.2);
}
 
button:hover {
  box-shadow: 
    0 10px 15px -3px rgba(0, 0, 0, 0.1),
    0 4px 6px -4px rgba(0, 0, 0, 0.1),
    inset 0 1px 1px rgba(255, 255, 255, 0.3);
}
 
/* Optional: Tambahkan glow effect saat playing */
button.animate-pulse {
  box-shadow: 
    0 4px 6px -1px rgba(0, 0, 0, 0.1),
    0 2px 4px -2px rgba(0, 0, 0, 0.1),
    inset 0 1px 1px rgba(255, 255, 255, 0.2),
    0 0 15px rgba(255, 107, 107, 0.3);
}
 
/* Update transition untuk icon */
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
 
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: scale(0.9) rotate(15deg);
}
</style>