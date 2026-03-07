<script setup>  
import { computed, onMounted, ref } from "vue";  
import gsap from 'gsap';  
import ScrollTrigger from 'gsap/ScrollTrigger';  
import { useI18n } from "vue-i18n"; // Import useI18n

// Initialize i18n
const { t, locale } = useI18n();

const footerCopy = computed(() => ({
  thankYou: t('footer.thank_you', 'Thank You'),
  coupleNames: t('footer.couple_names', 'Hendra & Dinda'),
  creditsPrefix: t('footer.credits_prefix', 'Designed and Developed by'),
  developerName: t('footer.developer_name', 'andikawidiantr')
}));
  
gsap.registerPlugin(ScrollTrigger);  
  
const footerRef = ref(null);  
const thankYouRef = ref(null);  
const namesRef = ref(null);  
const creditsRef = ref(null);  
  
const setupAnimations = () => {  
  // Thank You text animation  
  gsap.fromTo(thankYouRef.value,  
    {   
      opacity: 0,  
      y: 50  // Mulai dari posisi lebih jauh
    },  
    {  
      opacity: 1,  
      y: 0,  
      duration: 2, // Durasi lebih lama (diubah dari 1.5 menjadi 2.5)
      ease: "power2.out", // Ease yang lebih lembut
      scrollTrigger: {  
        trigger: footerRef.value,  
        start: "top 70%", // Trigger lebih awal (diubah dari "top center+=100")
        toggleActions: "play none none reverse",
        // markers: true, // Aktifkan ini untuk debugging
      }  
    }  
  );  
  
  // Names animation - LEBIH LAMBAT DAN ELEGAN
  gsap.fromTo(namesRef.value,  
    {  
      opacity: 0,  
      scale: 0.92,  
      filter: 'blur(8px)'  
    },  
    {  
      opacity: 1,  
      scale: 1,  
      filter: 'blur(0px)',  
      duration: 3, // Durasi lebih lama (diubah dari 2 menjadi 3)
      delay: 0.5, // Delay lebih lama setelah "Thank You" (diubah dari 0.5 menjadi 1.2)
      ease: "power3.out", // Ease yang lebih lembut
      scrollTrigger: {  
        trigger: footerRef.value,  
        start: "top 70%", // Trigger lebih awal (sama dengan Thank You)
        toggleActions: "play none none reverse"  
      }  
    }  
  );  
  
};  
  
onMounted(async () => {  
  try {  
    setupAnimations();  
  } catch (error) {  
    console.error("Error loading image:", error);  
  }  
});  
</script>  
  
<template>  
  <section  
    ref="footerRef"  
    id="footer"  
    class="min-h-screen flex flex-col items-center justify-between relative z-[1] -mt-10"
    :style="{  
      backgroundColor: 'transparent',  
    }"  
  >  
    <!-- Main content with thank you and names -->
    <div :key="locale" class="flex-grow w-full flex flex-col items-center justify-center z-[2] px-4 py-12">
      <div class="flex flex-col gap-0 mb-4">  
        <h2   
          ref="thankYouRef"  
          class="font-poly text-center font-light text-white text-4xl"  
        >  
          {{ footerCopy.thankYou }}  
        </h2>  
        <h2   
          ref="namesRef"  
          class="font-playball text-center text-white text-3xl mt-2" 
        >  
          {{ footerCopy.coupleNames }}  
        </h2>  
      </div>
    </div>
    
    <!-- Credits fixed at the bottom -->
    <div   
      ref="creditsRef"  
      class="w-full pb-8 mt-auto"  
    >  
      <p class="text-gray-200 text-center mb-5">  
        {{ footerCopy.creditsPrefix }} <br>
        <a  
          href="https://www.instagram.com/andikawidiantr/"  
          target="_blank"  
          class="underline hover:text-white transition-colors duration-300 hover:scale-105 inline-block"  
        >{{ footerCopy.developerName }}</a>  
      </p>  
    </div>
  </section>  
</template>
  
<style scoped>  
/* Removed CSS animations since we're using GSAP for animations */
  
/* Hover effect for link */  
a {  
  transition: all 0.3s ease;  
  display: inline-block;  
}  
  
a:hover {  
  text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);  
  transform: translateY(-1px);  
}  
  
/* Smooth scroll behavior */  
html {  
  scroll-behavior: smooth;  
}  
  
/* Optimize animations */  
* {  
  -webkit-font-smoothing: antialiased;  
  -moz-osx-font-smoothing: grayscale;  
}  
</style>
