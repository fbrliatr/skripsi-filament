<!DOCTYPE html>
<html data-theme="light" lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@2.1.0/dist/iconify-icon.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://code.iconify.design/2/2.1.2/iconify.min.js"></script>

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
        <a class="btn btn-primary text-base-100 px-8" href="dashboard/login">Login</a>
      </div>
    </div>
  </header>

  <main>
    <div class="bg-base-200">
      <section class="grid grid-cols-2 gap-0">
        <div class="h-full w-full center">
          <div class="prose">
            <h1 class="my-0">Selamat Datang di SaSak!</h1>
            <p>Kurangi Limbah, Selamatkan Bumi!</p>
            <a> <button class="btn btn-primary text-base-100">
              Lanjutkan Membaca <iconify-icon icon="formkit:arrowright"></iconify-icon>
            </button>
            </a>
          </div>
        </div>
        <div class="flex h-full w-full justify-center">
          <img src="img/hero.svg" alt="Sampah Saku">
        </div>
      </section>
    </div>

    <section>
        <div class="scrollable-section flex justify-start gap-8 overflow-x-auto w-full">
            @foreach($contents as $content)
                <div class="card bg-base-100 shadow flex-shrink-0 w-80 h-96">
                    <img class="w-80 skeleton aspect-video object-cover object-center" src="{{ $content->image }}" alt="{{ $content->title }}" />
                    <div class="card-body">
                        <h2 class="card-title">{{ $content->title }}</h2>
                        <div class="card-actions justify-end">
                            <a href="{{ $content->id == 1 ? '/konten/1' : ($content->id == 2 ? '/konten/2' : ($content->id == 3 ? '/konten/3' : ($content->id == 4 ? '/konten/4' : ($content->id == 5 ? '/konten/5' : '/default'))) ) }}" class="view-content-button">
                                <button class="btn btn-outline btn-sm">
                                    Lanjutkan Membaca <iconify-icon icon="formkit:arrowright"></iconify-icon>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <div class="bg-base-200">
      <section class="grid grid-cols-2 gap-0">
        <div class="h-full w-full center">
          <div class="prose">
            <h1 class="my-0">Join Our Newsletter</h1>
            <p>We love to surprise our subscribers with occasional gifts.</p>
          </div>
        </div>
        <div class="flex h-96 w-full justify-center items-center gap-4">
          <input type="text" placeholder="Your email address" class="input input-bordered w-full max-w-xs" />
          <button class="btn btn-primary text-base-100">
            Subscribe <iconify-icon icon="formkit:arrowright"></iconify-icon>
          </button>
        </div>
      </section>
    </div>

    <section id=1 class="grid grid-cols-2 gap-0">
      <div class="h-full w-full center">
        <div class="w-[60%] flex flex-col gap-5">
          <a href="" class="flex text-center items-center gap-3">
            <img src="img/logo.svg" alt="">
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
  </main>

</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.view-content-button');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const contentId = this.getAttribute('data-id');
                if (contentId) {
                    window.location.href = `/konten/{contentId}`;
                }
            });
        });
    });
</script>
</html>
