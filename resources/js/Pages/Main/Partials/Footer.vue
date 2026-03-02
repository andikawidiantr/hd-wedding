<script setup>  
import { onMounted, ref } from "vue";  
import gsap from 'gsap';  
import ScrollTrigger from 'gsap/ScrollTrigger';  
import { useI18n } from "vue-i18n"; // Import useI18n

// Initialize i18n
const { t } = useI18n();
  
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
      y: 30  
    },  
    {  
      opacity: 1,  
      y: 0,  
      duration: 1.5,  
      ease: "power4.out",  
      scrollTrigger: {  
        trigger: footerRef.value,  
        start: "top center+=100",  
        toggleActions: "play none none reverse"  
      }  
    }  
  );  
  
  // Names animation with sparkle effect  
  gsap.fromTo(namesRef.value,  
    {  
      opacity: 0,  
      scale: 0.95,  
      filter: 'blur(5px)'  
    },  
    {  
      opacity: 1,  
      scale: 1,  
      filter: 'blur(0px)',  
      duration: 2,  
      delay: 0.5,  
      ease: "power4.out",  
      scrollTrigger: {  
        trigger: footerRef.value,  
        start: "top center+=100",  
        toggleActions: "play none none reverse"  
      }  
    }  
  );  
  
  // Credits fade up animation  
  gsap.fromTo(creditsRef.value,  
    {  
      opacity: 0,  
      y: 20  
    },  
    {  
      opacity: 1,  
      y: 0,  
      duration: 1,  
      delay: 1,  
      ease: "power3.out",  
      scrollTrigger: {  
        trigger: footerRef.value,  
        start: "top center+=100",  
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
    <div class="flex-grow w-full flex flex-col items-center justify-center z-[2] px-4 py-12">
      <div class="flex flex-col gap-0 mb-4">  
        <h2   
          ref="thankYouRef"  
          class="font-poly text-center text-white text-xl animate-fade-in"  
        >  
          {{ t('footer.thank_you', 'Thank You') }}  
        </h2>  
        <h2   
          ref="namesRef"  
          class="font-eyesome text-center text-white text-3xl animate-sparkle"  
        >  
          {{ t('footer.couple_names', 'Hendra & Dinda') }}  
        </h2>  
      </div>
    </div>
    
    <!-- Credits fixed at the bottom -->
    <div   
      ref="creditsRef"  
      class="w-full pb-8 mt-auto animate-fade-up"  
    >  
      <p class="text-gray-200 text-center mb-5">  
        {{ t('footer.credits_prefix', 'Designed and Developed by') }} <br>
        <a  
          href="https://www.instagram.com/andikawidiantr/"  
          target="_blank"  
          class="underline hover:text-white transition-colors duration-300 hover:scale-105 inline-block"  
        >{{ t('footer.developer_name', 'andikawidiantr') }}</a>  
      </p>  
    </div>
  </section>  
</template>
  
<style scoped>  
/* Fade In Animation */  
@keyframes fadeIn {  
  from {  
    opacity: 0;  
    transform: translateY(30px);  
  }  
  to {  
    opacity: 1;  
    transform: translateY(0);  
  }  
}  
  
/* Sparkle Animation */  
@keyframes sparkle {  
  0% {  
    opacity: 0;  
    transform: scale(0.95);  
    filter: blur(5px);  
  }  
  50% {  
    opacity: 0.5;  
    transform: scale(1.02);  
    filter: blur(0);  
  }  
  100% {  
    opacity: 1;  
    transform: scale(1);  
    filter: blur(0);  
  }  
}  
  
/* Fade Up Animation */  
@keyframes fadeUp {  
  from {  
    opacity: 0;  
    transform: translateY(20px);  
  }  
  to {  
    opacity: 1;  
    transform: translateY(0);  
  }  
}  
  
/* Apply animations as fallback */  
.animate-fade-in {  
  animation: fadeIn 1.5s ease-out forwards;  
}  
  
.animate-sparkle {  
  animation: sparkle 2s ease-out forwards;  
}  
  
.animate-fade-up {  
  animation: fadeUp 1s ease-out forwards;  
  animation-delay: 1s;  
}  
  
/* Optimize performance */  
.font-poly,  
.font-eyesome {  
  transform: translateZ(0);  
  backface-visibility: hidden;  
  will-change: transform, opacity;  
}  
  
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
