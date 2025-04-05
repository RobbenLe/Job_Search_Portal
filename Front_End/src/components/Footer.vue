<template>
  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-column">
        <h4>Job Portal</h4>
        <p>Your gateway to top jobs. Connecting talent with opportunities!</p>
      </div>

      <div class="footer-column">
        <h4>Quick Links</h4>
        <ul>
          <li><router-link to="/">Home</router-link></li>
          <li><router-link to="/jobs">Jobs</router-link></li>
          <li><router-link to="/profile">Profile</router-link></li>
          <li><router-link to="/login">Login</router-link></li>
        </ul>
      </div>

      <div class="footer-column">
        <h4>Contact Us</h4>
        <p>Email: support@jobportal.com</p>
        <p>Phone: +1 (555) 123-4567</p>
        <div class="social-icons">
          <a href="#"><i class="fab fa-facebook-square"></i></a>
          <a href="#"><i class="fab fa-twitter-square"></i></a>
          <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>
        &copy; {{ new Date().getFullYear() }} Job Portal. All rights reserved.
      </p>
    </div>
  </footer>
</template>

<script>
export default {
  name: "FooterComponent",
};
</script>

<style>
.footer {
  background-color: #022c1f;
  color: #fff;
  padding: 40px 20px 20px;
  margin-top: 60px;
  font-family: "Segoe UI", sans-serif;
}

.footer-container {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 30px;
  max-width: 1200px;
  margin: auto;
}

.footer-column {
  flex: 1 1 300px;
}

.footer-column h4 {
  margin-bottom: 15px;
  font-size: 18px;
  color: #00cc66;
}

.footer-column ul {
  list-style: none;
  padding: 0;
}

.footer-column ul li {
  margin-bottom: 8px;
}

.footer-column ul li a {
  color: #fff;
  text-decoration: none;
  transition: color 0.2s;
}

.footer-column ul li a:hover {
  color: #00cc99;
}

.social-icons a {
  color: #00cc66;
  margin-right: 10px;
  font-size: 20px;
}

.footer-bottom {
  text-align: center;
  margin-top: 30px;
  border-top: 1px solid #444;
  padding-top: 15px;
  font-size: 14px;
}
</style>
