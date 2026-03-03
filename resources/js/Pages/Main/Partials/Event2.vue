<script setup>
import { onMounted, ref, computed } from "vue";    
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";
import { useI18n } from 'vue-i18n'; // Import useI18n composable

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);
    
const boxSectionRef = ref(null);   
const imageRef = ref(null);  
const eventDetailsRef = ref(null);

// Initialize i18n
const { t, locale } = useI18n();

const props = defineProps({
  code: String,
});

// Define all three events with translations
const allEvents = computed(() => [
  {
    code: "23",
    title: t('events.tipat_bantal.title', 'Memadik/Nyuwaka'),
    date: t('events.tipat_bantal.date', 'Rabu, 23 Maret 2026'),
    time: t('events.tipat_bantal.time', 'Pukul 10.00 - Selesai'),
    venue: t('events.tipat_bantal.venue', 'Desa Jagapati'),
    address: t('events.tipat_bantal.address', 'Jl. jagapati, Pertima, Badung'),
    mapsUrl: "https://maps.app.goo.gl/9xDBhyXeR6tYD6DPA"
  },
  {
    code: "25",
    title: t('events.pawiwahan.title', 'PAWIWAHAN'),
    date: t('events.pawiwahan.date', 'Rabu, 25 Maret 2026'),
    time: t('events.pawiwahan.time', 'Pukul 10.00 - Selesai'),
    venue: t('events.pawiwahan.venue', 'Desa Asak'),
    address: t('events.pawiwahan.address', 'Jl. Asak, Pertima, Karangasem'),
    mapsUrl: "https://maps.app.goo.gl/PAWztaF2QjtRPNdc6"
  },
  {
    code: "28",
    title: t('events.resepsi.title', 'Resepsi'),
    date: t('events.resepsi.date', 'Rabu, 28 Maret 2026'),
    time: t('events.resepsi.time', 'Pukul 18.00 - Selesai'),
    venue: t('events.resepsi.venue', 'Villa D\'carik'),
    address: t('events.resepsi.address', 'Jalan Kaswari, Penatih, Denpasar Timur, Bali'),
    mapsUrl: "https://maps.app.goo.gl/85hCXpMEsPaby18m7?g_st=iw"
  }
]);

// Determine which events to show based on code
const eventsToShow = computed(() => {
  if (!props.code) {
    // If no code is provided, show all events
    return allEvents.value;
  } else if (props.code === "2325") {
    // If code is 2325, show both Tipat Bantal and PAWIWAHAN events
    return [allEvents.value[0], allEvents.value[1]];
  } else {
    // Otherwise, show only the event that matches the code
    const event = allEvents.value.find(event => event.code === props.code);
    return event ? [event] : [allEvents.value[2]]; // Default to Resepsi if code doesn't match
  }
});

// Compute section height based on number of events
const sectionHeight = computed(() => {
  if (eventsToShow.value.length === 3) {
    return 'auto'; // All events
  } else if (eventsToShow.value.length === 2) {
    return 'auto'; // Changed from fixed height to auto for two events
  } else {
    return 'min-h-screen'; // Single event
  }
});

// Compute box class based on number of events
const boxClass = computed(() => {
  const baseClass = "decorative-box";
  if (eventsToShow.value.length === 3) {
    return `${baseClass} all-events`;
  } else if (eventsToShow.value.length === 2) {
    return `${baseClass} two-events`;
  } else {
    return baseClass;
  }
});

// Function to change language
const changeLanguage = (newLocale) => {
  locale.value = newLocale;
  localStorage.setItem('locale', newLocale);
};

