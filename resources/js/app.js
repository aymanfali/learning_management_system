import "./bootstrap";

import { createApp } from "vue";
import TestCase from "./components/TestCase.vue";

const app = createApp({});
app.component("test-case", TestCase);
app.mount("#app");
