<template>
  <div class="admin-dashboard">
    <h2 class="title"><i class="fas fa-tools me-2"></i> Admin Dashboard</h2>

    <!-- JOB MANAGEMENT -->
    <section class="card">
      <h3><i class="fas fa-briefcase me-2"></i> Manage Jobs</h3>
      <button class="btn btn-success mb-3" @click="showCreateModal = true">
        <i class="fas fa-plus"></i> Create Job
      </button>

      <table class="styled-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Description</th>
            <th>Requirements</th>
            <th>Location</th>
            <th>Salary</th>
            <th>Type</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="job in jobs" :key="job.id">
            <td>{{ job.title }}</td>
            <td>{{ job.description }}</td>
            <td>{{ job.requirements }}</td>
            <td>{{ job.location }}</td>
            <td>${{ job.salary }}</td>
            <td>{{ job.jobType }}</td>
            <td>
              <button class="btn btn-warning" @click="startEdit(job)">
                <i class="fas fa-edit"></i>
              </button>
              <button class="btn btn-danger" @click="deleteJob(job.id)">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- CREATE JOB MODAL -->
    <div v-if="showCreateModal" class="modal">
      <div class="modal-content wide-modal">
        <h4><i class="fas fa-plus-circle"></i> Create Job</h4>
        <form @submit.prevent="submitCreateJob">
          <input v-model="newJob.title" placeholder="Title" required />
          <textarea
            v-model="newJob.description"
            placeholder="Description"
            required
          ></textarea>
          <textarea
            v-model="newJob.requirements"
            placeholder="Requirements"
            required
          ></textarea>
          <input v-model="newJob.location" placeholder="Location" required />
          <input
            v-model="newJob.salary"
            type="number"
            placeholder="Salary"
            required
          />
          <input
            v-model="newJob.jobType"
            placeholder="Job Type (e.g. Full-Time, Internship, etc)"
            required
          />

          <div class="modal-actions">
            <button type="submit" class="btn btn-success">Create</button>
            <button
              type="button"
              class="btn btn-secondary"
              @click="showCreateModal = false"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Compact EDIT JOB MODAL -->
    <div v-if="editingJob" class="modal">
      <div class="modal-content compact-modal">
        <h4><i class="fas fa-pen"></i> Edit Job</h4>
        <form @submit.prevent="submitEdit">
          <h5 class="form-section">Basic Info</h5>

          <div class="form-group">
            <label>Title</label>
            <input
              v-model="editingJob.title"
              placeholder="Job Title"
              required
            />
          </div>

          <div class="form-group">
            <label>Job Type</label>
            <input
              v-model="editingJob.jobType"
              placeholder="e.g., Full-Time"
              required
            />
          </div>

          <h5 class="form-section">Details</h5>

          <div class="form-group">
            <label>Description</label>
            <textarea
              v-model="editingJob.description"
              rows="2"
              placeholder="Job Description"
              required
            ></textarea>
          </div>

          <div class="form-group">
            <label>Requirements</label>
            <textarea
              v-model="editingJob.requirements"
              rows="2"
              placeholder="Job Requirements"
              required
            ></textarea>
          </div>

          <h5 class="form-section">Location & Salary</h5>

          <div class="form-row">
            <div class="form-group">
              <label>Location</label>
              <input
                v-model="editingJob.location"
                placeholder="Location"
                required
              />
            </div>

            <div class="form-group">
              <label>Salary</label>
              <input
                v-model="editingJob.salary"
                type="number"
                placeholder="$"
                required
              />
            </div>
          </div>

          <div class="modal-actions">
            <button type="submit" class="btn btn-success">Save</button>
            <button
              type="button"
              class="btn btn-secondary"
              @click="editingJob = null"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- APPLICATIONS -->
    <section class="card mt-5">
      <h3><i class="fas fa-file-alt me-2"></i> Applications</h3>
      <table class="styled-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Applicant</th>
            <th>Email</th>
            <th>Resume</th>
            <th>Cover Letter</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="app in applications" :key="app.application_id">
            <td>{{ app.job_title }}</td>
            <td>{{ app.name }}</td>
            <td>{{ app.email }}</td>
            <td>
              <a
                :href="`${FILE_BASE_URL}${app.resume_path}`"
                target="_blank"
                class="btn-resume"
              >
                <i class="fas fa-file-pdf"></i> View Resume
              </a>
            </td>
            <td>{{ app.cover_letter }}</td>
            <td>
              <select v-model="app.application_status" class="form-select">
                <option
                  v-for="status in statusOptions"
                  :key="status"
                  :value="status"
                >
                  {{ status }}
                </option>
              </select>
            </td>
            <td>
              <button
                class="btn btn-success"
                @click="updateApplicationStatus(app)"
              >
                Update
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </section>
  </div>
  <FooterComponent />
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";
import { API_BASE_URL, FILE_BASE_URL } from "@/config";
import FooterComponent from "@/components/Footer.vue";