onMounted(() => {  
  // Create animation that shows/hides the image based on scroll direction
  gsap.fromTo(imageRef.value,   
    { 
      y: 100,   
      opacity: 0   
    },  
    {   
      y: 0,   
      opacity: 1,   
      duration: 1,   
      ease: "power3.out",
      scrollTrigger: {  
        trigger: boxSectionRef.value,  
        start: "top 80%",
        end: "bottom 20%",
        toggleActions: "play reverse play reverse", 
        markers: false
      }  
    }  
  );  
  
  // Animation for the wedding details section
  gsap.fromTo(eventDetailsRef.value,     
    {   
      y: 50,     
      opacity: 0     
    },    
    {     
      y: 0,     
      opacity: 1,     
      duration: 1,
      delay: 0.3,     
      ease: "power3.out",  
      scrollTrigger: {    
        trigger: eventDetailsRef.value,    
        start: "top 80%",  
        end: "bottom 20%",  
        toggleActions: "play reverse play reverse",
        markers: false
      }    
    }    
  );   
});  
</script>    
    
<template>    
  <div ref="containerRef" class="container-wrapper">
    <!-- Language Switcher -->
    <!-- <div class="language-switcher">
      <button 
        @click="changeLanguage('id')" 
        :class="{ 'active': locale === 'id' }"
      >
        ID
      </button>
      <span class="divider">|</span>
      <button 
        @click="changeLanguage('en')" 
        :class="{ 'active': locale === 'en' }"
      >
        EN
      </button>
    </div> -->
    
    <section    
      ref="boxSectionRef"    
      id="box-section"    
      :class="[
        'flex items-center justify-center relative z-[2] overflow-visible pt-20 mt-12 extended-section',
        sectionHeight
      ]"
    >    
      <!-- Decorative box with rounded top corners (radius 200px) -->    
      <div ref="decorativeBoxRef" :class="boxClass">  
        <!-- Image container inside the box -->  
        <div class="image-container">  
          <img   
            ref="imageRef"  
            src="/assets/images/edit-26.jpg"   
            alt="Decorative image"   
            class="decorative-image"  
          />  
        </div>
        <div ref="eventDetailsRef" class="event-two">
          <!-- Loop through events to show -->
          <div v-for="(event, index) in eventsToShow" :key="event.code" class="event-item" :class="{ 'mt-8': index > 0 }">
            <h2 class="event-title">{{ event.title }}</h2>
            <p class="event-date">{{ event.date }}</p>
            <p class="event-time">{{ event.time }}</p>
            <p class="event-location">{{ t('common.located_at', 'Bertempat di') }}</p>
            <p class="event-venue">{{ event.venue }}</p>
            <p class="event-address">{{ event.address }}</p>
            
            <a
                :href="event.mapsUrl"
                target="_blank"
                class="map-button">
              <span class="map-icon"></span> {{ t('common.google_maps', 'Google Maps') }}
            </a>
            
            <!-- Add divider if not the last event -->
            <div v-if="index < eventsToShow.length - 1" class="event-divider"></div>
          </div>
        </div>  
      </div>  
    </section>
  </div>
</template>    
    
<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500&display=swap');

.container-wrapper {
  position: relative;
  width: 100%;
  overflow: visible;
}

/* Language Switcher */
.language-switcher {
  position: fixed;
  top: 20px;
  right: 20px;
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 20px;
  padding: 5px 10px;
  z-index: 1000;
  display: flex;
  align-items: center;
}

.language-switcher button {
  background: none;
  border: none;
  color: white;
  padding: 5px 8px;
  cursor: pointer;
  font-family: 'Montserrat', sans-serif;
  font-size: 14px;
  transition: all 0.3s ease;
}

.language-switcher button.active {
  font-weight: bold;
  color: gold;
}

.language-switcher .divider {
  color: rgba(255, 255, 255, 0.5);
  margin: 0 2px;
}

/* Extended section for multiple events */
.extended-section {
  height: auto;
  padding-bottom: 10vh;
}

.decorative-box {    
  position: relative; /* Changed from absolute to relative */
  width: 80%;    
  height: auto; /* Auto height to fit content */
  border: 2px solid rgba(255, 255, 255, 0.7);    
  border-top-left-radius: 200px;    
  border-top-right-radius: 200px;  
  z-index: 10;  
  display: flex;
  flex-direction: column;
  justify-content: flex-start; 
  align-items: center; 
  margin: 0 auto; /* Center the box */
  overflow: hidden; /* Hide any overflow */
  
  padding: 10px;  
}

