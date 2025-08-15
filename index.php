<?php
require_once("public/auth.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Elite School — School Management Software (One-Time Purchase)</title>
  <meta name="description" content="Elite School Management Software — one-time purchase, install on your own hosting. CBT, result computation, fees, ID cards, parent portals and more." />

  <!-- Favicons (replace these files with your own) -->
  <link rel="icon" type="image/png" sizes="32x32" href="assets/logo.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png" />

  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    html { scroll-behavior: smooth; }
    /* small custom transitions */
    .fade-in-up { transform: translateY(12px); opacity: 0; transition: all .6s ease-out; }
    .in-view { transform: translateY(0); opacity: 1; }
    .card-hover { transition: transform .2s ease, box-shadow .2s ease; }
    .card-hover:hover { transform: translateY(-8px); box-shadow: 0 12px 30px rgba(2,6,23,0.12); }
    /* testimonial carousel */
    .dots button { width: 10px; height: 10px; border-radius: 9999px; }
  </style>
</head>
<body class="font-sans antialiased text-gray-800 bg-gray-50">

  <!-- Sticky Navbar -->
  <header class="fixed top-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-sm shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="assets/logo.png" alt="EliteSchool logo" class="w-10 h-10 rounded" />
        <span class="font-extrabold text-xl text-blue-600">EliteSchool</span>
      </div>
      <nav class="hidden md:flex gap-6 items-center text-sm">
        <a href="#features" class="hover:text-blue-600">Features</a>
        <a href="#beforeafter" class="hover:text-blue-600">Before & After</a>
        <a href="#testimonials" class="hover:text-blue-600">Testimonials</a>
        <a href="#faq" class="hover:text-blue-600">FAQ</a>
        <a href="#pricing" class="hover:text-blue-600">Pricing</a>
        <a href="#contact" class="hover:text-blue-600">Contact</a>
        <a href="login.php" class="block mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg inline-block">View Demo</a>
      </nav>

      <!-- mobile menu button -->
      <div class="md:hidden">
        <button id="mobileMenuBtn" class="p-2 rounded-md hover:bg-gray-100" aria-label="Open menu">
          <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- mobile nav -->
    <div id="mobileMenu" class="hidden md:hidden border-t bg-white">
      <div class="px-6 py-4 space-y-3">
        <a href="#features" class="block">Features</a>
        <a href="#beforeafter" class="block">Before & After</a>
        <a href="#testimonials" class="block">Testimonials</a>
        <a href="#faq" class="block">FAQ</a>
        <a href="#pricing" class="block">Pricing</a>
        <a href="#contact" class="block">Contact</a>
        <a href="login.php" class="block mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg inline-block">View Demo</a>
      </div>
    </div>
  </header>

  <main class="pt-20">

    <!-- HERO -->
    <section class="relative overflow-hidden">
      <div class="absolute inset-0">
        <img src="assets/school.png"
             alt="School environment" class="w-full h-full object-cover opacity-60" />
      </div>

      <div class="relative max-w-7xl mx-auto px-6 py-28 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-8 items-center">
          <div class="text-white">          
            <h1 class="text-yellow-300 text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight mt-4">Manage your school with confidence — <span class="text-yellow-300">no subscriptions</span>.</h1>
            <p class="text-blue-900 mt-6 text-lg max-w-xl"><strong>EliteSchool gives you CBT, result computation, fees, attendance, ID cards, parent portals — all installed on your own hosting. One-time purchase, full ownership.</strong></p>

            <div class="mt-8 flex flex-wrap gap-3">
              <a href="#pricing" class="inline-flex items-center bg-yellow-400 text-gray-900 px-5 py-3 rounded-lg font-semibold shadow hover:bg-yellow-300">Buy Now</a>
              <a href="#demo" class="inline-flex items-center bg-yellow-400 text-gray-900 px-5 py-3 rounded-lg font-semibold shadow hover:bg-yellow-300">View Demo</a>
            </div>

            <!-- trust badges -->
            <div class="mt-8 flex flex-wrap gap-4 items-center text-sm">
              <div class="flex items-center gap-2 bg-gray-800 text-white px-3 py-2 rounded">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 2a6 6 0 00-6 6v2a6 6 0 0012 0V8a6 6 0 00-6-6zM3 13a2 2 0 002 2h10a2 2 0 002-2v-1H3v1z"/>
                </svg>
                <span>Self-hosted • Your data</span>
              </div>
            
              <div class="flex items-center gap-2 bg-gray-800 text-white px-3 py-2 rounded">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.943a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.447a1 1 0 00-.364 1.118l1.287 3.943c.3.921-.755 1.688-1.54 1.118L10 13.347l-3.87 2.649c-.784.57-1.84-.197-1.54-1.118l1.286-3.943a1 1 0 00-.364-1.118L2.142 9.37c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.287-3.943z"/>
                </svg>
                <span>Trusted by principals & admins</span>
              </div>
            
              <div class="flex items-center gap-2 bg-gray-800 text-white px-3 py-2 rounded">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M3 3h14a1 1 0 011 1v12a1 1 0 01-1 1H3a1 1 0 01-1-1V4a1 1 0 011-1z"/>
                </svg>
                <span>Mobile-friendly</span>
              </div>
            </div>            
          </div>

          <!-- right: screenshot mockup -->
          <div class="hidden lg:flex justify-center">
            <div class="w-[380px] h-[260px] rounded-2xl overflow-hidden shadow-2xl transform transition-all duration-500 hover:scale-[1.02]">
              <img src="assets/cbt.png" alt="Dashboard screenshot" class="w-full h-full object-cover"/>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features -->
  <section id="features" class="max-w-7xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold">Features that save time & reduce errors</h2>
      <p class="text-gray-600 mt-3 max-w-2xl mx-auto">Everything a modern school needs — built for self-hosting and offline reliability.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-3">
      <!-- card -->
      <article class="bg-white rounded-xl p-6 card-hover fade-in-up" data-animate>
        <div class="flex items-center gap-4">
          <div class="bg-indigo-50 p-3 rounded-lg">
            <img src="assets/cbt.png" alt="CBT" class="w-8 h-8"/>
          </div>
          <h3 class="font-semibold">Computer-Based Tests (CBT)</h3>
        </div>
        <p class="mt-4 text-gray-600">Create exam groups, manage objective & theory tests, auto-score MCQs and store answers for manual grading.</p>
      </article>

      <article class="bg-white rounded-xl p-6 card-hover fade-in-up" data-animate>
        <div class="flex items-center gap-4">
          <div class="bg-indigo-50 p-3 rounded-lg">
            <img src="https://img.icons8.com/fluency/48/000000/report-card.png" alt="Result Computation" class="w-8 h-8"/>
          </div>
          <h3 class="font-semibold">Result Computation & Reports</h3>
        </div>
        <p class="mt-4 text-gray-600">Auto compute results, combine objective and theory scores, generate printable report cards and transcripts.</p>
      </article>

      <article class="bg-white rounded-xl p-6 card-hover fade-in-up" data-animate>
        <div class="flex items-center gap-4">
          <div class="bg-indigo-50 p-3 rounded-lg">
            <img src="https://img.icons8.com/fluency/48/000000/money-bag.png" alt="Fees" class="w-8 h-8"/>
          </div>
          <h3 class="font-semibold">Fees Management</h3>
        </div>
        <p class="mt-4 text-gray-600">Track payments, print receipts, define fee structures and send reminders to parents.</p>
      </article>

      <article class="bg-white rounded-xl p-6 card-hover fade-in-up" data-animate>
        <div class="flex items-center gap-4">
          <div class="bg-indigo-50 p-3 rounded-lg">
            <img src="https://img.icons8.com/fluency/48/000000/id-verified.png" alt="ID card" class="w-8 h-8"/>
          </div>
          <h3 class="font-semibold">ID Card Generator</h3>
        </div>
        <p class="mt-4 text-gray-600">Design and print ID cards for students & staff with barcode/QR support.</p>
      </article>

      <article class="bg-white rounded-xl p-6 card-hover fade-in-up" data-animate>
        <div class="flex items-center gap-4">
          <div class="bg-indigo-50 p-3 rounded-lg">
            <img src="https://img.icons8.com/fluency/48/000000/timetable.png" alt="Timetable" class="w-8 h-8"/>
          </div>
          <h3 class="font-semibold">Timetable & Attendance</h3>
        </div>
        <p class="mt-4 text-gray-600">Create timetables, record attendance, and export logs for parents and administration.</p>
      </article>

      <article class="bg-white rounded-xl p-6 card-hover fade-in-up" data-animate>
        <div class="flex items-center gap-4">
          <div class="bg-indigo-50 p-3 rounded-lg">
            <img src="assets/login.png" alt="Portal" class="w-8 h-8"/>
          </div>
          <h3 class="font-semibold">Teacher & Student Portals</h3>
        </div>
        <p class="mt-4 text-gray-600">Secure portals for teachers and students to view results, timetables, and announcements.</p>
      </article>
    </div>
  </section>

  <!-- Before & After -->
  <section id="beforeafter" class="py-20 bg-gradient-to-b from-white to-gray-50">
    <div class="max-w-6xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-8">Before & After — See The Difference</h2>
      <div class="grid md:grid-cols-2 gap-6 items-center">
        <figure class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
          <img src="assets/manual.png" alt="Paperwork" class="rounded mb-4 h-44 object-cover w-full"/>
          <figcaption class="font-semibold">Before — Manual & Paper-based</figcaption>
          <p class="text-gray-600 mt-2 text-center">Loose files, manual calculations and time-consuming admin work.</p>
        </figure>

        <figure class="bg-white p-6 rounded-lg shadow flex flex-col items-center">
          <img src="assets/digital.png" alt="Digital dashboard" class="rounded mb-4 h-44 object-cover w-full"/>
          <figcaption class="font-semibold">After — Digital & Efficient</figcaption>
          <p class="text-gray-600 mt-2 text-center">Centralized dashboard, instant reports and faster decision-making.</p>
        </figure>
      </div>
    </div>
  </section>

  <!-- Testimonials Carousel -->
  <section id="testimonials" class="py-20">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-6">What school owners say</h2>

      <div class="relative">
        <div id="testiWrap" class="overflow-hidden rounded-xl">
          <!-- slides -->
          <div class="testi-slide bg-white p-8 rounded-xl shadow" data-index="0">
            <p class="text-gray-700">“EliteSchool cut our result processing time by 80%. Installation was smooth and we host on our server — great for data privacy.”</p>
            <div class="mt-4 text-sm font-semibold">— Hikimah International Schools, Sokoto</div>
          </div>
          <div class="testi-slide hidden bg-white p-8 rounded-xl shadow" data-index="1">
            <p class="text-gray-700">“CBT module is reliable and the students found it user-friendly. No monthly fees is a big win.”</p>
            <div class="mt-4 text-sm font-semibold">— Adamspring College, Lagos</div>
          </div>
          <div class="testi-slide hidden bg-white p-8 rounded-xl shadow" data-index="2">
            <p class="text-gray-700">“We now print ID cards and receipts in minutes — parents love the portal for quick access to results.”</p>
            <div class="mt-4 text-sm font-semibold">— Gemliz Academy, Lagos</div>
          </div>
        </div>

        <!-- controls -->
        <div class="mt-6 flex items-center justify-center gap-3">
          <button id="prevTesti" aria-label="Previous" class="p-2 rounded bg-gray-100 hover:bg-gray-200">‹</button>
          <div class="dots flex gap-2 items-center"></div>
          <button id="nextTesti" aria-label="Next" class="p-2 rounded bg-gray-100 hover:bg-gray-200">›</button>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ -->
  <section id="faq" class="py-20 bg-gray-50">
    <div class="max-w-4xl mx-auto px-6">
      <h2 class="text-3xl font-bold text-center mb-6">Frequently asked questions</h2>
      <dl class="space-y-4">
        <div class="bg-white rounded-lg shadow">
          <button class="w-full text-left px-6 py-4 flex justify-between items-center faq-btn">
            <span class="font-medium">Do I pay every year?</span>
            <svg class="w-5 h-5 text-gray-500 transform transition-transform" viewBox="0 0 20 20" fill="none"><path stroke="currentColor" stroke-width="2" d="M6 8l4 4 4-4"></path></svg>
          </button>
          <div class="px-6 pb-4 hidden faq-body">No — EliteSchool is a one-time purchase. We offer optional paid support or setup if you want ongoing help.</div>
        </div>

        <div class="bg-white rounded-lg shadow">
          <button class="w-full text-left px-6 py-4 flex justify-between items-center faq-btn">
            <span class="font-medium">Can I host it myself?</span>
            <svg class="w-5 h-5 text-gray-500 transform transition-transform" viewBox="0 0 20 20" fill="none"><path stroke="currentColor" stroke-width="2" d="M6 8l4 4 4-4"></path></svg>
          </button>
          <div class="px-6 pb-4 hidden faq-body">Yes — we'll assist with installation on your hosting (cPanel, Plesk or custom). Full control and data ownership remain with you.</div>
        </div>

        <div class="bg-white rounded-lg shadow">
          <button class="w-full text-left px-6 py-4 flex justify-between items-center faq-btn">
            <span class="font-medium">Do you provide training?</span>
            <svg class="w-5 h-5 text-gray-500 transform transition-transform" viewBox="0 0 20 20" fill="none"><path stroke="currentColor" stroke-width="2" d="M6 8l4 4 4-4"></path></svg>
          </button>
          <div class="px-6 pb-4 hidden faq-body">Yes — free onboarding training for admin & staff is included in the installation package.</div>
        </div>

      </dl>
    </div>
  </section>

  <!-- Pricing & CTA -->
  <section id="pricing" class="py-20">
    <div class="max-w-4xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-4">Simple one-time purchase</h2>
      <p class="text-gray-600 mb-8">Pay once and own the software. We help you install on your server and train staff.</p>

      <div class="inline-block bg-white rounded-xl shadow-lg p-8">
        <div class="text-5xl font-extrabold text-blue-600 mb-2">₦350,000</div>
        <p class="text-gray-600 mb-6">Includes installation & onboarding</p>
        <a href="https://wa.me/2348075423260" target="_blank" class="inline-block bg-green-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-green-600">Buy / Request Setup</a>
      </div>
    </div>
  </section>

  <!-- Contact -->
  <section id="contact" class="py-16 bg-blue-900 text-white">
    <div class="max-w-5xl mx-auto px-6 text-center">
      <h2 class="text-3xl font-bold mb-4">Ready to get started?</h2>
      <p class="mb-6">Contact us now to arrange a demo or request installation.</p>
      <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="https://wa.me/2348075423260" target="_blank" class="bg-green-400 text-gray-900 px-6 py-3 rounded-lg font-semibold">Chat on WhatsApp</a>
        <a href="mailto:info@eliteschool.com.ng" class="bg-white text-blue-900 px-6 py-3 rounded-lg font-semibold">Email Us</a>
      </div>
    </div>
  </section>

  <!-- Floating WhatsApp button -->
  <a href="https://wa.me/2348075423260" target="_blank" aria-label="Chat on WhatsApp"
     class="fixed right-5 bottom-5 z-50 bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-lg flex items-center justify-center">
    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none"><path d="M21 12.25a8.5 8.5 0 10-2.34 5.7L22 22l1.05-3.15A8.48 8.48 0 0021 12.25z" fill="white"/></svg>
  </a>

  <!-- Footer -->
  <footer class="bg-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-6 text-center text-gray-600">
      <div class="mb-3">EliteSchool — One-time purchase school management software</div>
      <div>Phone: <a class="text-blue-600" href="tel:09061643031">09061643031</a> • Email: <a class="text-blue-600" href="mailto:info@eliteschool.com.ng">info@eliteschool.com.ng</a></div>
      <div class="mt-3 text-sm">&copy; <span id="year"></span> EliteSchool. All rights reserved.</div>
    </div>
  </footer>

  <!-- Minimal JS: mobile menu, animations, carousel, FAQ -->
  <script>
    // set year
    document.getElementById('year').textContent = new Date().getFullYear();

    // mobile menu
    const mobileBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    mobileBtn?.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

    // intersection observer for fade-in
    const obs = new IntersectionObserver((entries) => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('in-view');
        }
      });
    }, { threshold: 0.15 });
    document.querySelectorAll('[data-animate]').forEach(el => {
      el.classList.add('fade-in-up');
      obs.observe(el);
    });

    // FAQ accordion
    document.querySelectorAll('.faq-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const body = btn.nextElementSibling;
        const icon = btn.querySelector('svg');
        const open = !body.classList.contains('hidden');
        if (open) {
          body.classList.add('hidden');
          icon.style.transform = 'rotate(0deg)';
        } else {
          body.classList.remove('hidden');
          icon.style.transform = 'rotate(180deg)';
        }
      });
    });

    // Simple testimonials carousel
    const slides = Array.from(document.querySelectorAll('.testi-slide'));
    const dotsWrap = document.querySelector('.dots');
    let current = 0;
    let interval = null;
    function showTesti(index) {
      slides.forEach((s, i) => {
        s.classList.toggle('hidden', i !== index);
      });
      // update dots
      dotsWrap.innerHTML = '';
      slides.forEach((_, i) => {
        const btn = document.createElement('button');
        btn.className = (i === index ? 'bg-blue-600' : 'bg-gray-300') + ' dots-btn';
        btn.addEventListener('click', () => {
          current = i; resetInterval(); showTesti(i);
        });
        dotsWrap.appendChild(btn);
      });
    }
    function prev() { current = (current - 1 + slides.length) % slides.length; showTesti(current); resetInterval(); }
    function next() { current = (current + 1) % slides.length; showTesti(current); resetInterval(); }
    document.getElementById('prevTesti').addEventListener('click', prev);
    document.getElementById('nextTesti').addEventListener('click', next);
    function resetInterval(){
      clearInterval(interval);
      interval = setInterval(() => { next(); }, 5000);
    }
    if (slides.length) {
      showTesti(0);
      resetInterval();
    }
  </script>

</body>
</html>
