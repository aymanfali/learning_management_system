import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();


import { createApp } from "vue";
import TestCase from "./components/TestCase.vue";
import UsersList from "./components/UsersList.vue";


const app = createApp({
    data() {
        return {
            role: "student", // or use a dynamic value from old('role')
        };
    },
});
app.component("test-case", TestCase);
app.component("users-list", UsersList);
app.mount("#app");


