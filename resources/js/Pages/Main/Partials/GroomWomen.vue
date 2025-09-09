<script setup>
import { onMounted, ref } from "vue";
const imageLoaded = ref(false);

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
onMounted(async () => {
  try {
    await preloadImage("/assets/images/women-groom.webp");

    // Handle focus event
  } catch (error) {
    console.error("Error loading image:", error);
  }
});
</script>
<template>
  <section
    ref="groomWomenRef"
    id="groomWomen"
    class="min-h-screen flex items-start justify-center relative bg-cover bg-center bg-gray-200 z-[1]  relative"
    :style="{
      backgroundImage: imageLoaded ? 'url(/assets/images/women-groom.webp)' : 'none',
      backgroundColor: '#4D4D4D',
    }"
  >
    <div class="w-full h-screen flex items-end justify-start z-[2] px-4 py-12">
      <div class="flex flex-col gap-4 p-4">
        <h2 class="text-xl uppercase font-wittgenstein text-white drop-shadow-lg">The Groom</h2>
        <div class="border-t w-48  border-white"></div>
        <h1 class="text-3xl font-wittgenstein text-white">Ni Kadek Astri Cahyani</h1>
        <p class="font-wittgenstein text-white text-md">Putri Kedua dari Bapak I Ketut Sudiarta & Ibu Ni Ketut Sri Yuliani</p>
         <a
          href="https://www.instagram.com/"
          target="_blank"
          class="transition-all ease-in flex items-center gap-2 font-wittgenstein bg-green-700 text-white px-4 border-0 hover:border border-white py-1 rounded-lg w-fit text-xs"
          ><mdicon name="instagram"  width="15" height="15"/> Instagram</a
        >
      </div>
    </div>
    <div class="absolute inset-0 bg-[#4D4D4D]/30"></div>
  </section>
</template>
