<script setup>
import { onMounted, ref, computed } from "vue";    
import gsap from "gsap";
import ScrollTrigger from "gsap/ScrollTrigger";

// Register ScrollTrigger plugin
gsap.registerPlugin(ScrollTrigger);
    
const boxSectionRef = ref(null);   
const imageRef = ref(null);  
const eventDetailsRef = ref(null);

const props = defineProps({
  code: String,
});

// Define all three events
const allEvents = [
  {
    code: "23",
    title: "Memadik/Nyuwaka",
    date: "Rabu, 23 Maret 2026",
    time: "Pukul 10.00 - Selesai",
    venue: "Desa Jagapati",
    address: "Jl. jagapati, Pertima, Badung",
    mapsUrl: "https://maps.app.goo.gl/mhtjeGGvWJd1arz76"
  },
  {
    code: "25",
    title: "PAWIWAHAN",
    date: "Rabu, 25 Maret 2026",
    time: "Pukul 10.00 - Selesai",
    venue: "Desa Asak",
    address: "Jl. Asak, Pertima, Karangasem",
    mapsUrl: "https://maps.app.goo.gl/PAWztaF2QjtRPNdc6"
  },
  {
    code: "28",
    title: "Resepsi",
    date: "Rabu, 28 Maret 2026",
    time: "Pukul 18.00 - Selesai",
    venue: "Villa D'carik",
    address: "Jalan Kaswari, Penatih, Denpasar Timur, Bali",
    mapsUrl: "https://maps.app.goo.gl/85hCXpMEsPaby18m7?g_st=iw"
  }
];

// Determine which events to show based on code
const eventsToShow = computed(() => {
  if (!props.code) {
    // If no code is provided, show all events
    return allEvents;
  } else {
    // Otherwise, show only the event that matches the code
    const event = allEvents.find(event => event.code === props.code);
    return event ? [event] : [allEvents[2]]; // Default to Resepsi if code doesn't match
  }
});

// Compute section height based on number of events
const sectionHeight = computed(() => {
  return eventsToShow.value.length > 1 ? 'auto' : 'min-h-screen';
});

// Compute container class based on code
const containerClass = computed(() => {
  return !props.code ? 'container-wrapper pb-40' : 'container-wrapper';
});

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
  <div ref="containerRef" :class="containerClass">
    <section    
      ref="boxSectionRef"    
      id="box-section"    
      :class="[
        'flex items-center justify-center relative z-[2] overflow-visible pt-20 mt-12 mb-12',
        sectionHeight,
        {'extended-section': eventsToShow.length > 1}
      ]"
    >    
      <!-- Decorative box with rounded top corners (radius 200px) -->    
      <div ref="decorativeBoxRef" class="decorative-box" :class="{ 'all-events': eventsToShow.length > 1 }">  
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
            <p class="event-location">Bertempat di</p>
            <p class="event-venue">{{ event.venue }}</p>
            <p class="event-address">{{ event.address }}</p>
            
            <a
                :href="event.mapsUrl"
                target="_blank"
                class="map-button">
              <span class="map-icon"></span> Google Maps
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

/* Extended section for multiple events */
.extended-section {
  min-height: 200vh; /* Minimum height for multiple events */
  height: auto;
  padding-bottom: 10vh;
}

.decorative-box {    
  position: absolute;    
  top: 0;    
  left: 50%;    
  transform: translateX(-50%);    
  width: 80%;    
  height: 150vh;    
  border: 2px solid rgba(255, 255, 255, 0.7);    
  border-top-left-radius: 200px;    
  border-top-right-radius: 200px;  
  z-index: 10;  
  display: flex;
  flex-direction: column;
  justify-content: flex-start; 
  align-items: center; 
  padding-top: 10vh;
}

/* Make the box taller when showing all events */
.decorative-box.all-events {
  height: auto; /* Auto height based on content */
  min-height: 250vh; /* Minimum height */
  bottom: 0; /* Extend to bottom */
}
  
.image-container {  
  position: relative;  
  width: 100%;
  height: 70vh;
  overflow: hidden;
  border-top-left-radius: 180px;    
  border-top-right-radius: 180px;
  flex-shrink: 0; /* Prevent image from shrinking */
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
  margin-top: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
  /* Removed scroll functionality */
}

.event-item {
  width: 100%;
  margin-bottom: 2rem;
}

.event-divider {
  width: 50%;
  height: 1px;
  background-color: rgba(255, 255, 255, 0.3);
  margin: 3rem auto;
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
    padding: 10px;   
    height: 100vh;
  }
  
  .decorative-box.all-events {
    min-height: 200vh;
  }
  
  .extended-section {
    min-height: 180vh;
  }
  
  .image-container {
    height: 50vh;
  }
  
  .event-two {
    padding: 1.5rem 1rem;
    margin-top: 1rem;
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
    margin: 2rem auto;
  }
}    
</style>
