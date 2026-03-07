<script setup>
import { computed, onMounted, ref } from "vue";
import gsap from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { useI18n } from 'vue-i18n'; // Import useI18n

// Initialize i18n
const { locale } = useI18n();

const storyContent = {
  id: {
    titleLine1: 'KISAH',
    titleLine2: 'KAMI',
    paragraph1: 'Awalnya ini perkenalan biasa, tapi disinilah awal cerita dimulai, kami dipertemukan pada waktu yang tidak semua orang tahu ini tepat atau tidak, lewat obrolan ringan sampai percakapan yang penuh makna membuat kami saling mengenal pribadi kami masing masing',
    paragraph2: 'Perjalanan yang tidak selalu mudah, namun yang kami percaya tidak ada yang benar benar sempurna. Hari demi hari kami berjalan, proses demi proses terbentuk kami belajar untuk menerima dan memahami. Bentuk cinta dan kasih yang kami percaya adalah tentang bertahan dengan segala bentuk perbedaan yang sudah ada ataupun akan kami temui nanti.',
    paragraph3: 'Atas restu Ida Sang Hyang Widhi, leluhur, dan orang tua. Dengan segala keyakinan kami berdua, sebuah janji Dharma untuk saling menjaga, saling menghormati dan setia dalam segala kondisi. Kami berdua memantapkan untuk menjalani kehidupan Grahasta Asrama.',
    paragraph4: 'Semoga langkah kami hari ini menjadi awal dari perjalanan panjang yang penuh dengan berkah. menjalani kehidupan yang diwarnai dengan keharmonisan, kedamaian dan kebahagian. Kami mohon doa restunya'
  },
  en: {
    titleLine1: 'OUR',
    titleLine2: 'STORY',
    paragraph1: 'It started as a casual introduction, but this is where our story began. We met at a time that not everyone would know was right or not. Through light conversations to meaningful talks, we got to know each other personally.',
    paragraph2: 'The journey has not always been easy, but we believe nothing is truly perfect. Day by day we walked, process by process we formed, learning to accept and understand. The form of love and affection that we believe in is about enduring with all forms of differences that already exist or that we will encounter later.',
    paragraph3: 'With the blessing of Ida Sang Hyang Widhi, ancestors, and parents. With all our beliefs, a Dharma promise to protect each other, respect each other and be faithful in all conditions. We both are determined to live the Grahasta Asrama life.',
    paragraph4: 'May our steps today be the beginning of a long journey full of blessings. Living a life colored with harmony, peace and happiness. We ask for your prayers and blessings.'
  }
};

const storyCopy = computed(() => storyContent[locale.value] || storyContent.id);

gsap.registerPlugin(ScrollTrigger);

const titleRef = ref(null);
const messageRef = ref(null);
const sectionRef = ref(null);

const initAnimations = () => {
  // Fade in animation for title
  gsap.from(titleRef.value, {
    opacity: 0,
    y: 30,
    duration: 1.2,
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
    delay: 0.2,
    ease: "power2.out",
    scrollTrigger: {
      trigger: messageRef.value,
      start: "top bottom-=100",
      toggleActions: "play none none reverse",
    },
  });
};

onMounted(() => {
  initAnimations();
});
</script>

<template>
  <section
    ref="sectionRef"
    id="story"
    class="min-h-full px-4 py-1">
    <div :key="locale" class="w-full max-w-md flex flex-col items-center z-[2] relative">
      <!-- Title with decorative elements -->
      <div class="flex items-center justify-center w-full my-8">
        <!-- Left decorative line -->
        <div class="w-16 h-6 mr-4 flex items-center">
          <svg viewBox="0 0 100 30" class="w-full h-full">
            <path d="M0,15 Q30,0 50,15 T100,15" fill="none" stroke="white" stroke-width="2" />
          </svg>
        </div>
        
        <!-- Title -->
        <h2 ref="titleRef" class="font-serif text-center text-white text-5xl tracking-wider">
          {{ storyCopy.titleLine1 }}<br>{{ storyCopy.titleLine2 }}
        </h2>
        
        <!-- Right decorative line (flipped version of left) -->
        <div class="w-16 h-6 ml-4 flex items-center">
          <svg viewBox="0 0 100 30" class="w-full h-full transform scale-x-[-1]">
            <path d="M0,15 Q30,0 50,15 T100,15" fill="none" stroke="white" stroke-width="2" />
          </svg>
        </div>
      </div>
      
      <!-- Message -->
      <p ref="messageRef" class="text-white text-justify mb-8 mx-4 leading-relaxed">
        {{ storyCopy.paragraph1 }}
        <br><br>
        {{ storyCopy.paragraph2 }}
        <br><br>
        {{ storyCopy.paragraph3 }}
        <br><br>
        {{ storyCopy.paragraph4 }}
      </p>
      
      <!-- Overlay gradient -->
      <div
        class="absolute inset-0 bg-[#4D4D4D]/90 -z-[1]"
      ></div>
    </div>
  </section>
</template>

<style scoped>
.font-serif {
  font-family: 'Playfair Display', serif;
}

@media (max-width: 640px) {
  section {
    padding-top: 3rem;
    padding-bottom: 3rem;
  }
  
  /* Make decorative lines smaller on mobile */
  .w-16 {
    width: 2.5rem;
  }
}
</style>