/* Make the box fit its content for all events */
.decorative-box.all-events, .decorative-box.two-events {
  height: auto;
}
  
.image-container {  
  position: relative;  
  width: 100%;
  height: 70vh;
  overflow: hidden;
  border-top-left-radius: 180px;    
  border-top-right-radius: 180px;
  flex-shrink: 0; /* Prevent image from shrinking */
  margin-top: 0; /* Remove top margin */
}
  
.decorative-image {  
  width: 100%;  
  height: 100%;  
  object-fit: cover;
  object-position: center;
  border-top-left-radius: 200px;    
  border-top-right-radius: 200px;
}
         
/* EVENT TWO: Text content styling */
.event-two {
  width: 100%;
  color: white;
  padding: 3rem 2rem;
  text-align: center;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.event-item {
  width: 100%;
  margin-bottom: 1rem;
}

.event-divider {
  width: 50%;
  height: 1px;
  background-color: rgba(255, 255, 255, 0.3);
  margin: 2rem auto;
}

.event-title {
  font-family: 'Playfair Display', serif;
  font-size: 2.5rem;
  font-weight: 400;
  letter-spacing: 2px;
  margin-bottom: 1.5rem;
  text-transform: uppercase;
}

.event-date, .event-time {
  font-family: 'Montserrat', sans-serif;
  font-size: 1.25rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.event-location {
  font-family: 'Montserrat', sans-serif;
  margin-top: 1.5rem;
  font-size: 1rem;
  font-weight: 300;
}

.event-venue {
  font-family: 'Montserrat', sans-serif;
  font-size: 1.25rem;
  font-weight: 400;
  margin-bottom: 0.25rem;
}

.event-address {
  font-family: 'Montserrat', sans-serif;
  font-size: 1rem;
  font-weight: 300;
  margin-bottom: 2rem;
}

.map-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0.75rem 2rem;
  border: 1px solid white;
  border-radius: 50px;
  color: white;
  text-decoration: none;
  font-family: 'Montserrat', sans-serif;
  font-size: 1rem;
  font-weight: 400;
  transition: all 0.3s ease;
  width: 80%;
  max-width: 300px;
  margin-bottom: 1rem;
}

.map-button:hover {
  background-color: rgba(255, 255, 255, 0.2);
}

.map-icon {
  display: inline-block;
  width: 12px;
  height: 12px;
  background-color: white;
  border-radius: 50%;
  margin-right: 10px;
  position: relative;
}

/* Add this to create the black circle in the center */
.map-icon::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 4px;
  height: 4px;
  border-radius: 50%;
  background-color: black;
}

.map-icon::after {
  content: '';
  position: absolute;
  bottom: -4px;
  left: 50%;
  transform: translateX(-50%);
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-top: 6px solid white;
}

/* For a responsive box that scales better on different screens */    
@media (max-width: 768px) {    
  .decorative-box {    
    width: 90%;   
    height: 100vh;
  }
  
  .image-container {
    height: 50vh;
  }
  
  .event-two {
    padding: 1.5rem 1rem;
  }
  
  .event-title {
    font-size: 2rem;
    margin-bottom: 1rem;
  }
  
  .event-date, .event-time, .event-venue {
    font-size: 1.1rem;
  }
  
  .event-location, .event-address {
    font-size: 0.9rem;
  }
  
  .map-button {
    padding: 0.6rem 1.5rem;
    font-size: 0.9rem;
  }
  
  .event-divider {
    margin: 1.5rem auto;
  }
  
  .language-switcher {
    top: 10px;
    right: 10px;
    padding: 3px 8px;
  }
  
  .language-switcher button {
    padding: 3px 5px;
    font-size: 12px;
  }
}    
</style>
