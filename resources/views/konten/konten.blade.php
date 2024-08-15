<!DOCTYPE html>
<html data-theme="light" lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $content->title }}</title>

  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

  <link href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&family=Quicksand:wght@400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="/style.css" />

  <title>Sampah Saku</title>
</head>
<body>
    <header class="sticky top-0 z-50 drop-shadow-md">
        <div class="navbar bg-base-100 px-20 py-5">
          <div class="navbar-start">
            <div class="dropdown">
              <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                <iconify-icon icon="icon-park-outline:hamburger-button" width="32" height="32"></iconify-icon>
              </div>
              <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
                <li><a>Home</a></li>
                <li><a>Blog</a></li>
                <li><a>Tutorial</a></li>
                <li><a>About</a></li>
              </ul>
            </div>
            <a href="/landing" class="flex text-center items-center gap-3">
              <img src="/img/logo.svg" alt="">
              <p class="text-xl font-bold">Sampah Saku</p>
            </a>
          </div>
          <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
              <li><a>Home</a></li>
              <li>
                <details>
                  <summary>Blog</summary>
                </details>
              </li>
              <li><a>Tutorial</a></li>
              <li><a>About</a></li>
            </ul>
          </div>
          <div class="navbar-end">
            <a class="btn btn-primary text-base-100 px-8" href="/dashboard/login">Login</a>
          </div>
        </div>
    </header>


  <div class="content-container h-full mx-auto p-4 clearfix">
    <div class="card bg-base-100 shadow w-full max-w-lg mx-auto">
      <img class="w-full h-64 object-cover" src="{{ $content->image }}" alt="{{ $content->title }}" />
      <div class="card-body p-4">
        <h1 class="text-2xl font-bold mb-4">{{ $content->title }}</h1>
        <div class="text-gray-700 mb-4">
            {!! $content->description_html !!} <!-- Render HTML content -->
        </div>
        <p class="text-gray-500">{{ $content->tgl_rilis->format('F d, Y') }}</p>
      </div>
    </div>
  </div>


  <footer>
    <section class="grid grid-cols-2 gap-0 max-w-screen-lg mx-auto">
      <div class="h-full w-full flex flex-col items-start gap-5">
        <a href="" class="flex text-center items-center gap-3">
          <img src="/img/logo.svg" alt="">
          <p class="text-xl font-bold">Sampah Saku</p>
        </a>
        <p>Sampah Saku is an online platform for supporting business process on Bank Sampah.</p>

        <div class="flex gap-7 text-gray-500">
          <a href="">
            <iconify-icon icon="mdi:github" width="36" height="36"></iconify-icon>
          </a>
          <a href="">
            <iconify-icon icon="mdi:instagram" width="36" height="36"></iconify-icon>
          </a>
          <a href="">
            <iconify-icon icon="mdi:youtube" width="36" height="36"></iconify-icon>
          </a>
        </div>
      </div>

      <div class="flex gap-24">
        <div>
          <p class="pb-5">SUPPORT</p>
          <div class="space-y-3 font-semibold text-gray-500">
            <p>FAQ</p>
            <p>Terms of Use</p>
            <p>Privacy Policy</p>
          </div>
        </div>
        <div>
          <p class="pb-5">COMPANY</p>
          <div class="space-y-3 font-semibold text-gray-500">
            <p>About Us</p>
            <p>Contact</p>
            <p>Career</p>
          </div>
        </div>
      </div>
    </section>
  </footer>
</body>
</html>

