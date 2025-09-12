<template>
  <TransitionRoot appear :show="isVisible" as="template">
    <Dialog @close="close" class="fixed inset-0 overflow-hidden z-50">
      <!-- Overlay -->
      <TransitionChild
        as="template"
        enter="transition-opacity ease-in-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="transition-opacity ease-in-out duration-300"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <DialogOverlay class="absolute inset-0 bg-black/30 backdrop-blur-sm" />
      </TransitionChild>

      <!-- Modal Content -->
      <TransitionChild
        enter="transform ease-in-out transition-transform duration-300"
        enter-from="translate-y-full"
        enter-to="translate-y-0"
        leave="transform ease-in-out transition-transform duration-300"
        leave-from="translate-y-0"
        leave-to="translate-y-full"
        as="template"
      >
        <div class="fixed inset-0 overflow-y-auto">
          <div class="min-h-screen flex items-center justify-center">
            <!-- Sesuaikan dengan layout utama -->
            <DialogPanel class="bg-white w-full max-w-sm mx-auto relative  overflow-hidden  rounded-lg" >
              <!-- Header -->
              <div
                class="flex justify-between items-center px-4 py-2 bg-white  rounded-t-md font-bold  sticky top-0 z-10 text-gray-500"
              >
                <DialogTitle>
                  <slot name="title" />
                </DialogTitle>
                <div class="p-1">
                  <button @click="close" v-if="closeable">
                    <mdicon name="close" class="text-gray-500" width="20" height="20" />
                  </button>
                </div>
              </div>

              <!-- Content -->
              <div class="pt-2 pb-4 overflow-hidden">
                <slot name="content" />
              </div>
            </DialogPanel>
          </div>
        </div>
      </TransitionChild>
    </Dialog>
  </TransitionRoot>
</template>
<script setup>
import { computed, onMounted, onUnmounted, watch, ref } from "vue";

import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogOverlay,
  DialogTitle,
  DialogDescription,
} from "@headlessui/vue";

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  closeable: {
    type: Boolean,
    default: true,
  },
  scrollable: {
    type: Boolean,
    default: false,
  },
  closebutton: {
    type: Boolean,
    default: true,
  },
  maxWidth: {
    type: String,
    validator: (value) =>
      [
        "max-w-sm",
        "max-w-lg",
        "max-w-md",
        "max-w-xl",
        "max-w-2xl",
        "max-w-7xl",
        "max-w-4xl",
      ].includes(value),
    default: "max-w-sm",
  },
});

const emit = defineEmits(["close"]);

// Tambahkan state internal untuk mengontrol visibilitas dialog
const isVisible = ref(props.show);

// Watch props.show untuk mengupdate isVisible
watch(
  () => props.show,
  (value) => {
    if (value) {
      isVisible.value = true;
    } else {
      // Jangan langsung menutup, biarkan animasi berjalan dulu
      setTimeout(() => {
        isVisible.value = false;
      }, 100); // Sesuaikan dengan durasi animasi Anda (300ms)
    }
  },
  { immediate: true }
);

const close = () => {
  if (props.closeable) {
    emit("close");
  }
};

const closeOnEscape = (e) => {
  if (e.key === "Escape" && props.show) {
    close();
  }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));

onUnmounted(() => {
  document.removeEventListener("keydown", closeOnEscape);
  document.body.style.overflow = null;
});
</script>
