// src/stores/jobs.js
import { defineStore } from "pinia";
import axios from "axios";

export const useJobsStore = defineStore("jobs", {
  state: () => ({
    jobs: [], // list of all jobs
    job: null, // a single job (for detail view)
    loading: false,
    error: null,
  }),

  actions: {
    // Fetch all jobs from the backend
    async fetchJobs() {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get("/api/jobs");
        this.jobs = response.data;
      } catch (err) {
        this.error = err.response?.data?.error || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Fetch a single job by ID
    async fetchJob(id) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get(`/api/jobs/${id}`);
        this.job = response.data;
      } catch (err) {
        this.error = err.response?.data?.error || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Create a new job
    async createJob(jobData) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.post("/api/admin/jobs", jobData);
        // Optionally add the newly created job to our local state
        this.jobs.push(response.data);
      } catch (err) {
        this.error = err.response?.data?.error || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Update an existing job
    async updateJob(id, jobData) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.put(`/api/jobs/${id}`, jobData);
        // Optionally update the job in our local jobs array
        const index = this.jobs.findIndex((job) => job.id === id);
        if (index !== -1) {
          this.jobs[index] = response.data;
        }
      } catch (err) {
        this.error = err.response?.data?.error || err.message;
      } finally {
        this.loading = false;
      }
    },

    // Delete a job by ID
    async deleteJob(id) {
      this.loading = true;
      this.error = null;
      try {
        await axios.delete(`/api/jobs/${id}`);
        // Remove the job from our local array
        this.jobs = this.jobs.filter((job) => job.id !== id);
      } catch (err) {
        this.error = err.response?.data?.error || err.message;
      } finally {
        this.loading = false;
      }
    },
  },
});
