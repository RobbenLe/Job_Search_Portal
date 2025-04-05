<!-- src/components/Home.vue -->
<template>
  <div class="home-container">
    <!-- Hero Section -->
    <header class="hero-section">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <h1>Welcome to the Job Portal!</h1>
        <p>Your one-stop solution for finding your dream job.</p>
        <router-link to="/jobs" class="btn btn-primary btn-lg"
          >Browse Jobs</router-link
        >
      </div>
    </header>

    <!-- Featured Jobs Section -->
    <section class="jobs-preview">
      <JobsList />
    </section>
  </div>
</template>

<script>
import JobsList from "@/components/JobsList.vue";

export default {
  name: "Home",
  components: {
    JobsList,
  },
};
</script>

<style scoped>
/* Global container styling */
.home-container {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

/* Hero Section */
.hero-section {
  position: relative;
  background: url("@/assets/GettyImages-504381960-1.webp") center/cover
    no-repeat;
  color: #fff;
  text-align: center;
  padding: 100px 20px;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 0;
}

.hero-content {
  position: relative;
  z-index: 1;
}

.hero-content h1 {
  font-size: 3.5rem;
  margin-bottom: 20px;
}

.hero-content p {
  font-size: 1.5rem;
  margin-bottom: 30px;
}

/* Button Styling - using Bootstrap classes if available; otherwise, add your own */
.btn {
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  text-decoration: none;
  font-size: 1rem;
  cursor: pointer;
}

.btn-primary {
  background-color: #007bff;
  color: #fff;
}

/* Featured Jobs Section */
.jobs-preview {
  padding: 60px 20px;
  background-color: #f8f9fa;
  text-align: center;
}

.jobs-preview h2 {
  font-size: 2.5rem;
  margin-bottom: 40px;
}
</style>
