<template>
  <div class="jobs-list-container">
    <h1 class="page-title">Explore Job Opportunities</h1>

    <div class="filter-container">
      <input type="text" placeholder="🔍 Search job..." v-model="searchText" />
    </div>

    <p v-if="error" class="error-message">⚠️ {{ error }}</p>
    <p v-if="loading" class="loading-message">⏳ Loading jobs...</p>

    <div v-if="!loading && !error" class="jobs-grid">
      <div v-for="job in jobs" :key="job.id" class="job-card">
        <router-link :to="`/jobs/${job.id}`" class="job-link">
          <h2 class="job-title">{{ job.title }}</h2>
          <p class="job-type">{{ job.jobType }}</p>
          <p class="job-location">📍 {{ job.location }}</p>
        </router-link>
      </div>
    </div>
  </div>
  <FooterComponent />
</template>

<script>
import { ref, onMounted, watch } from "vue";
import axios from "axios";
import debounce from "lodash.debounce";
import { API_ENDPOINTS } from "@/config";
import FooterComponent from "@/components/Footer.vue";

export default {
  components: {
    FooterComponent,
  },
  setup() {
    const jobs = ref([]);
    const jobTypes = ref([]);
    const selectedJobType = ref("");
    const searchText = ref("");
    const loading = ref(false);
    const error = ref("");

    const fetchJobs = async () => {
      loading.value = true;
      error.value = "";
      try {
        let endpoint = API_ENDPOINTS.jobs;
        let params = { page: 1 };

        if (searchText.value) {
          endpoint = `${API_ENDPOINTS.jobs}/search`;
          params.search = searchText.value;
        } else if (selectedJobType.value) {
          params.jobType = selectedJobType.value;
        }

        const response = await axios.get(endpoint, { params });
        jobs.value = response.data;
      } catch (err) {
        error.value = err.response?.data?.error || err.message;
      } finally {
        loading.value = false;
      }
    };

    const debouncedFetchJobs = debounce(fetchJobs, 500);

    watch(searchText, debouncedFetchJobs);
    watch(selectedJobType, fetchJobs);

    const fetchJobTypes = async () => {
      try {
        const response = await axios.get(`${API_ENDPOINTS.jobs}/types`);
        jobTypes.value = response.data;
      } catch (err) {
        console.error("Failed to fetch job types:", err);
      }
    };

    onMounted(() => {
      fetchJobs();
      fetchJobTypes();
    });

    return {
      jobs,
      jobTypes,
      selectedJobType,
      searchText,
      loading,
      error,
    };
  },
};
</script>

<style scoped>
.jobs-list-container {
  padding: 30px;
  max-width: 1200px;
  margin: 0 auto;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.page-title {
  text-align: center;
  font-size: 2.5rem;
  color: #333;
  margin-bottom: 25px;
}

.filter-container {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-bottom: 30px;
}

.filter-container select,
.filter-container input {
  padding: 10px 15px;
  border-radius: 5px;
  border: 1px solid #ddd;
  font-size: 1rem;
  cursor: pointer;
}

.error-message,
.loading-message {
  text-align: center;
  font-size: 1.2rem;
  margin-top: 20px;
}

.jobs-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 25px;
}

.job-card {
  background: #ffffff;
  border: 1px solid #ececec;
  padding: 20px;
  border-radius: 12px;
  text-align: center;
  transition: transform 0.3s, box-shadow 0.3s;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.job-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.job-link {
  text-decoration: none;
  color: inherit;
}

.job-title {
  font-size: 1.8rem;
  margin-bottom: 10px;
  color: #0056b3;
}

.job-type {
  font-size: 1rem;
  font-weight: bold;
  color: #28a745;
  margin-bottom: 8px;
}

.job-location {
  font-size: 1rem;
  color: #555;
}
</style>
