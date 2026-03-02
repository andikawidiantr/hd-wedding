<script setup>
import { onMounted, ref } from "vue";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useI18n } from "vue-i18n"; // Import useI18n

// Initialize i18n
const { t } = useI18n();

gsap.registerPlugin(ScrollTrigger);

const imageLoaded = ref(false);
const titleRef = ref(null);
const messageRef = ref(null);
const bankInfoRef = ref(null);
const sectionRef = ref(null);
const imageRef = ref(null);
const copiedBank = ref(null);
const copyTimeouts = ref({});

const banks = [
  {
    name: t('gift.bank_bca', 'BANK BCA'),
    number: "6485670681",
    owner: t('gift.bank_owner', 'a.n. I Gede Agus Hendrawan')
  },
  // {
  //   name: "BANK CA",
  //   number: "123xxx",
  //   owner: "a.n. LOREM IPSUM"
  // }
];


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

const copyToClipboard = async (text, type) => {
  try {
    await navigator.clipboard.writeText(text);
    copiedBank.value = type;
    
    // Clear existing timeout if any
    if (copyTimeouts.value[type]) clearTimeout(copyTimeouts.value[type]);
    
    // Reset after 3 seconds
    copyTimeouts.value[type] = setTimeout(() => {
      if (copiedBank.value === type) {
        copiedBank.value = null;
      }
    }, 3000);

    // Animate the copy button
    gsap.from(`#copy-btn-${type}`, {
      scale: 0.9,
      duration: 0.3,
      ease: "back.out"
    });
  } catch (err) {
    console.error("Failed to copy:", err);
  }
};

const initAnimations = () => {
  // Fade in animation for image
  gsap.from(imageRef.value, {
    opacity: 0,
    y: 10,
    duration: 1.2,
    ease: "power2.out",
    scrollTrigger: {
      trigger: sectionRef.value,
      start: "top bottom-=50",
      toggleActions: "play none none reverse",
    },
  });

  // Fade in animation for title
  gsap.from(titleRef.value, {
    opacity: 0,
    y: 30,
    duration: 1.2,
    delay: 0.2,
    ease: "power2.out",
    scrollTrigger: {
      trigger: titleRef.value,
      start: "top bottom-=100",
      toggleActions: "play none none reverse",
    },
  });

  // Fade in animation for message
  gsap.from(messageRef.value, {
    opacity: 0,
    y: 20,
    duration: 1,
    delay: 0.3,
    ease: "power2.out",
    scrollTrigger: {
      trigger: messageRef.value,
      start: "top bottom-=100",
      toggleActions: "play none none reverse",
    },
  });

  // Animate bank info
  gsap.from(bankInfoRef.value.children, {
    opacity: 0,
    y: 20,
    stagger: 0.2,
    duration: 0.8,
    delay: 0.5,
    ease: "power2.out",
    scrollTrigger: {
      trigger: bankInfoRef.value,
      start: "top bottom-=100",
      toggleActions: "play none none reverse",
    },
  });
};

onMounted(async () => {
  try {
    await preloadImage("/assets/images/raw-1582.jpg");
    initAnimations();
  } catch (error) {
    console.error("Error loading image:", error);
    // Still initialize animations even if image fails to load
    initAnimations();
  }
});
</script>

<template>
  <!-- Image at the top -->
  <div ref="imageRef" class="px-4 pt-20">
    <img 
      src="/assets/images/raw-1582.jpg" 
      :alt="t('gift.image_alt', 'Couple on the beach')" 
      class="object-cover rounded-sm"
    />
  </div>
  <section
    ref="sectionRef"
    id="gift"
    class="min-h-screen flex items-start justify-center relative z-[2] overflow-hidden px-4">
    <div class="w-full max-w-md flex flex-col items-center z-[2] relative pb-8">
      <!-- Title -->
      <h2 ref="titleRef" class="font-serif text-center text-white text-5xl tracking-wider my-8">
        {{ t('gift.title', 'WEDDING') }}<br>{{ t('gift.subtitle', 'GIFT') }}
      </h2>
      
      <!-- Message -->
      <p ref="messageRef" class="text-white text-center mb-10 mx-4 leading-relaxed">
        {{ t('gift.message', 'Your presence and prayers are the greatest wedding gifts we could ever ask for. No other gifts are needed nor expected. However, if giving is a sign of love, we are happy to receive it, and, of course, it will enhance our happiness even more.') }}
      </p>
      
      <!-- Bank Information -->
      <div ref="bankInfoRef" class="w-full flex flex-col gap-4 px-4">
        <!-- First Bank -->
        <div 
          v-for="(bank, index) in banks" 
          :key="bank.name"
          class="w-full border border-white/40 p-5 relative pb-16"
        >
          <div class="flex flex-col">
            <span class="text-white font-medium">{{ bank.name }}</span>
            <span class="text-white">{{ bank.number }}</span>
            <span class="text-white">{{ bank.owner }}</span>
          </div>
          <button 
            :id="`copy-btn-${index}`"
            @click="copyToClipboard(bank.number, index)" 
            class="absolute bottom-4 right-4 text-white border border-white/40 px-4 py-1 text-sm hover:bg-white/10 transition-all duration-300"
          >
            <span class="flex items-center">
              <mdicon 
                :name="copiedBank === index ? 'check' : 'content-copy'" 
                class="mr-1"
                width="16"
              />
              {{ copiedBank === index ? t('gift.copied', 'Copied') : t('gift.copy', 'Copy') }}
            </span>
          </button>
        </div>
        
        <!-- Address -->
        <div class="w-full border border-white/40 p-5 relative pb-16">
          <p class="text-white">Home Address: Jalan Gunung Lebah I Gang VII No. 10, Denpasar, Bali</p>
          <button 
            id="copy-btn-address"
            @click="copyToClipboard('Home Address: Jalan Gunung Lebah I Gang VII No. 10, Denpasar, Bali', 'address')" 
            class="absolute bottom-4 right-4 text-white border border-white/40 px-4 py-1 text-sm hover:bg-white/10 transition-all duration-300"
          >
            <span class="flex items-center">
              <mdicon 
                :name="copiedBank === 'address' ? 'check' : 'content-copy'" 
                class="mr-1"
                width="16"
              />
              {{ copiedBank === 'address' ? t('gift.copied', 'Copied') : t('gift.copy', 'Copy') }}
            </span>
          </button>
        </div>
      </div>
      <!-- Overlay gradient -->
      <div
        class="absolute inset-x-0 top-0 bottom-0 bg-[#4D4D4D]/90 -z-[1]"
      ></div>
    </div>
  </section>
</template>

<style scoped>
.font-serif {
  font-family: 'Playfair Display', serif;
}

/* Style for buttons when copied */
button:has(mdicon[name="check"]) {
  background-color: rgba(255, 255, 255, 0.1);
}

/* Image transition */
img {
  transition: opacity 0.5s ease-in-out;
}

/* Ensure content is properly spaced */
@media (max-width: 640px) {
  section {
    padding-top: 3rem;
    padding-bottom: 3rem;
  }
}
</style>