export default {
  name: "AdminDashboard",
  components: {
    FooterComponent,
  },
  setup() {
    const jobs = ref([]);
    const applications = ref([]);
    const editingJob = ref(null);
    const showCreateModal = ref(false);
    const newJob = ref({
      title: "",
      description: "",
      requirements: "",
      location: "",
      salary: "",
      jobType: "",
    });

    const statusOptions = ["pending", "reviewed", "accepted", "rejected"];
    const BASE_FILE_URL = window.location.origin; // ← FULLY DYNAMIC

    const fetchJobs = async () => {
      const res = await axios.get("/api/jobs");
      jobs.value = res.data;
    };

    const fetchApplications = async () => {
      const res = await axios.get("/api/admin/applications");
      applications.value = res.data;
    };

    const deleteJob = async (id) => {
      if (confirm("Are you sure?")) {
        await axios.delete(`/api/jobs/${id}`);
        await fetchJobs();
      }
    };

    const startEdit = (job) => {
      editingJob.value = { ...job };
    };

    const submitEdit = async () => {
      try {
        await axios.put(`/admin/jobs/${editingJob.value.id}`, {
          ...editingJob.value,
        });
        alert("Job updated successfully");
      } catch (err) {
        alert("Failed to update job");
      } finally {
        editingJob.value = null;
        await fetchJobs();
      }
    };

    const submitCreateJob = async () => {
      await axios.post("/api/admin/jobs", newJob.value);
      showCreateModal.value = false;
      await fetchJobs();
      newJob.value = {
        title: "",
        description: "",
        requirements: "",
        location: "",
        salary: "",
        jobType: "",
      };
    };

    const updateApplicationStatus = async (app) => {
      try {
        await axios.put(`/admin/applications/${app.application_id}/status`, {
          status: app.application_status,
        });
        alert("Status updated successfully");
      } catch (error) {
        console.error("Failed to update status", error);
        alert("Failed to update status");
      }
    };

    onMounted(() => {
      fetchJobs();
      fetchApplications();
    });

    return {
      jobs,
      applications,
      editingJob,
      showCreateModal,
      newJob,
      startEdit,
      deleteJob,
      submitEdit,
      submitCreateJob,
      updateApplicationStatus,
      statusOptions,
      FILE_BASE_URL, // ← export to template
    };
  },
};
</script>

<style scoped>
.admin-dashboard {
  padding: 2rem;
  font-family: "Segoe UI", sans-serif;
}
.title {
  font-size: 2rem;
  color: #2c3e50;
  margin-bottom: 20px;
}
.card {
  background: #fff;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
}
.styled-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 1rem;
}
.styled-table th,
.styled-table td {
  padding: 12px 15px;
  border: 1px solid #ddd;
  text-align: left;
}
.styled-table th {
  background-color: #0d6efd;
  color: white;
}
.styled-table td {
  background-color: #f9f9f9;
}
button.btn {
  margin-right: 5px;
  padding: 6px 12px;
  font-size: 0.9rem;
}
.modal {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal-content {
  background: #fff;
  padding: 20px;
  width: 500px;
  border-radius: 8px;
}
.wide-modal {
  width: 600px;
}
.extra-wide-modal {
  width: 700px;
}
.modal-content form input,
.modal-content form textarea,
.modal-content form select {
  padding: 10px;
  width: 100%;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.modal-content form textarea {
  min-height: 80px;
}
.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}
.btn-resume {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background-color: #669d62; /* soft red */
  color: white;
  padding: 6px 14px;
  border-radius: 5px;
  font-size: 0.95rem;
  text-decoration: none;
  transition: background-color 0.3s ease;
}

.btn-resume i {
  font-size: 1rem;
}

.btn-resume:hover {
  background-color: #387529; /* darker red on hover */
}

.compact-modal {
  width: 500px;
  padding: 15px;
}

h5.form-section {
  margin-top: 15px;
  margin-bottom: 8px;
  font-size: 1rem;
  border-bottom: 1px solid #ccc;
  padding-bottom: 3px;
  color: #444;
}

.form-group {
  margin-bottom: 10px;
}

.form-group label {
  display: block;
  font-size: 0.9rem;
  margin-bottom: 3px;
  color: #333;
}

input,
textarea {
  width: 100%;
  padding: 6px 8px;
  font-size: 0.9rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.form-row {
  display: flex;
  gap: 10px;
}

.form-row .form-group {
  flex: 1;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 10px;
}
</style>
