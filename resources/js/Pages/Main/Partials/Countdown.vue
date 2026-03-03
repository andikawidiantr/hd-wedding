<script setup>      
import moment from "moment";      
import { onBeforeUnmount, onMounted, ref, watch, computed } from "vue";      
import gsap from "gsap";      
import ScrollTrigger from "gsap/ScrollTrigger";
import { useI18n } from 'vue-i18n';
import 'moment/locale/id';
import 'moment/locale/en-gb';

// Initialize i18n
const { t, locale } = useI18n();

// Accept props including the code
const props = defineProps({
  code: String
});

gsap.registerPlugin(ScrollTrigger);      
      
const eventRef = ref(null);      
const countdownRef = ref(null);      
const saveTheDateRef = ref(null);  
  
const countdown = ref({      
  days: "00",      
  hours: "00",      
  minutes: "00",      
  seconds: "00",      
});

// Define different target dates based on code
const eventDates = computed(() => ({
  "23": moment("2026-03-23T10:00:00+08:00"), // Tipat Bantal date
  "25": moment("2026-03-25T10:00:00+08:00"), // Pawiwahan date
  "28": moment("2026-03-28T18:00:00+08:00"), // Resepsi date
  "default": moment("2026-03-25T10:00:00+08:00") // Default date (Pawiwahan)
}));

// Determine which target date to use based on code
const targetDate = computed(() => {
  if (!props.code) {
    return eventDates.value.default;
  } else if (props.code === "2325") {
    // For combined code, use the earlier date
    return eventDates.value["25"];
  } else {
    return eventDates.value[props.code] || eventDates.value.default;
  }
});

let timer;      
      
const updateCountdown = () => {      
  const now = moment();      
  const diff = targetDate.value.diff(now);      
      
  if (diff <= 0) {      
    countdown.value = {      
      days: "00",      
      hours: "00",      
      minutes: "00",      
      seconds: "00",      
    };      
    if (timer) clearInterval(timer);      
    return;      
  }      
      
  const duration = moment.duration(diff);      
      
  countdown.value = {      
    days: String(Math.floor(duration.asDays())).padStart(2, "0"),      
    hours: String(duration.hours()).padStart(2, "0"),      
    minutes: String(duration.minutes()).padStart(2, "0"),      
    seconds: String(duration.seconds()).padStart(2, "0"),      
  };      
};

// Define different calendar events based on code
const calendarEvents = computed(() => ({
  "23": {
    title: t('countdown.calendar_event_title_tipat', 'Memadik/Nyuwaka Hendra & Dinda'),
    description: t('countdown.calendar_event_description_tipat', 'Memadik/Nyuwaka : 10:00 - Selesai\nLokasi: Desa Jagapati, Pertima, Badung'),
    location: t('countdown.calendar_event_location_tipat', 'Desa Jagapati, Pertima, Badung'),
    start: "20260323T020000Z", // 10:00 WITA in UTC
    end: "20260323T100000Z"    // 18:00 WITA in UTC
  },
  "25": {
    title: t('countdown.calendar_event_title_pawiwahan', 'Pawiwahan Hendra & Dinda'),
    description: t('countdown.calendar_event_description_pawiwahan', 'Pawiwahan: 10:00 - Selesai\nLokasi: Desa Asak, Pertima, Karangasem'),
    location: t('countdown.calendar_event_location_pawiwahan', 'Desa Asak, Pertima, Karangasem'),
    start: "20260325T020000Z", // 10:00 WITA in UTC
    end: "20260325T100000Z"    // 18:00 WITA in UTC
  },
  "28": {
    title: t('countdown.calendar_event_title_resepsi', 'Resepsi Hendra & Dinda'),
    description: t('countdown.calendar_event_description_resepsi', 'Resepsi: 18:00 - Selesai\nLokasi: Villa D\'carik, Jalan Kaswari, Penatih, Denpasar Timur, Bali'),
    location: t('countdown.calendar_event_location_resepsi', 'Villa D\'carik, Jalan Kaswari, Penatih, Denpasar Timur, Bali'),
    start: "20260328T100000Z", // 18:00 WITA in UTC
    end: "20260328T140000Z"    // 22:00 WITA in UTC
  },
  "2325": {
    title: t('countdown.calendar_event_title_combined', 'Memadik/Nyuwaka & Pawiwahan Hendra & Dinda'),
    description: t('countdown.calendar_event_description_combined', 'Memadik/Nyuwaka : 23 Maret 2026, 10:00 - Selesai\nPawiwahan: 25 Maret 2026, 10:00 - Selesai'),
    location: t('countdown.calendar_event_location_combined', 'Desa Jagapati & Desa Asak, Bali'),
    start: "20260323T020000Z", // First event start
    end: "20260325T100000Z"    // Last event end
  },
  "default": {
    title: t('countdown.calendar_event_title', 'Pawiwahan & Resepsi Hendra & Dinda'),
    description: t('countdown.calendar_event_description', 'Pawiwahan: 10:00 - Selesai\nResepsi: 18:00 - Selesai'),
    location: t('countdown.calendar_event_location', 'Desa Asak, Karangasem & Villa D\'carik, Denpasar'),
    start: "20260325T020000Z",
    end: "20260328T140000Z"
  }
}));

