<header id="MyTopbar" class="bg-navy-900 text-light-100">
    <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-row justify-between py-2">
            <div class="flex gap-3">
                <a href="https://www.ung.ac.id/" class="flex flex-row text-sm font-body font-light hover:underline">
                    <i class="ri-home-4-fill me-1"></i>
                    <span class="hidden md:block">Universitas Negeri Gorontalo</span>
                    <span class="md:hidden">UNG</span>
                </a>

                <a href="mailto:pti.ft@ung.ac.id" class="text-sm font-body font-light hidden md:block hover:underline">
                    <i class="ri-mail-fill me-1"></i> pti.ft@ung.ac.id
                </a>

                <a href="#" class="text-sm font-body font-light hidden md:block hover:underline">
                    <i class="ri-phone-fill me-1"></i> +62 21 345 6789
                </a>
            </div>
            <div class="flex gap-3">
                <a href="#" class="text-sm font-body font-light hover:underline">
                    Kerja Sama
                </a>
                <a href="{{ route('login') }}" class="text-sm font-body font-light hover:underline">
                    Login
                </a>
                <a href="#" class="flex items-center gap-2 text-sm font-body font-light hover:underline">
                    <img src="{{ asset('assets/frontpage/img/flag/id.svg') }}" alt="id-flag" class="w-4">
                    <span>ID</span>
                </a>
                <a href="#" class="flex items-center gap-2 text-sm font-body font-light hover:underline">
                    <img src="{{ asset('assets/frontpage/img/flag/gb.svg') }}" alt="id-flag" class="w-4">
                    <span>EN</span>
                </a>
            </div>
        </div>
    </div>
</header>
