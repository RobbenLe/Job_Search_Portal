<template>
  <div>
    <nav class="navbar">
      <div class="navbar-container">
        <!-- Left Side: Logo + Main Nav -->
        <div class="navbar-left">
          <div class="logo">
            <router-link to="/">
              <i class="fas fa-briefcase"></i> Job Portal
            </router-link>
          </div>

          <ul class="nav-links">
            <li>
              <router-link to="/"><i class="fas fa-home"></i> Home</router-link>
            </li>
            <li>
              <router-link to="/jobs"
                ><i class="fas fa-search"></i> Jobs</router-link
              >
            </li>
            <li v-if="isLoggedIn && user?.role === 'user'">
              <router-link to="/profile"
                ><i class="fas fa-user"></i> Profile</router-link
              >
            </li>
            <li v-if="isLoggedIn && user?.role === 'admin'">
              <router-link to="/admin/dashboard"
                ><i class="fas fa-cogs"></i> Admin</router-link
              >
            </li>
          </ul>
        </div>

        <!-- Right Side: Auth Actions -->
        <div class="auth-actions">
          <router-link v-if="!isLoggedIn" to="/login" class="auth-btn">
            <i class="fas fa-sign-in-alt"></i> Login / Register
          </router-link>
          <button v-else @click="logout" class="auth-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </div>
      </div>
    </nav>

    <!-- Router View -->
    <router-view />
  </div>
</template>

<script>
import { computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
import { useAuthStore } from "@/stores/authStore";

export default {
  name: "App",
  setup() {
    const router = useRouter();
    const authStore = useAuthStore();

    const isLoggedIn = computed(() => authStore.isLoggedIn);
    const user = computed(() => authStore.user);

    onMounted(async () => {
      const token = localStorage.getItem("token");
      if (token) {
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;
        try {
          await authStore.fetchMe(); // Get user info
        } catch (err) {
          authStore.logout(); // Clear bad token
        }
      }
    });

    const logout = () => {
      authStore.logout();
      router.push("/login"); // Redirect to login
    };

    return {
      isLoggedIn,
      user,
      logout,
    };
  },
};
</script>

<style>
.navbar {
  background-color: #038726; /* green background */
  padding: 16px 32px;
  color: #fff;
  font-family: "Segoe UI", sans-serif;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.navbar-container {
  display: flex;
  justify-content: space-between; /* push auth button to far right */
  align-items: center;
  flex-wrap: wrap;
  width: 100%;
}

.navbar-left {
  display: flex;
  align-items: center;
  gap: 48px;
  flex: 1; /* take available width */
}

/* Force auth-actions to stick to right */
.auth-actions {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-left: auto;
}

.logo a {
  font-size: 1.8rem;
  font-weight: bold;
  color: #fff;
  text-decoration: none;
}

.logo i {
  margin-right: 8px;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 32px; /* Increased spacing between links */
  padding: 0;
  margin: 0;
}

.nav-links a {
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  font-size: 1.05rem;
  transition: color 0.3s;
}

.nav-links a:hover {
  color: #c0ffd4; /* Light green on hover */
}

/* Auth Actions (right side) */
.auth-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.auth-btn {
  background-color: #0d6efd;
  color: #fff;
  border: none;
  padding: 10px 18px;
  border-radius: 6px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: background 0.3s ease;
}

.auth-btn:hover {
  background-color: #0b5ed7;
}

/* Logout button style */
.auth-btn.logout {
  background-color: transparent;
  border: 1px solid #ffffffaa;
  color: #fff;
}

.auth-btn.logout:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

/* Responsive */
@media (max-width: 768px) {
  .navbar-container {
    flex-direction: column;
    align-items: stretch;
    gap: 20px;
  }

  .navbar-left {
    flex-direction: column;
    align-items: flex-start;
  }

  .nav-links {
    flex-direction: column;
    gap: 12px;
  }

  .auth-actions {
    align-self: flex-end;
  }
}
</style>
