import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

import { createApp } from "vue";
import UsersList from "./components/UsersList.vue";
import CoursesList from "./components/CoursesList.vue";
import AssignmentsList from "./components/AssignmentsList.vue";
import Toast, { POSITION } from "vue-toastification";
import "vue-toastification/dist/index.css";

const app = createApp({
    data() {
        return {
            role: "student", // or use a dynamic value from old('role')
        };
    },
});

app.use(Toast, {
    position: POSITION.BOTTOM_RIGHT,
    timeout: 5000,
    closeOnClick: true,
    pauseOnHover: true,
});

app.component("users-list", UsersList);
app.component("courses-list", CoursesList);
app.component("assignments-list", AssignmentsList);
app.mount("#app");
