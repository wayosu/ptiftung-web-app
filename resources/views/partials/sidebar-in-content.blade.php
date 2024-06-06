<div class="lg:col-span-1 lg:w-full lg:h-full lg:bg-gradient-to-r lg:from-gray-50 lg:via-transparent lg:to-transparent">
    <div class="sticky top-16 start-0 pt-0 pb-8 lg:py-8 lg:ps-8">
        <!-- Avatar Media -->
        <div class="border-t lg:border-t-0 pt-8 lg:pt-0 border-b border-gray-200 pb-8 mb-8">
            <h2 class="text-xl font-display font-bold">Profil</h2>
        </div>
        <!-- End Avatar Media -->

        <div class="space-y-6">
            <a class="group flex items-center gap-x-6" href="{{ route('profil.sejarah') }}">
                <div>
                    <span class="text-sm font-body font-semibold text-gray-800 group-hover:text-blue-800">
                        <i class="ri-arrow-right-line me-2"></i>
                        Sejarah PTI
                    </span>
                </div>
            </a>
            <a class="group flex items-center gap-x-6" href="{{ route('profil.visiTujuanStrategi') }}">
                <div>
                    <span class="text-sm font-body font-semibold text-gray-800 group-hover:text-blue-800">
                        <i class="ri-arrow-right-line me-2"></i>
                        Visi, Tujuan dan Strategi
                    </span>
                </div>
            </a>
            <a class="group flex items-center gap-x-6" href="{{ route('profil.strukturOrganisasi') }}">
                <div>
                    <span class="text-sm font-body font-semibold text-gray-800 group-hover:text-blue-800">
                        <i class="ri-arrow-right-line me-2"></i>
                        Struktur Organisasi
                    </span>
                </div>
            </a>
            <a class="group flex items-center gap-x-6" href="{{ route('profil.dosen') }}">
                <div>
                    <span class="text-sm font-body font-semibold text-gray-800 group-hover:text-blue-800">
                        <i class="ri-arrow-right-line me-2"></i>
                        Dosen
                    </span>
                </div>
            </a>
        </div>
    </div>
</div>
