// JobDetail.vue
<template>
  <div class="page-bg py-5">
    <div class="container">
      <div v-if="error" class="alert alert-danger text-center">{{ error }}</div>
      <div v-else-if="loading" class="text-center fs-5 text-muted mb-4">
        Loading Job...
      </div>

      <div v-else-if="job" class="card job-card shadow-sm">
        <div class="card-body">
          <h2 class="card-title mb-1">{{ job.title }}</h2>
          <h5 class="text-muted mb-4">{{ job.jobType }}</h5>

          <p class="mb-2">
            <i class="fas fa-map-marker-alt me-2 text-primary"></i>
            <strong>Location:</strong> {{ job.location }}
          </p>
          <p class="mb-3">
            <i class="fas fa-dollar-sign me-2 text-primary"></i>
            <strong>Salary:</strong> ${{ job.salary }}
          </p>

          <div class="mb-4">
            <i class="fas fa-file-alt me-2 text-primary"></i>
            <strong>Description:</strong>
            <div class="ms-4 mt-1">{{ job.description }}</div>
          </div>

          <div>
            <i class="fas fa-list me-2 text-primary"></i>
            <strong>Requirements:</strong>
            <ul class="list-unstyled ms-4 mt-2">
              <li v-for="(req, index) in splitRequirements" :key="index">
                • {{ req }}
              </li>
            </ul>
          </div>

          <button class="btn btn-primary mt-4" @click="toggleApplyForm">
            {{ showApplyForm ? "Cancel Application" : "Apply Now" }}
          </button>

          <transition name="fade">
            <div v-if="showApplyForm" class="mt-4 p-3 border rounded bg-light">
              <h4 class="text-center mb-3">Apply for this Job</h4>

              <div
                v-if="successMessage"
                class="alert alert-success text-center"
              >
                {{ successMessage }}
              </div>

              <form @submit.prevent="submitApplication">
                <div class="mb-3">
                  <label for="applicantName" class="form-label">
                    <i class="fas fa-user me-2"></i>Name
                  </label>
                  <input
                    id="applicantName"
                    v-model="application.name"
                    type="text"
                    required
                    class="form-control"
                  />
                </div>

                <div class="mb-3">
                  <label for="applicantEmail" class="form-label">
                    <i class="fas fa-envelope me-2"></i>Email
                  </label>
                  <input
                    id="applicantEmail"
                    v-model="application.email"
                    type="email"
                    required
                    class="form-control"
                  />
                </div>

                <div class="mb-3">
                  <label for="coverLetter" class="form-label">
                    <i class="fas fa-pencil-alt me-2"></i>Cover Letter
                  </label>
                  <textarea
                    id="coverLetter"
                    v-model="application.coverLetter"
                    rows="4"
                    class="form-control"
                    placeholder="Write a brief cover letter"
                  ></textarea>
                </div>

                <div class="mb-3">
                  <label for="resume" class="form-label">
                    <i class="fas fa-upload me-2"></i>Resume
                  </label>
                  <input
                    id="resume"
                    type="file"
                    @change="handleFileUpload"
                    class="form-control"
                    required
                  />
                </div>

                <div class="text-end">
                  <button type="submit" class="btn btn-success">
                    Submit Application
                  </button>
                </div>
              </form>
            </div>
          </transition>
        </div>
      </div>
    </div>
  </div>
  <FooterComponent />
</template>

<script>
import { ref, computed, onMounted, watchEffect } from "vue";
import { useAuthStore } from "@/stores/authStore";
import axios from "axios";
import FooterComponent from "@/components/Footer.vue";

export default {
  name: "JobDetail",
  components: {
    FooterComponent,
  },
  props: ["id"],

  setup(props) {
    const authStore = useAuthStore();
    const user = computed(() => authStore.user);
    const userId = computed(() => user.value?.id || null);

    const job = ref(null);
    const error = ref("");
    const loading = ref(false);
    const showApplyForm = ref(false);
    const successMessage = ref("");

    const application = ref({
      name: "",
      email: "",
      coverLetter: "",
      resume: null,
    });

    // 🔁 Auto-fill name/email if logged in
    watchEffect(() => {
      if (user.value) {
        application.value.name = user.value.name || "";
        application.value.email = user.value.email || "";
      }
    });

    const splitRequirements = computed(() => {
      if (!job.value?.requirements) return [];
      return job.value.requirements
        .split("\n")
        .map((line) => line.trim())
        .filter((line) => line);
    });

    const fetchJob = async () => {
      loading.value = true;
      error.value = "";
      try {
        const response = await axios.get(`/api/jobs/${props.id}`);
        job.value = response.data;
      } catch (err) {
        error.value = err.response?.data?.error || err.message;
      } finally {
        loading.value = false;
      }
    };

    const toggleApplyForm = () => {
      showApplyForm.value = !showApplyForm.value;
      successMessage.value = "";
    };

    const handleFileUpload = (event) => {
      application.value.resume = event.target.files[0];
    };

    const submitApplication = async () => {
      const formData = new FormData();
      formData.append("name", application.value.name);
      formData.append("email", application.value.email);
      formData.append("coverLetter", application.value.coverLetter);
      formData.append("job_id", props.id);

      if (userId.value) {
        formData.append("user_id", userId.value);
      }

      if (application.value.resume) {
        formData.append("resume", application.value.resume);
      }

      try {
        const response = await axios.post("/api/applications", formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });

        if (response.status === 200) {
          successMessage.value =
            response.data.message || "Application submitted successfully!";
          application.value = {
            name: user.value?.name || "",
            email: user.value?.email || "",
            coverLetter: "",
            resume: null,
          };
        } else {
          alert("Application failed.");
        }
      } catch (err) {
        alert(
          "Error submitting application: " +
            (err.response?.data?.error || err.message)
        );
      }
    };

    onMounted(fetchJob);

    return {
      job,
      error,
      loading,
      showApplyForm,
      toggleApplyForm,
      application,
      handleFileUpload,
      submitApplication,
      splitRequirements,
      successMessage,
    };
  },
};
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.page-bg {
  background-color: #f8f9fa;
  min-height: 100vh;
}

.job-card {
  margin-top: 2rem;
  border-left: 5px solid #0d6efd;
  border-radius: 0.25rem;
}

.card-title {
  font-size: 1.75rem;
  margin-bottom: 0.5rem;
}
.text-muted {
  font-size: 1.1rem;
}
.list-unstyled li {
  margin-bottom: 5px;
}
.alert {
  margin-top: 1rem;
  font-weight: bold;
}
</style>