// Get the appropriate calendar event based on code
const selectedCalendarEvent = computed(() => {
  if (!props.code) {
    return calendarEvents.value.default;
  } else {
    return calendarEvents.value[props.code] || calendarEvents.value.default;
  }
});

const createGoogleCalendarLink = () => {
  const event = selectedCalendarEvent.value;
  
  return `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(
    event.title
  )}&dates=${event.start}/${event.end}&details=${encodeURIComponent(
    event.description
  )}&location=${encodeURIComponent(event.location)}&ctz=Asia/Makassar`;
};

// Set moment locale based on current i18n locale
const updateMomentLocale = () => {
  moment.locale(locale.value);
};

// Watch for locale changes to update moment
watch(locale, () => {
  updateMomentLocale();
});

// Watch for code changes to update countdown
watch(() => props.code, () => {
  updateCountdown();
});
  
onMounted(() => {
  // Set moment locale
  updateMomentLocale();
        
  updateCountdown();      
  timer = setInterval(updateCountdown, 1000);      
    
  // GSAP animation      
  const tl = gsap.timeline({      
    scrollTrigger: {      
      trigger: eventRef.value,      
      start: "top center",      
      end: "center center",      
      toggleActions: "play none none reverse",      
    },      
  });      
    
  gsap.set([countdownRef.value, saveTheDateRef.value],{      
    y: 50,      
    opacity: 0,      
  });      
    
  tl.to(countdownRef.value, {      
    y: 0,      
    opacity: 1,      
    duration: 1,      
    ease: "power3.out",      
  })  
  .to(saveTheDateRef.value, {  
      y: 0,  
      opacity: 1,  
      duration: 1,  
      ease: "back.out(1.7)"  
    }, "-=0.7");      
});      
      
onBeforeUnmount(() => {      
  if (timer) clearInterval(timer);      
});      
</script>      
    
