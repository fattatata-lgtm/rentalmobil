<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Rental Mobil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      background: #fff;
      color: #444;
      scroll-behavior: smooth;
    }

    /* HEADER */
    header {
      background-color: #fff;
      color: #fdbd40;
      padding: 20px 50px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    header .logo-container {
      display: flex;
      align-items: center;
    }

    header img {
      width: 120px;
      height: auto;
    }

    header h1 {
      font-family: 'Playfair Display', serif;
      font-size: 20px;
      font-weight: 700;
      margin-left: 15px;
      color: #fdbd40;
    }

    /* NAVBAR */
    nav {
      display: flex;
      gap: 20px;
    }

    nav a {
      text-decoration: none;
      color: #222;
      font-weight: 600;
      padding: 8px 12px;
      border-radius: 5px;
      transition: all 0.3s;
    }

    nav a:hover {
      background-color: #fdbd40;
      color: #fff;
    }

    /* SECTION */
    section {
      padding: 40px 20px;
      opacity: 0;
      transform: translateY(50px);
      transition: all 0.8s ease-in-out;
    }

    section.visible {
      opacity: 1;
      transform: translateY(0);
    }

    /* ABOUT */
    section.about {
      text-align: center;
      background-color: #f0f0f0;
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
    }

    section.about img {
      width: 300px;
      height: auto;
      border: 4px solid #fdbd40;
      border-radius: 8px;
      margin: 10px;
    }

    section.about p {
      max-width: 500px;
      text-align: justify;
      margin: 10px;
    }

    h2 {
      font-family: 'Playfair Display', serif;
      font-size: 32px;
      color: #fdbd40;
      margin-bottom: 20px;
    }

    h3 {
      font-family: 'Playfair Display', serif;
      color: #fdbd40;
    }

    /* LOGIN */
    .login-section {
      background-color: #fff;
      color: #444;
      text-align: center;
    }

    .login-card {
      background-color: #222;
      color: white;
      padding: 20px;
      border-radius: 8px;
      display: inline-block;
      border: 2px solid #fdbd40;
      text-align: center;
      margin-top: 20px;
    }

    .login-card a.button {
      display: inline-block;
      text-decoration: none;
      background-color: #fdbd40;
      color: #000;
      font-weight: bold;
      padding: 10px 20px;
      border-radius: 5px;
      margin-top: 15px;
      transition: 0.3s;
    }

    .login-card a.button:hover {
      background-color: #e0a800;
    }

    /* CONTACT */
    .contact {
      background: #f0f0f0;
      text-align: center;
    }

    footer {
      background-color: #222;
      color: #fdbd40;
      text-align: center;
      padding: 10px;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      header {
        flex-direction: column;
        text-align: center;
      }

      nav {
        margin-top: 10px;
        flex-direction: column;
        gap: 10px;
      }

      section.about {
        flex-direction: column;
      }
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <header>
    <div class="logo-container">
      <img src="img/logo.jpg" alt="Logo Rental Mobil">
      <h1>Solusi Tepat Untuk Mendapatkan Mobil Rental</h1>
    </div>
    <nav>
      <a href="#about">About</a>
      <a href="#login">Login</a>
      <a href="#contact">Kontak</a>
    </nav>
  </header>

  <!-- ABOUT -->
  <section class="about" id="about">
    <div>
      <h2>Rental Mobil</h2>
      <p>Rental Mobil adalah salah satu program dimana pengunjung dapat merental/menyewa jenis mobil yang kami telah sediakan.</p>
      <h3>Sekilas Mengapa Kami Memilih Ide Website Rental Mobil?</h3>
      <p>Di era digital, kebutuhan akan transportasi yang praktis dan fleksibel terus meningkat. Banyak orang lebih memilih menyewa mobil untuk keperluan perjalanan bisnis, liburan, atau aktivitas sehari-hari dibandingkan membeli kendaraan pribadi.
        <br><br>
        Website rental mobil hadir sebagai solusi modern yang memudahkan pengguna untuk mencari, memilih, dan memesan mobil secara online. Pengguna dapat melihat daftar mobil, harga, serta ketersediaan secara real-time, sehingga proses penyewaan menjadi lebih cepat dan efisien. Selain itu, website ini membantu pemilik rental mobil mengelola kendaraan dan reservasi dengan lebih rapi, memperluas jangkauan pelanggan, serta meningkatkan profesionalisme layanan.
        <br><br>
        Dengan demikian, ide website rental mobil menawarkan kemudahan bagi pelanggan sekaligus mendukung pengelolaan bisnis rental yang lebih efektif.</p>

      <p>✔ Thank you for visiting our website</p>
    </div>
    <img src="img/kantor.jpeg" alt="Kantor Rental Mobil">
  </section>

  <!-- LOGIN -->
  <section class="login-section" id="login">
    <h2>Login dan Rental Mobil</h2>
    <p>Klik button untuk memulai Rental Mobil</p>
    <div class="login-card">
      <img src="img/maxresdefault.jpg" alt="Mobil" width="300">
      <h3>Aplikasi Rental Mobil</h3>
      <p>Dibuat dengan se-simple mungkin untuk memudahkan user dalam proses rental mobil.</p>
      <a href="login.php" class="button">LOGIN</a>
    </div>
  </section>

  <!-- CONTACT -->
  <section class="contact" id="contact">
    <h2>Kontak Kami</h2>
    <p>Silahkan hubungi kami mengenai Rental Mobil dan konfirmasi booking Anda.</p>
    <p><b>Phone Numbers:</b> +62-858-5527-3945  (Phone Booking)</p>
    <p>Konfirmasi Pembayaran: Rentalmobil@gmail.com</p>
  </section>

  <footer>
    <p>CAFA PROJECT © 2025</p>
  </footer>

  <!-- SCRIPT SCROLL HALUS & ANIMASI -->
  <script>
    // Smooth scroll dengan offset untuk header sticky
    const navLinks = document.querySelectorAll('nav a');

    navLinks.forEach(link => {
      link.addEventListener('click', function (e) {
        e.preventDefault();
        const targetId = this.getAttribute('href').substring(1);
        const targetSection = document.getElementById(targetId);
        if (targetSection) {
          const headerOffset = document.querySelector('header').offsetHeight;
          const elementPosition = targetSection.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

          window.scrollTo({
            top: offsetPosition,
            behavior: "smooth"
          });
        }
      });
    });

    // Animasi fade-in saat scroll
    const sections = document.querySelectorAll('section');

    const revealSection = () => {
      const triggerBottom = window.innerHeight * 0.85;

      sections.forEach(section => {
        const sectionTop = section.getBoundingClientRect().top;
        if (sectionTop < triggerBottom) {
          section.classList.add('visible');
        }
      });
    };

    window.addEventListener('scroll', revealSection);
    window.addEventListener('load', revealSection);
  </script>

</body>

</html>
