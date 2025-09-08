import {
    nextTick,
    ref
} from "vue";

export const show = ref(false);
export const setShow = (value) => {
    // localStorage.setItem("showPopup", value);
    show.value = value;
};
export const fetchData = () => {
    nextTick(() => {
        // console.log(JSON.parse(localStorage.getItem("showPopup")));
        // if (JSON.parse(localStorage.getItem("showPopup")) === false) {
        //     show.value = false;
        //     console.log('not show');
        // } else {
        //     show.value = true;
        //     console.log('show');
        // }

        // console.log("fetchData called");

        show.value = true;
        // console.log(show.value)

    })

}
