// Utils/index.js
import { ref, nextTick } from "vue";

export const show = ref(false);
export const animationsInitialized = ref(false); // Add new ref for tracking
export const amplop = ref(false);
export const setAmplop = (value) => {
    amplop.value = value;
}
export const setShow = (value) => {
    show.value = value;
    if (!value) {
        // Ketika popup ditutup, set animationsInitialized ke true
        animationsInitialized.value = true;
    }
};

export const fetchData = () => {
    return nextTick(() => {
        show.value = true;
    });
};