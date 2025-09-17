<script setup>
import Popup from "@/Components/Popup.vue";
import { ref } from "vue";
import gsap from "gsap";

const props = defineProps({
  show: Boolean,
});

const emit = defineEmits(["close"]);
const close = () => {
  emit("close");
};
const isCopied = ref(false);
const copyTimeout = ref(null);
const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText("7730778842");
    isCopied.value = true;

    // Clear existing timeout if any
    if (copyTimeout.value) clearTimeout(copyTimeout.value);

    // Reset after 5 seconds
    copyTimeout.value = setTimeout(() => {
      isCopied.value = false;
    }, 5000);

    // Animate the copy icon
    gsap.from(".copy-icon", {
      scale: 0.5,
      rotation: 360,
      duration: 0.5,
      ease: "back.out",
    });
  } catch (err) {
    console.error("Failed to copy:", err);
  }
};
</script>
<template>
  <Popup :show="show" @close="close" max-width="max-w-sm">
    <template #title>  </template>
    <template #content>
      <div class="flex flex-col gap-4 pb-4">
        <div class="w-full flex items-center justify-center gap-4">
          <div ref="bcaRef" class=" flex justify-start items-center gap-4">
            <div>
              <img src="/assets/images/card.png" alt="" class="max-w-[300px]" />
            </div>
          </div>
          <div ref="bankInfoRef" class="flex items-center">
            <button
              @click="copyToClipboard"
              class="text-gray-500 px-2 py-1 rounded-full hover:bg-gray-100 transition-all duration-300"
            >
              <mdicon
                :name="isCopied ? 'check-all' : 'content-copy'"
                class="copy-icon transition-all duration-300"
                :class="{ 'text-green-400': isCopied }"
                width="15"
              />
            </button>
          </div>
        </div>
      </div>
    </template>
  </Popup>
</template>
