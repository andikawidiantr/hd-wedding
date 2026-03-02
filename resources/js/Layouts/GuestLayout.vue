<script setup>  
import { Head } from "@inertiajs/vue3";  
import { onMounted } from "vue";  
onMounted(() => {  
  const observer = new IntersectionObserver(  
    (entries) => {  
      entries.forEach(entry => {  
        if (entry.isIntersecting) {  
          entry.target.style.opacity = '1';  
          entry.target.style.transform = 'translateX(0)';  
        }  
      });  
    },  
    {  
      threshold: 0.1  
    }  
  );  
  
  document.querySelectorAll('.fixed').forEach(img => observer.observe(img));  
});  
</script>  

<template>  
  <Head title="Hendra & Dinda Wedding" />  
  <div class="min-h-screen bg-black flex items-center justify-center relative">  
    <!-- Left Image -->  
    <!-- <div class="hidden lg:block fixed left-0 top-0 bottom-0 w-[30vw]">  
       <div class="absolute inset-0 bg-gradient-to-r from-black/50 via-black/50 to-black/100"></div>
      <img   
        src="/assets/images/men-groom.webp"   
        alt="Groom"  
        class="w-full h-full object-cover"  
        loading="lazy"  
        decoding="async"  
      />  
    </div>   -->

    <!-- Main Content -->
    <div class="bg-white max-w-md min-h-screen w-full relative z-[2] lg:mx-[30vw]">  
      <slot />  
    </div>

    <!-- Right Image -->  
    <!-- <div class="hidden lg:block fixed right-0 top-0 bottom-0 w-[30vw]">  
       <div class="absolute inset-0 bg-gradient-to-l from-black/50 via-black/50 to-black/100"></div>

      <img   
        src="/assets/images/women-groom.webp"   
        alt="Bride"  
        class="w-full h-full object-cover"  
        loading="lazy"  
        decoding="async"  
      />  
    </div>   -->
  </div>  
</template>  

<style scoped>  
/* Smooth transition when images load */  
img {  
  transition: opacity 0.5s ease;  
}  

/* Separate animations for left and right images */  
.fixed:first-of-type {  
  animation: fadeInLeft 1s ease-out forwards;  
}  

.fixed:last-of-type {  
  animation: fadeInRight 1s ease-out forwards;  
}  

@keyframes fadeInLeft {  
  from {  
    opacity: 0;  
    transform: translateX(-20px);  
  }  
  to {  
    opacity: 1;  
    transform: translateX(0);  
  }  
}  

@keyframes fadeInRight {  
  from {  
    opacity: 0;  
    transform: translateX(20px);  
  }  
  to {  
    opacity: 1;  
    transform: translateX(0);  
  }  
}  

/* Container styling */  
.fixed {  
  height: 100vh;  
}  

/* Hover effect */  
.fixed img {  
  transition: transform 0.3s ease;
}  


</style>
