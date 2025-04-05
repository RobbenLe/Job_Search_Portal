// src/main.js
import { createApp } from "vue";
import App from "./App.vue";
import { createPinia } from "pinia";

// Import router (if you moved it inside main.js, make sure it’s correctly defined)
import { createRouter, createWebHistory } from "vue-router";
import Home from "@/components/Home.vue";
import Login from "@/components/Authentication.vue";
import Profile from "@/components/Profile.vue";
import JobsList from "@/components/JobsList.vue";
import JobDetail from "@/components/JobDetail.vue";
import AdminDashBoard from "@/components/AdminDashBoard.vue";
import FooterComponent from "@/components/Footer.vue";

const routes = [
  { path: "/", name: "Home", component: Home },
  { path: "/login", name: "Login", component: Login },
  { path: "/profile", name: "Profile", component: Profile },
  { path: "/jobs", name: "JobsList", component: JobsList },
  { path: "/jobs/:id", name: "JobDetail", component: JobDetail, props: true },
  {
    path: "/admin/dashboard",
    name: "AdminDashBoard",
    component: AdminDashBoard,
  },
  { path: "/footer", name: "FooterComponent", component: FooterComponent },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Optionally set Axios base URL if needed
import axios from "axios";
axios.defaults.baseURL = "http://localhost";

// Create and mount the Vue app
const app = createApp(App);
app.use(router);
app.use(createPinia());
app.mount("#app");