<template>        
  <section        
    ref="eventRef"        
    id="countdown"        
    class="h-[50vh] flex flex-col items-center justify-center relative z-[2] overflow-hidden px-4 py-6"        
  >        
    <!-- Title section at the top -->  
    <div class="mb-8 w-full">  
      <!-- Line with small margins -->  
      <div class="w-[100%] mx-auto border-t border-white mb-6"></div>  
        
      <!-- Title -->  
      <div class="w-[90%] mx-auto">      
        <div class="text-right">  
          <h2 class="font-wittgenstein text-4xl md:text-5xl text-white drop-shadow-md">{{ t('countdown.title_line1', 'COUNTDOWN') }}</h2>        
          <h2 class="font-wittgenstein text-4xl md:text-5xl text-white drop-shadow-md">{{ t('countdown.title_line2', 'TIMER') }}</h2>  
        </div>        
      </div>  
    </div>  
          
    <div ref="countdownRef" class="flex gap-4 justify-center items-center mb-8 pt-24">       
      <!-- Days -->        
      <div        
        class="flex flex-col items-center bg-black/30 p-3 lg:p-4 rounded-lg backdrop-blur-sm transition-all duration-300 ease-in-out hover:bg-black/40 hover:scale-105"        
      >        
        <span class="font-poly text-2xl lg:text-3xl text-white countdown-number drop-shadow-md">        
          {{ countdown.days }}        
        </span>        
        <span class="font-wittgenstein text-sm md:text-base text-white drop-shadow-sm">{{ t('countdown.days', 'Hari') }}</span>        
      </div>        
    
      <span class="font-poly text-3xl text-white drop-shadow-md">:</span>        
    
      <!-- Hours -->        
      <div        
        class="flex flex-col items-center bg-black/30 p-3 lg:p-4 rounded-lg backdrop-blur-sm transition-all duration-300 ease-in-out hover:bg-black/40 hover:scale-105"        
      >        
        <span class="font-poly text-2xl lg:text-3xl text-white countdown-number drop-shadow-md">        
          {{ countdown.hours }}        
        </span>        
        <span class="font-wittgenstein text-sm md:text-base text-white drop-shadow-sm">{{ t('countdown.hours', 'Jam') }}</span>        
      </div>        
    
      <span class="font-poly text-3xl text-white drop-shadow-md">:</span>        
    
      <!-- Minutes -->        
      <div        
        class="flex flex-col items-center bg-black/30 p-3 lg:p-4 rounded-lg backdrop-blur-sm transition-all duration-300 ease-in-out hover:bg-black/40 hover:scale-105"        
      >        
        <span class="font-poly text-2xl lg:text-3xl text-white countdown-number drop-shadow-md">        
          {{ countdown.minutes }}        
        </span>        
        <span class="font-wittgenstein text-sm md:text-base text-white drop-shadow-sm">{{ t('countdown.minutes', 'Menit') }}</span>        
      </div>        
    
      <span class="font-poly text-2xl lg:text-3xl text-white drop-shadow-md">:</span>        
    
      <!-- Seconds -->        
      <div        
        class="flex flex-col items-center bg-black/30 p-3 lg:p-4 rounded-lg backdrop-blur-sm transition-all duration-300 ease-in-out hover:bg-black/40 hover:scale-105"        
      >        
        <span class="font-poly text-2xl lg:text-3xl text-white countdown-number drop-shadow-md">        
          {{ countdown.seconds }}        
        </span>        
        <span class="font-wittgenstein text-sm md:text-base text-white drop-shadow-sm">{{ t('countdown.seconds', 'Detik') }}</span>        
      </div>        
    </div>      
          
    <div ref="saveTheDateRef" class="flex items-center justify-center pt-8">    
      <a    
        :href="createGoogleCalendarLink()"  
        target="_blank"    
        class="save-date-button border border-white rounded-xl px-6 py-1 text-white uppercase font-wittgenstein transition-all duration-300 hover:bg-white/20 backdrop-blur-sm"    
      >    
        {{ t('countdown.save_the_date', 'Save The Date') }}    
      </a>    
    </div>  
  </section>        
</template>  
      
<style scoped>      
/* Animasi hover untuk countdown boxes */      
.countdown-number {      
  transition: transform 0.2s ease-in-out;      
}      
      
.save-date-button {  
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);  
}  
  
.save-date-button:hover {  
  transform: translateY(-2px);  
  background-color: white;  
  color: #4D4D4D;  
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);  
}  
  
.save-date-button:active {  
  transform: translateY(0);  
}  
.countdown-number:hover {      
  transform: scale(1.1);      
}      
      
/* Responsive adjustments */      
@media (max-width: 640px) {      
  .countdown-number {      
    font-size: 1.5rem;      
  }      
}      
</style>
