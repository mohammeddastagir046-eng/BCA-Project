<!-- ==========================================================
   BOOKIFY FOOTER SECTION
   Includes Info Bar + Social Links + Legal Notice
   ========================================================== -->

<!-- ---------- Info Section ---------- -->
<div class="info-section">
  <div class="info-item">
    <img src="https://img.icons8.com/ios/50/ffffff/headset.png" alt="Customer Care">
    <p>24/7 Customer Care</p>
  </div>
  <div class="info-item">
    <img src="assets/images/feedback.png" alt="Feedback">
    <p>Feedback</p>
  </div>
  <div class="info-item">
    <img src="https://img.icons8.com/ios/50/ffffff/ticket.png" alt="Booking Confirmation">
    <p>Booking Confirmation</p>
  </div>
</div>

<!-- ---------- Footer ---------- -->
<footer class="footer">
  <img src="assets/images/white1.png" alt="Bookify Logo" class="footer-logo">

  <!-- Social media links -->
  <div class="social-icons">
    <a href="https://www.facebook.com/login/" target="_blank">
      <img src="https://img.icons8.com/ios-filled/50/ffffff/facebook-new.png" alt="Facebook">
    </a>
    <a href="https://x.com/i/flow/login?lang=en" target="_blank">
      <img src="https://img.icons8.com/ios-filled/50/ffffff/twitterx.png" alt="TwitterX">
    </a>
    <a href="https://www.instagram.com/accounts/login/?hl=en" target="_blank">
      <img src="https://img.icons8.com/ios-filled/50/ffffff/instagram.png" alt="Instagram">
    </a>
    <a href="https://www.youtube.com/" target="_blank">
      <img src="https://img.icons8.com/ios-filled/50/ffffff/youtube.png" alt="YouTube">
    </a>
    <a href="https://in.pinterest.com/login/" target="_blank">
      <img src="https://img.icons8.com/ios-filled/50/ffffff/pinterest.png" alt="Pinterest">
    </a>
    <a href="https://www.linkedin.com/feed/" target="_blank">
      <img src="https://img.icons8.com/ios-filled/50/ffffff/linkedin.png" alt="LinkedIn">
    </a>
  </div>

  <!-- Copyright notice -->
  <p>
    Copyright 2025 © Bookify Entertainment Pvt. Ltd. All Rights Reserved.<br>
    The content and images used on this site are copyright protected and copyrights vest with the respective owners.
    The usage of the content and images on this website is intended to promote the works and no endorsement of the artist shall be implied.
    Unauthorized use is prohibited and punishable by law.
  </p>
</footer>

<!-- ==========================================================
   FOOTER STYLES
   ========================================================== -->
<style>
  /* --- Layout gap below events --- */
  main.container,
  .container.my-4,
  main .container,
  main {
    margin-bottom: 100px !important;
    padding-bottom: 40px;
  }

  /* --- Info Section (Dark Bar) --- */
  .info-section {
    margin-top: 40px;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 60px;
    padding: 25px 15px;
    background-color: #2b2b2b;
    text-align: center;
    flex-wrap: wrap;
  }

  .info-item {
    color: #f1f1f1;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .info-item img {
    width: 50px;
    height: 50px;
    margin-bottom: 10px;
    filter: brightness(0) invert(1);
  }

  /* --- Footer Body --- */
  .footer {
    background-color: #2b2b2b;
    padding: 35px 20px;
    text-align: center;
    border-top: 1px solid #3a3a3a;
    color: #ccc;
  }

  .footer-logo {
    width: 200px;
    height: auto;
    margin-bottom: 20px;
    object-fit: contain;
  }

  /* --- Social Icons --- */
  .social-icons {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 18px;
    margin: 15px 0 25px;
  }

  .social-icons img {
    width: 28px;
    height: 28px;
    cursor: pointer;
    transition: transform 0.2s ease, opacity 0.2s ease;
    filter: brightness(0) invert(1);
  }

  .social-icons img:hover {
    transform: scale(1.1);
    opacity: 0.8;
  }

  /* --- Footer Text --- */
  .footer p {
    font-size: 12px;
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
    color: #bbb;
  }

  /* --- Responsive --- */
  @media (max-width: 768px) {
    .info-section {
      flex-direction: column;
      gap: 20px;
      margin-top: 30px;
    }
    .footer-logo {
      width: 150px;
    }
    .footer p {
      font-size: 11px;
      padding: 0 15px;
    }
  }
</style>

<!-- Bootstrap JS (for navbar & dropdowns) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
