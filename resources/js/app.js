import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();


import { createApp } from "vue";
import TestCase from "./components/TestCase.vue";

const app = createApp({
    data() {
        return {
            role: "student", // or use a dynamic value from old('role')
        };
    },
});
app.component("test-case", TestCase);
app.mount("#app");


