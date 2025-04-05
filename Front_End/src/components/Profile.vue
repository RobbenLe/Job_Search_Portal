<template>
  <div class="profile-container">
    <h2><i class="fas fa-user-circle"></i> User Profile</h2>

    <div v-if="error" class="alert error">{{ error }}</div>
    <div v-else-if="loading" class="alert loading">Loading user info...</div>

    <!-- View Mode -->
    <div v-else-if="!isEditing" class="profile-view">
      <div class="profile-field">
        <i class="fas fa-id-badge icon"></i>
        <span><strong>ID:</strong> {{ user.id }}</span>
      </div>
      <div class="profile-field">
        <i class="fas fa-envelope icon"></i>
        <span><strong>Email:</strong> {{ user.email }}</span>
      </div>
      <div class="profile-field">
        <i class="fas fa-user-tag icon"></i>
        <span><strong>Role:</strong> {{ user.role }}</span>
      </div>

      <button class="edit-button" @click="startEdit">
        <i class="fas fa-edit"></i> Edit Profile
      </button>
    </div>

    <!-- Edit Mode -->
    <div v-else class="profile-edit">
      <form @submit.prevent="updateProfile">
        <div class="form-group">
          <label for="email"><i class="fas fa-envelope me-2"></i>Email</label>
          <input id="email" v-model="editEmail" type="email" required />
        </div>

        <div class="form-group">
          <label for="password"
            ><i class="fas fa-key me-2"></i>New Password</label
          >
          <input
            id="password"
            v-model="editPassword"
            type="password"
            placeholder="Leave blank to keep current password"
          />
        </div>

        <div class="form-actions">
          <button type="submit" class="save-button">
            <i class="fas fa-save"></i> Save
          </button>
          <button type="button" class="cancel-button" @click="cancelEdit">
            <i class="fas fa-times-circle"></i> Cancel
          </button>
        </div>

        <p v-if="updateError" class="alert error">{{ updateError }}</p>
      </form>
    </div>
  </div>
  <FooterComponent />
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";
import FooterComponent from "@/components/Footer.vue";

export default {
  name: "Profile",
  components: {
    FooterComponent,
  },
  setup() {
    const user = ref(null);
    const loading = ref(true);
    const error = ref("");
    const isEditing = ref(false);
    const updateError = ref("");
    const editEmail = ref("");
    const editPassword = ref("");

    const fetchUser = async () => {
      try {
        const response = await axios.get("/auth/me");
        user.value = response.data;
      } catch (err) {
        error.value = err.response?.data?.message || err.message;
      } finally {
        loading.value = false;
      }
    };

    const startEdit = () => {
      if (user.value) {
        isEditing.value = true;
        editEmail.value = user.value.email;
        editPassword.value = "";
      }
    };

    const cancelEdit = () => {
      isEditing.value = false;
      updateError.value = "";
    };

    const updateProfile = async () => {
      updateError.value = "";
      try {
        const payload = { email: editEmail.value };

        if (editPassword.value.trim() !== "") {
          payload.password = editPassword.value;
        }

        const response = await axios.put("/auth/update", payload);
        await fetchUser();
        isEditing.value = false;
      } catch (err) {
        if (err.response && err.response.data && err.response.data.error) {
          updateError.value = err.response.data.error;
        } else {
          updateError.value = "An unexpected error occurred.";
        }
      }
    };

    onMounted(fetchUser);

    return {
      user,
      loading,
      error,
      isEditing,
      updateError,
      editEmail,
      editPassword,
      startEdit,
      cancelEdit,
      updateProfile,
    };
  },
};
</script>

<style scoped>
.profile-container {
  max-width: 700px;
  margin: 40px auto;
  padding: 35px;
  background: #ffffff;
  border-radius: 10px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

h2 {
  text-align: center;
  margin-bottom: 30px;
  font-size: 2rem;
  color: #333;
}

.profile-view .profile-field {
  display: flex;
  align-items: center;
  gap: 12px;
  font-size: 1.1rem;
  margin-bottom: 15px;
  color: #444;
}

.icon {
  color: #0d6efd;
  font-size: 1.3rem;
}

.alert {
  text-align: center;
  font-weight: 500;
  margin: 15px 0;
}

.alert.error {
  color: #dc3545;
}

.alert.loading {
  color: #0d6efd;
}

.edit-button,
.save-button,
.cancel-button {
  padding: 10px 20px;
  margin: 12px 6px;
  border: none;
  font-size: 1rem;
  border-radius: 5px;
  cursor: pointer;
  transition: background 0.3s ease;
}

.edit-button {
  background-color: #0d6efd;
  color: #fff;
}
.edit-button:hover {
  background-color: #0256c7;
}

.form-group {
  margin-bottom: 18px;
}

.form-group label {
  font-weight: bold;
  display: block;
  margin-bottom: 5px;
}

input[type="email"],
input[type="password"] {
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  border-radius: 6px;
  border: 1px solid #ccc;
}

.form-actions {
  text-align: right;
}

.save-button {
  background-color: #198754;
  color: white;
}
.save-button:hover {
  background-color: #126b45;
}

.cancel-button {
  background-color: #dc3545;
  color: white;
}
.cancel-button:hover {
  background-color: #b52d3d;
}
</style>
