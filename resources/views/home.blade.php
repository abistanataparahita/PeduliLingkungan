@extends('layouts.app_public')

@section('title', 'Peduli Lingkungan — Hijaukan Aksimu')

@push('head')
    <style>
        [x-cloak] { display: none !important; }
    </style>
@endpush

@section('content')
    {{-- HERO dengan Banner Slider --}}
    @php
        $resolvedMeta = $bannerMeta ?? $banners->map(fn($b) => [
            'is_default'  => (bool) $b->is_default,
            'has_content' => (bool) ($b->title || $b->subtitle || $b->button_text),
            'show_card'   => (bool) ($b->is_default || $b->title || $b->subtitle || $b->button_text),
        ])->values()->toArray();
    @endphp
    <section
        id="hero"
        class="relative min-h-[90vh] min-h-[100dvh] sm:min-h-[90vh] overflow-hidden flex items-center rv"
        x-data="heroSlider({{ $banners->count() }}, {{ json_encode($resolvedMeta) }})"
        :class="isFullCover ? 'min-h-screen' : ''"
    >
        {{-- Background: Banner Slider atau fallback gradient --}}
        @forelse($banners as $i => $banner)
            @php
                $isCustom = ! $banner->is_default;
                $hasText = (bool) $banner->title || (bool) $banner->subtitle || (bool) $banner->button_text;
                $bgSize = 'cover';
            @endphp
            <div
                class="absolute inset-0 transition-opacity duration-800 ease-in-out"
                :class="current === {{ $i }} ? 'opacity-100 z-0' : 'opacity-0 z-0'"
                style="background-image: url('{{ $banner->image_url ?? asset('images/placeholder.svg') }}'); background-size: {{ $bgSize }}; background-position: center; background-repeat: no-repeat;"
            ></div>
        @empty
            <div class="absolute inset-0 z-0 bg-gradient-to-br from-forest via-moss to-black/90"></div>
        @endforelse
        {{-- Overlay & dekorasi hanya untuk default / custom berisi --}}
        <div
            class="absolute inset-0 z-[1] bg-gradient-to-b from-forest/40 via-forest/35 to-black/45"
            x-show="!isFullCover"
            x-cloak
        ></div>
        <div
            class="hidden md:block parallax-layer pointer-events-none absolute -left-24 top-16 w-80 h-80 bg-emerald-900/25 rounded-full blur-3xl z-[2]"
            data-speed="0.12"
            x-show="!isFullCover"
            x-cloak
        ></div>
        <div
            class="hidden md:block parallax-layer pointer-events-none absolute right-0 bottom-0 w-[360px] sm:w-[520px] h-[320px] sm:h-[520px] bg-emerald-900/20 rounded-[70%] blur-3xl z-[2]"
            data-speed="0.08"
            x-show="!isFullCover"
            x-cloak
        ></div>

        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20 pt-24 sm:pt-20 grid md:grid-cols-[minmax(0,1.3fr)_minmax(0,0.9fr)] gap-8 md:gap-10 items-end">
            <div>
                @forelse($banners as $i => $banner)
                    <div x-show="current === {{ $i }}" x-cloak>
                        @if($banner->is_default)
                            {{-- MODE 1: Default hero text --}}
                            <div>
                                <h1 class="font-heading text-4xl md:text-6xl lg:text-[4.3rem] leading-[0.95] text-white space-y-1">
                                    <span class="block">Hijaukan Aksimu,</span>
                                    <span class="block text-emerald-200 italic">Pedulikan Sekitarmu.</span>
                                </h1>
                                <p class="mt-8 text-sm md:text-base text-white/70 max-w-xl leading-relaxed italic">
                                    {{ setting('hero_tagline', 'Bergabung bersama generasi muda yang peduli bumi. Satu aksi nyata lebih berarti dari seribu wacana. Ubah keresahanmu jadi gerakan hijau di Purbalingga.') }}
                                </p>
                                <div class="mt-8 flex flex-wrap gap-3">
                                    <a
                                        href="{{ setting('wa_group_link', 'https://chat.whatsapp.com/Lo7XaVcbPi68DXbW212FX4') }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-2 rounded-full bg-emerald-700 text-white font-semibold text-sm px-6 py-3 shadow-lg shadow-emerald-900/30 hover:bg-emerald-800 transition-colors"
                                    >
                                        <x-icons name="whatsapp" class="w-5 h-5" />
                                        Gabung via WhatsApp
                                    </a>
                                    <a
                                        href="#about"
                                        class="inline-flex items-center gap-2 rounded-full border border-white/25 text-white/80 text-sm px-5 py-3 hover:bg-white/10 transition"
                                    >
                                        Kenalan Dulu
                                        <x-icons name="arrow-right" class="w-5 h-5" />
                                    </a>
                                </div>

                                <div class="mt-10 flex flex-wrap text-xs text-white/70 divide-x divide-white/15 border-y border-white/10">
                                    <div class="pr-6 py-3">
                                        <p class="uppercase tracking-[0.18em] text-white/50 mb-1">Followers</p>
                                        <p class="font-heading text-2xl" data-counter="{{ setting('hero_stats_followers', '1.193+') }}">0</p>
                                    </div>
                                    <div class="px-6 py-3">
                                        <p class="uppercase tracking-[0.18em] text-white/50 mb-1">Aksi & Konten</p>
                                        <p class="font-heading text-2xl" data-counter="{{ setting('hero_stats_actions', '86') }}">0</p>
                                    </div>
                                    <div class="px-6 py-3">
                                        <p class="uppercase tracking-[0.18em] text-white/50 mb-1">Since</p>
                                        <p class="font-heading text-2xl">{{ setting('hero_stats_since', '2025') }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- MODE 2: Custom banner text (semua field opsional) --}}
                            <div>
                                @if($banner->title || $banner->subtitle || $banner->button_text)
                                    <div class="inline-flex items-center gap-3 mb-6">
                                        <span class="px-4 py-1.5 rounded-full border border-lime/40 bg-lime/10 text-[11px] tracking-[0.2em] uppercase text-lime">
                                            Banner Campaign / Event
                                        </span>
                                    </div>
                                @endif

                                @if($banner->title)
                                    <h1 class="font-heading text-4xl md:text-5xl lg:text-[3.5rem] leading-tight text-white space-y-1">
                                        <span class="block">{{ $banner->title }}</span>
                                    </h1>
                                @endif

                                @if($banner->subtitle)
                                    <p class="mt-6 text-sm md:text-base text-white/75 max-w-xl leading-relaxed">
                                        {{ $banner->subtitle }}
                                    </p>
                                @endif

                                @if($banner->button_text)
                                    <div class="mt-8 flex flex-wrap gap-3">
                                        <a
                                            href="{{ $banner->button_url ?: '#' }}"
                                            class="inline-flex items-center gap-2 rounded-full bg-lime text-forest font-semibold text-sm px-6 py-3 shadow-lg shadow-lime/30 hover:bg-white transition"
                                        >
                                            <x-icons name="arrow-right" class="w-5 h-5" />
                                            <span>{{ $banner->button_text }}</span>
                                        </a>
                                        <a
                                            href="#events"
                                            class="inline-flex items-center gap-2 rounded-full border border-white/25 text-white/80 text-sm px-5 py-3 hover:bg-white/10 transition"
                                        >
                                            Lihat Event Lainnya
                                            <x-icons name="calendar-days" class="w-5 h-5" />
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @empty
                    {{-- Fallback jika tidak ada banner sama sekali --}}
                    <div>
                        <h1 class="font-heading text-4xl md:text-6xl lg:text-[4.3rem] leading-[0.95] text-white space-y-1">
                            <span class="block">Hijaukan Aksimu,</span>
                            <span class="block text-emerald-200 italic">Pedulikan Sekitarmu.</span>
                        </h1>
                        <p class="mt-8 text-sm md:text-base text-white/70 max-w-xl leading-relaxed italic">
                            {{ setting('hero_tagline', 'Bergabung bersama generasi muda yang peduli bumi. Satu aksi nyata lebih berarti dari seribu wacana. Ubah keresahanmu jadi gerakan hijau di Purbalingga.') }}
                        </p>
                        <div class="mt-8 flex flex-wrap gap-3">
                            <a
                                href="{{ setting('wa_group_link', 'https://chat.whatsapp.com/Lo7XaVcbPi68DXbW212FX4') }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 rounded-full bg-emerald-700 text-white font-semibold text-sm px-6 py-3 shadow-lg shadow-emerald-900/30 hover:bg-emerald-800 transition-colors"
                            >
                                <x-icons name="whatsapp" class="w-5 h-5" />
                                Gabung via WhatsApp
                            </a>
                            <a
                                href="#about"
                                class="inline-flex items-center gap-2 rounded-full border border-white/25 text-white/80 text-sm px-5 py-3 hover:bg-white/10 transition"
                            >
                                Kenalan Dulu
                                <x-icons name="arrow-right" class="w-5 h-5" />
                            </a>
                        </div>

                        <div class="mt-10 flex flex-wrap text-xs text-white/70 divide-x divide-white/15 border-y border-white/10">
                            <div class="pr-6 py-3">
                                <p class="uppercase tracking-[0.18em] text-white/50 mb-1">Followers</p>
                                <p class="font-heading text-2xl" data-counter="{{ setting('hero_stats_followers', '1.193+') }}">0</p>
                            </div>
                            <div class="px-6 py-3">
                                <p class="uppercase tracking-[0.18em] text-white/50 mb-1">Aksi & Konten</p>
                                <p class="font-heading text-2xl" data-counter="{{ setting('hero_stats_actions', '86') }}">0</p>
                            </div>
                            <div class="px-6 py-3">
                                <p class="uppercase tracking-[0.18em] text-white/50 mb-1">Since</p>
                                <p class="font-heading text-2xl">{{ setting('hero_stats_since', '2025') }}</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div
                class="hidden md:block pb-10"
                x-show="total === 0 || (meta[current] && meta[current].show_card)"
                x-cloak
            >
                <div class="relative rounded-3xl border border-white/10 bg-white/5 backdrop-blur-xl p-6">
                    <p class="text-[11px] uppercase tracking-[0.2em] text-spring/80 mb-3">Aksi Nyata · Komunitas</p>
                    <p class="font-heading text-2xl text-white mb-4">Dari keresahan,<br>jadi gerakan hijau.</p>
                    <p class="text-xs text-white/70 leading-relaxed">
                        Lebih dari sekadar komunitas, Peduli Lingkungan adalah ruang tumbuh bagi pemuda
                        yang ingin berdampak — lewat penanaman pohon, edukasi sekolah, bersih sungai, dan aksi zero waste.
                    </p>
                </div>
            </div>
        </div>

        {{-- Indikator lingkaran --}}
        @if($banners->count() > 0)
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 z-[30] flex items-center gap-3">
                @foreach($banners as $i => $banner)
                    <button
                        type="button"
                        @click="goTo({{ $i }})"
                        class="rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-lime/50"
                        :class="current === {{ $i }} ? 'bg-lime scale-125' : 'border border-white/60 bg-black/20 hover:bg-white/30'"
                        aria-label="Slide {{ $i + 1 }}"
                        style="min-width: 10px; min-height: 10px;"
                    ></button>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ABOUT --}}
    <section id="about" class="bg-cream py-12 sm:py-20 rv">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 grid md:grid-cols-2 gap-8 md:gap-12 items-start">
            <div>
                <div class="flex items-center gap-4 mb-4">
                    <span class="font-heading text-5xl text-moss/20">01</span>
                    <div class="h-10 w-px bg-moss/20"></div>
                    <span class="section-eyebrow text-xs text-moss/70">Tentang Komunitas</span>
                </div>
                <h2 class="font-heading text-3xl md:text-4xl text-forest leading-tight">
                    Pemuda yang<br>
                    Bergerak untuk<br>
                    <span class="italic text-leaf">Bumi Kita.</span>
                </h2>
            </div>
            <div class="space-y-4 text-sm text-moss leading-relaxed">
                <p>
                    <strong>Pemuda Peduli Lingkungan</strong> adalah komunitas yang berfokus pada peningkatan kesadaran
                    dan partisipasi generasi muda dalam menjaga serta melestarikan lingkungan hidup di sekitarnya.
                </p>
                <p>
                    Sejak 2025, kami aktif menginisiasi beragam kegiatan edukasi, aksi sosial, dan kampanye peduli lingkungan
                    yang mendorong perubahan positif di masyarakat — dari penanaman pohon, bersih sungai, hingga program zero waste.
                </p>
                <p>
                    Dalam setiap gerakan, kami mengedepankan nilai kepemimpinan, komitmen, dan kemampuan memecahkan masalah
                    agar pemuda tidak hanya peduli, tetapi juga siap menjadi agen perubahan di tengah masyarakat.
                </p>
                <p>
                    Lewat ruang belajar dan kolaborasi yang terbuka, kami mengajak seluruh generasi muda untuk
                    <em>Hijaukan Aksimu, Pedulikan Sekitarmu</em> dan bersama-sama merawat bumi untuk masa depan yang lebih baik.
                </p>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 mt-10 grid md:grid-cols-3 gap-8">
            <div class="grid grid-cols-2 gap-3 md:col-span-1">
                <div class="rounded-2xl bg-white p-5 shadow-sm">
                    <div class="text-leaf mb-2"><x-icons name="user-circle" class="w-8 h-8" /></div>
                    <h3 class="font-semibold text-sm mb-1">Leadership</h3>
                    <p class="text-xs text-moss/80">Tumbuh sebagai pemimpin muda yang berdampak bagi sekitar.</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm">
                    <div class="text-leaf mb-2"><x-icons name="check-circle" class="w-8 h-8" /></div>
                    <h3 class="font-semibold text-sm mb-1">Commitment</h3>
                    <p class="text-xs text-moss/80">Konsisten dalam setiap aksi dan janji pada alam.</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm">
                    <div class="text-leaf mb-2"><x-icons name="light-bulb" class="w-8 h-8" /></div>
                    <h3 class="font-semibold text-sm mb-1">Solve Problem</h3>
                    <p class="text-xs text-moss/80">Berpikir kritis dan kreatif mencari solusi hijau.</p>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm">
                    <div class="text-leaf mb-2"><x-icons name="arrow-right" class="w-8 h-8" /></div>
                    <h3 class="font-semibold text-sm mb-1">Change & Grow</h3>
                    <p class="text-xs text-moss/80">Terus berkembang demi dampak yang semakin besar.</p>
                </div>
            </div>

            <div class="relative md:col-span-1">
                <div class="rounded-3xl bg-forest text-white p-7 relative overflow-hidden h-full flex flex-col">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-lime/10 rounded-full blur-2xl"></div>
                    <h3 class="font-heading text-xl mb-3">Visi Komunitas</h3>
                    <p class="text-sm text-spring/80 leading-relaxed">
                        {{ setting('about_vision', 'Menjadi komunitas pemuda yang menginspirasi gerakan hijau di Purbalingga dan sekitarnya — dengan aksi nyata yang berkelanjutan, inklusif, dan berdampak langsung.') }}
                    </p>
                    <div class="mt-6 inline-flex items-center gap-2 rounded-full bg-lime text-forest text-[11px] font-semibold px-4 py-1.5">
                        100% Gratis · Terbuka · Berdampak
                    </div>

                    @php
                        $missions = [
                            setting('about_mission_1', 'Meningkatkan kesadaran lingkungan di kalangan generasi muda melalui edukasi dan kampanye peduli lingkungan.'),
                            setting('about_mission_2', 'Menggerakkan aksi nyata seperti kegiatan penghijauan, kebersihan lingkungan, dan program sosial berbasis lingkungan.'),
                            setting('about_mission_3', 'Membangun karakter kepemimpinan pemuda yang peduli terhadap keberlanjutan lingkungan dan masyarakat.'),
                            setting('about_mission_4', 'Mendorong kolaborasi antara pemuda, masyarakat, dan berbagai pihak untuk menciptakan perubahan positif bagi lingkungan.'),
                            setting('about_mission_5', 'Menjadi wadah pengembangan diri bagi anggota untuk belajar, berkontribusi, dan tumbuh bersama dalam gerakan peduli lingkungan.'),
                        ];
                        $hasMissions = collect($missions)->filter(fn($m) => !empty($m))->isNotEmpty();
                    @endphp

                    @if($hasMissions)
                        <div class="mt-6 pt-5 border-t border-white/15">
                            <p class="text-xs font-semibold text-spring/80 uppercase tracking-[0.18em] mb-3">
                                Misi Komunitas
                            </p>
                            <ul class="space-y-2.5 text-sm text-spring/85">
                                @foreach($missions as $mission)
                                    @if(! empty($mission))
                                        <li class="flex items-start gap-2.5">
                                            <span class="mt-[3px] inline-flex w-4 h-4 rounded-full bg-lime/90 text-forest items-center justify-center">
                                                <x-icons name="check" class="w-3 h-3" />
                                            </span>
                                            <span class="leading-relaxed">{{ $mission }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>

            <div class="md:col-span-1">
                @php
                    $aboutImage = setting('about_image');
                @endphp
                @if($aboutImage)
                    <div class="relative rounded-3xl overflow-hidden bg-moss/20 h-full min-h-[260px]">
                        <img
                            src="{{ asset('storage/'.$aboutImage) }}"
                            alt="Tentang Komunitas Peduli Lingkungan"
                            class="w-full h-full object-cover"
                            onerror="this.src='/images/placeholder.svg'"
                        >
                    </div>
                @else
                    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-forest via-moss to-black/80 h-full min-h-[260px] flex items-center justify-center">
                        <div class="absolute inset-0 opacity-30 bg-[radial-gradient(circle_at_top,_#a4e57a_0,_transparent_55%)]"></div>
                        <div class="relative z-10 flex flex-col items-center text-center text-spring/90 px-6">
                            <div class="w-16 h-16 rounded-full bg-white/10 flex items-center justify-center mb-4">
                                <x-icons name="leaf" class="w-8 h-8" />
                            </div>
                            <p class="text-sm font-semibold mb-1">Cerita Komunitas</p>
                            <p class="text-xs text-spring/80">
                                Tambahkan foto "Tentang Kami" dari dashboard admin untuk membuat section ini lebih hidup.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- EVENTS --}}
    <section id="events" class="bg-forest text-white py-12 sm:py-20 rv">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <div>
                    <span class="section-eyebrow text-lime">02 — Aksi Nyata</span>
                    <h2 class="font-heading text-3xl md:text-4xl mt-2">
                        Event <span class="italic text-lime">Terdekat</span>
                    </h2>
                </div>
                <p class="text-xs md:text-sm text-spring/80 max-w-sm md:text-right">
                    Semua event terbuka untuk umum. Datang, bergerak, dan rasakan perbedaannya langsung.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @forelse($events as $index => $event)
                    @php
                        $isPast = $event->event_date && $event->event_date->isPast();
                        $isUpcoming = $event->event_date && $event->event_date->isFuture();
                        $isToday = $event->event_date && $event->event_date->isToday();

                        $cardClasses = 'relative rounded-3xl border p-6 flex flex-col justify-between transition group';
                        if ($index === 0) {
                            $cardClasses .= ' md:col-span-2';
                        }

                        if ($isToday) {
                            $cardClasses .= ' bg-emerald-700/90 border-lime/60 shadow-lg shadow-lime/40';
                        } elseif ($isUpcoming) {
                            $cardClasses .= ' bg-moss/85 border-lime/50 shadow-md shadow-lime/30';
                        } elseif ($isPast) {
                            $cardClasses .= ' bg-moss/60 border-white/10 opacity-70 grayscale hover:opacity-80';
                        } else {
                            $cardClasses .= ' bg-moss/80 border-white/10';
                        }
                    @endphp

                    <article class="{{ $cardClasses }} hover:-translate-y-1">
                        @if($isUpcoming)
                            <span class="absolute top-4 left-4 text-[10px] font-semibold px-3 py-1 rounded-full bg-lime text-forest uppercase tracking-[0.18em] inline-flex items-center gap-1.5">
                                <x-icons name="calendar-days" class="w-3.5 h-3.5" />
                                Akan Datang
                            </span>
                        @elseif($isToday)
                            <span class="absolute top-4 left-4 text-[10px] font-semibold px-3 py-1 rounded-full bg-amber-400 text-forest uppercase tracking-[0.18em] inline-flex items-center gap-1.5 animate-pulse">
                                <x-icons name="star" class="w-3.5 h-3.5" />
                                Hari Ini!
                            </span>
                        @elseif($isPast)
                            <span class="absolute top-4 left-4 text-[10px] font-semibold px-3 py-1 rounded-full bg-white/10 text-spring/80 uppercase tracking-[0.18em] inline-flex items-center gap-1.5">
                                <x-icons name="check-circle" class="w-3.5 h-3.5" />
                                Sudah Berlalu
                            </span>
                        @endif

                        @if($index === 0)
                            <span class="absolute top-4 right-4 text-[10px] font-semibold px-3 py-1 rounded-full bg-lime text-forest uppercase tracking-[0.18em]">
                                Terdekat
                            </span>
                        @endif
                        <div class="mt-8 flex items-start gap-4 mb-4">
                            <div class="flex flex-col items-center justify-center rounded-2xl bg-forest/80 px-3.5 py-2.5 text-center shadow-inner shadow-black/20">
                                <span class="font-heading text-2xl leading-none">
                                    {{ $event->event_date?->format('d') }}
                                </span>
                                <span class="text-[10px] uppercase tracking-[0.18em] text-spring/80">
                                    {{ $event->event_date?->format('M') }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-heading text-lg mb-1 group-hover:text-lime transition line-clamp-2">
                                    {{ $event->title }}
                                </h3>
                                <p class="text-xs text-spring/80 line-clamp-3">
                                    {{ $event->description }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 mt-2 text-[11px] text-spring/80">
                            <span class="inline-flex items-center gap-1.5">
                                <x-icons name="calendar-days" class="w-3.5 h-3.5" />
                                {{ $event->event_date?->translatedFormat('d F Y') }}
                                @if($event->event_time)
                                    · {{ \Illuminate\Support\Str::of($event->event_time)->substr(0,5) }} WIB
                                @endif
                            </span>
                            <span class="inline-flex items-center gap-1.5">
                                <x-icons name="map-pin" class="w-3.5 h-3.5" />
                                {{ $event->location }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-white/10 border border-white/15">
                                {{ $event->category }}
                            </span>
                        </div>

                        @php
                            $eventDetailUrl = \Illuminate\Support\Facades\Route::has('events.show')
                                ? route('events.show', $event->slug)
                                : route('events.index');
                        @endphp

                        <div class="mt-4 flex justify-end">
                            <a
                                href="{{ $eventDetailUrl }}"
                                class="inline-flex items-center gap-1.5 text-[12px] font-semibold text-lime hover:text-white hover:underline"
                            >
                                Lihat Detail
                                <x-icons name="arrow-right" class="w-3.5 h-3.5" />
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-spring/80">
                        Belum ada event terdekat. Nantikan jadwal aksi berikutnya.
                    </p>
                @endforelse
            </div>

            <div class="mt-8 text-right">
                <a href="{{ route('events.index') }}" class="inline-flex items-center gap-2 text-sm text-spring hover:text-lime">
                    Lihat Semua Event →
                </a>
            </div>
        </div>
    </section>

    {{-- PRODUCTS --}}
    <section id="products" class="bg-cream py-12 sm:py-20 rv">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <div>
                    <span class="section-eyebrow text-leaf">Merchandise</span>
                    <h2 class="font-heading text-3xl md:text-4xl mt-2 text-forest">
                        Produk <span class="italic text-leaf">Peduli Lingkungan</span>
                    </h2>
                </div>
                <p class="text-xs md:text-sm text-moss/80 max-w-sm md:text-right">
                    Kaos, mug, dan merchandise lainnya. Dukung gerakan hijau sekaligus dapat barang keren.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <a href="{{ route('products.show', $product) }}" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition border border-gray-100">
                        <div class="aspect-square bg-gray-100 relative">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-[1.03] transition duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">Tanpa gambar</div>
                            @endif
                            @if($product->is_preorder)
                                <span class="absolute top-2 left-2 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold {{ $product->is_preorder_closed ? 'bg-gray-600 text-white' : 'bg-violet-600 text-white' }}">
                                    {{ $product->is_preorder_closed ? 'Pre Order Ditutup' : 'Pre Order' }}
                                </span>
                            @elseif($product->has_discount)
                                <span class="absolute top-2 right-2 inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-600 text-white">Diskon</span>
                            @endif
                        </div>
                        <div class="p-4">
                            <p class="text-[11px] text-gray-500">{{ $product->sku }}</p>
                            <h3 class="font-semibold text-forest mt-0.5 line-clamp-2 group-hover:text-leaf transition">{{ $product->name }}</h3>
                            <p class="mt-2 text-sm font-medium text-forest">
                                {{ $product->formatted_final_price }}
                                @if($product->has_discount)
                                    <span class="text-gray-400 line-through text-xs ml-1">{{ $product->formatted_selling_price }}</span>
                                @endif
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-moss/80 py-8">Belum ada produk. Nantikan merchandise resmi Peduli Lingkungan.</p>
                @endforelse
            </div>

            @if($products->isNotEmpty())
                <div class="mt-8 text-right">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-leaf hover:text-forest transition">
                        Lihat Semua Produk →
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- WHY JOIN --}}
    <section id="why-join" class="bg-cream py-12 sm:py-20 rv">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-10 items-end mb-12">
                <div>
                    <span class="section-eyebrow text-leaf">03 — Kenapa Join?</span>
                    <h2 class="font-heading text-3xl md:text-4xl mt-2 text-forest">
                        Lebih dari<br>
                        Sekadar <span class="italic text-leaf">Komunitas.</span>
                    </h2>
                </div>
                <div class="text-sm text-moss leading-relaxed">
                    Bergabung di Peduli Lingkungan bukan hanya soal menanam pohon. Ini tentang
                    menemukan versi terbaik dirimu, dikelilingi orang-orang yang punya misi yang sama:
                    menjaga bumi lewat aksi nyata.
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $benefits = [
                        ['no' => '01', 'icon' => 'leaf', 'title' => 'Aksi Nyata, Dampak Riil', 'desc' => 'Program dengan output jelas dan terasa langsung.'],
                        ['no' => '02', 'icon' => 'user-group', 'title' => 'Jaringan Pemuda Berdampak', 'desc' => 'Terhubung dengan ratusan pemuda visioner.'],
                        ['no' => '03', 'icon' => 'arrow-trending-up', 'title' => 'Kembangkan Skill Kepemimpinan', 'desc' => 'Latihan memimpin tim dan merancang program.'],
                        ['no' => '04', 'icon' => 'star', 'title' => 'Portofolio & Pengakuan', 'desc' => 'Dokumentasi aksi yang bisa kamu banggakan.'],
                        ['no' => '05', 'icon' => 'heart', 'title' => 'Komunitas Supportif', 'desc' => 'Lingkungan positif dan saling menguatkan.'],
                        ['no' => '06', 'icon' => 'globe-alt', 'title' => 'Gratis & Terbuka', 'desc' => 'Tanpa biaya, terbuka untuk siapa saja.'],
                    ];
                @endphp

                @foreach($benefits as $benefit)
                    <div class="relative rounded-3xl bg-white/70 p-6 shadow-sm overflow-hidden group hover:-translate-y-1 hover:shadow-lg transition">
                        <span class="absolute -right-4 -top-2 font-heading text-6xl text-moss/5">{{ $benefit['no'] }}</span>
                        <div class="relative z-10">
                            <div class="text-leaf mb-3"><x-icons :name="$benefit['icon']" class="w-8 h-8" /></div>
                            <h3 class="font-semibold text-sm mb-1 group-hover:text-leaf transition">
                                {{ $benefit['title'] }}
                            </h3>
                            <p class="text-xs text-moss/80 leading-relaxed">
                                {{ $benefit['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- JOIN CTA --}}
    <section id="join" class="bg-cream py-12 sm:py-20 rv">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-0 rounded-3xl bg-forest text-white overflow-hidden">
                <div class="p-8 md:p-10 flex flex-col justify-between">
                    <div>
                        <span class="section-eyebrow text-spring/80">04 — Bergabung</span>
                        <h2 class="font-heading text-3xl md:text-4xl mt-2 mb-4">
                            Siap Jadi Bagian<br>
                            dari Gerakan Hijau Purbalingga?
                        </h2>
                        <p class="text-sm text-spring/80 leading-relaxed">
                            Lebih dari 1.000 pemuda sudah memilih untuk bergerak.
                            Sekarang giliranmu. Klik sekali, dan kamu sudah jadi bagian dari komunitas kami.
                        </p>
                    </div>
                    <div class="mt-8 space-y-3">
                        <a
                            href="{{ setting('wa_group_link', 'https://chat.whatsapp.com/Lo7XaVcbPi68DXbW212FX4') }}"
                            target="_blank"
                            class="inline-flex items-center gap-3 rounded-2xl bg-[#25D366] text-white text-sm font-semibold px-6 py-3 shadow-lg shadow-[#25D366]/40 hover:bg-[#1db954] transition"
                        >
                            <span class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center"><x-icons name="whatsapp" class="w-5 h-5" /></span>
                            <span>
                                <span class="block leading-none">Gabung via WhatsApp</span>
                                <span class="block text-[11px] opacity-80">Klik → langsung masuk grup komunitas</span>
                            </span>
                        </a>
                        <p class="text-[11px] text-spring/70">
                            Kontak admin: {{ setting('wa_phone', '0812-2942-8356') }} (Furqon)
                        </p>
                    </div>
                </div>
                <div class="relative bg-moss/70 flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-br from-lime/10 via-transparent to-forest/60"></div>
                    <div class="relative z-10 text-center">
                        <div class="text-leaf/80 mb-4 drop-shadow-xl"><x-icons name="globe-alt" class="w-20 h-20 md:w-24 md:h-24" /></div>
                        <div class="inline-flex flex-col items-start bg-white/10 rounded-2xl px-4 py-3 mb-3">
                            <span class="font-heading text-2xl">
                                {{ setting('hero_stats_followers', '1.193+') }}
                            </span>
                            <span class="text-[11px] uppercase tracking-[0.18em] text-spring/80">
                                Member Aktif
                            </span>
                        </div>
                        <div class="inline-flex flex-col items-start bg-white/10 rounded-2xl px-4 py-3 ml-3">
                            <span class="font-heading text-2xl">
                                {{ setting('hero_stats_since', '2025') }}
                            </span>
                            <span class="text-[11px] uppercase tracking-[0.18em] text-spring/80">
                                Berdiri Sejak
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- GALLERY: MOMEN DI LAPANGAN --}}
    <section id="gallery" class="bg-cream py-12 sm:py-20 rv" x-data="{ lightboxOpen: false, lightboxImage: null, lightboxTitle: null, lightboxDate: null }">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <div>
                    <span class="section-eyebrow text-leaf">05 — Galeri</span>
                    <h2 class="font-heading text-3xl md:text-4xl mt-2 text-forest">
                        Momen Bersama <span class="italic text-leaf">di Lapangan</span>
                    </h2>
                </div>
                <p class="text-xs md:text-sm text-moss/80 max-w-sm md:text-right">
                    Setiap foto adalah bukti bahwa aksi kami nyata — bukan sekadar kata-kata.
                </p>
            </div>

            <div class="grid md:grid-cols-4 md:grid-rows-2 gap-4">
                @forelse($galleries as $index => $item)
                    <div
                        class="group relative rounded-3xl overflow-hidden cursor-pointer transform transition duration-500 hover:-translate-y-1 hover:shadow-2xl hover:shadow-forest/25 {{ $index === 0 ? 'md:row-span-2 md:col-span-2' : '' }}"
                        @click="
                            lightboxOpen = true;
                            lightboxImage = '{{ asset('storage/'.$item->image) }}';
                            lightboxTitle = '{{ addslashes($item->title) }}';
                            lightboxDate = '{{ $item->activity_date?->translatedFormat('d F Y') ?? 'Tanggal tidak tersedia' }}';
                        "
                    >
                        <img
                            src="{{ asset('storage/'.$item->image) }}"
                            alt="{{ $item->title }}"
                            class="w-full h-full object-cover transition duration-500 group-hover:scale-[1.03]"
                            onerror="this.src='/images/placeholder.svg'"
                        >
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent opacity-90"></div>

                        <div class="absolute left-4 right-4 bottom-4">
                            <p class="text-[11px] text-white/80 mb-1">
                                {{ $item->activity_date?->translatedFormat('d F Y') ?? 'Tanggal tidak tersedia' }}
                            </p>
                            <h3
                                class="font-semibold text-sm md:text-base text-white leading-snug transform origin-bottom-left transition duration-300 group-hover:scale-[1.08]"
                            >
                                {{ $item->title }}
                            </h3>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-moss/80">
                        Belum ada foto galeri yang ditampilkan. Tambahkan dari dashboard admin.
                    </p>
                @endforelse
            </div>

            <div class="mt-8 text-right">
                <a href="{{ route('galleries.index') }}" class="inline-flex items-center gap-2 text-sm text-moss hover:text-leaf">
                    Lihat Semua Momen →
                </a>
            </div>

            <!-- Lightbox -->
            <div
                x-show="lightboxOpen"
                x-transition
                class="fixed inset-0 z-50 bg-black/80 flex items-center justify-center px-4"
                @click.self="lightboxOpen = false"
            >
                <div class="max-w-3xl w-full">
                    <div class="flex justify-end mb-2">
                        <button
                            type="button"
                            class="inline-flex items-center gap-2 text-white text-sm px-3 py-1 rounded-full bg-white/10 hover:bg-white/20"
                            @click="lightboxOpen = false"
                        >
                            <x-icons name="x-mark" class="w-4 h-4" /> Tutup
                        </button>
                    </div>
                    <div class="bg-black rounded-3xl overflow-hidden">
                        <img :src="lightboxImage" :alt="lightboxTitle" class="w-full max-h-[70vh] object-contain">
                        <div class="p-4 text-sm text-white/90">
                            <p class="text-[11px] text-white/70 mb-1" x-text="lightboxDate"></p>
                            <p class="font-semibold" x-text="lightboxTitle"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ARTICLES --}}
    <section id="articles" class="bg-cream py-12 sm:py-20 rv">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <div>
                    <span class="section-eyebrow text-leaf">06 — Artikel</span>
                    <h2 class="font-heading text-3xl md:text-4xl mt-2 text-forest">
                        Insight & Cerita<br>
                        dari <span class="italic text-leaf">Lapangan</span>
                    </h2>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                @forelse($articles as $article)
                    <article class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-lg transition group">
                        @if($article->thumbnail)
                            <img
                                src="{{ asset('storage/'.$article->thumbnail) }}"
                                alt="{{ $article->title }}"
                                class="w-full h-40 object-cover group-hover:scale-[1.03] transition duration-500"
                            >
                        @endif
                        <div class="p-5 space-y-2">
                            <p class="text-[11px] uppercase tracking-[0.18em] text-moss/60">
                                {{ $article->published_at?->translatedFormat('d M Y') ?? 'Draft' }}
                            </p>
                            <h3 class="font-semibold text-sm group-hover:text-leaf transition">
                                {{ $article->title }}
                            </h3>
                            <p class="text-xs text-moss/80 line-clamp-3">
                                {{ $article->excerpt }}
                            </p>
                            <a href="{{ route('articles.show', $article->slug) }}" class="inline-flex items-center gap-1 text-xs text-leaf mt-2">
                                Baca Selengkapnya →
                            </a>
                        </div>
                    </article>
                @empty
                    <p class="text-sm text-moss/80">
                        Belum ada artikel yang diterbitkan.
                    </p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section id="testimonials" class="bg-moss/95 text-white py-12 sm:py-20 rv">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <div>
                    <span class="section-eyebrow text-lime">07 — Cerita Member</span>
                    <h2 class="font-heading text-3xl md:text-4xl mt-2">
                        Kata Mereka yang<br>
                        <span class="italic text-lime">Sudah Bergerak</span>
                    </h2>
                </div>
                <div class="flex items-center gap-2">
                    @auth
                        <a
                            href="{{ route('testimonials.create') }}"
                            class="inline-flex items-center gap-2 rounded-full bg-lime text-forest font-semibold text-sm px-5 py-3 hover:bg-white transition"
                        >
                            Tulis Testimoni
                            <x-icons name="arrow-right" class="w-5 h-5" />
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-full border border-white/25 text-white/90 font-semibold text-sm px-5 py-3 hover:bg-white/10 transition"
                        >
                            Login untuk menulis testimoni
                            <x-icons name="arrow-right" class="w-5 h-5" />
                        </a>
                    @endauth
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4">
                @forelse($testimonials as $testimonial)
                    <figure class="bg-forest/60 rounded-3xl p-6 border border-white/5">
                        <p class="text-xs text-spring/90 italic leading-relaxed mb-4">
                            “{{ $testimonial->quote }}”
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-leaf to-lime flex items-center justify-center text-lg">
                                {{ mb_substr($testimonial->name, 0, 1) }}
                            </div>
                            <div>
                                <figcaption class="text-sm font-semibold">
                                    {{ $testimonial->name }}
                                </figcaption>
                                <p class="text-[11px] text-spring/80">
                                    {{ $testimonial->role }}
                                </p>
                            </div>
                        </div>
                    </figure>
                @empty
                    <p class="text-sm text-spring/80">
                        Belum ada testimoni yang ditambahkan.
                    </p>
                @endforelse
            </div>
        </div>
    </section>
@if(isset($popupEvent) && $popupEvent)
    <div
        x-data="{
            show: false,
            init() {
                setTimeout(() => {
                    this.show = true;
                }, 3000);
            }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
        style="display: none;"
    >
        <div
            class="absolute inset-0 bg-black/70 backdrop-blur-sm"
            @click="show = false"
        ></div>

        <div
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="relative z-10 max-w-md w-full bg-white rounded-2xl overflow-hidden shadow-2xl"
        >
            <button
                @click="show = false"
                class="absolute top-3 right-3 z-20 w-8 h-8 bg-black/40 hover:bg-black/60 
                       text-white rounded-full flex items-center justify-center 
                       transition-colors backdrop-blur-sm"
                aria-label="Tutup popup"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                     stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>

            @php
                $popupUrl = $popupEvent->popup_redirect_url ?? '';
            @endphp

            <a
                href="{{ $popupUrl ?: '#' }}"
                {{ $popupUrl ? 'target="_blank"' : '' }}
                class="block relative"
            >
                <img
                    src="{{ $popupEvent->image_url ?? asset('images/placeholder.svg') }}"
                    alt="Poster {{ $popupEvent->title }}"
                    class="w-full object-cover max-h-[420px]"
                    onerror="this.src='/images/placeholder.svg'"
                >
                <div class="absolute bottom-0 left-0 right-0 h-24 
                            bg-gradient-to-t from-[#1a3a2a]/90 to-transparent"></div>
                <div class="absolute bottom-4 left-4 right-4">
                    <span class="text-[#7ecb5f] text-xs font-bold tracking-widest uppercase">
                        Event Mendatang
                    </span>
                    <h3 class="text-white font-bold text-lg leading-tight mt-1">
                        {{ $popupEvent->title }}
                    </h3>
                    <p class="text-white/70 text-sm mt-1">
                        📅 {{ \Carbon\Carbon::parse($popupEvent->event_date)->translatedFormat('d F Y') }}
                        · 📍 {{ \Illuminate\Support\Str::limit($popupEvent->location, 35) }}
                    </p>
                </div>
            </a>

            <div class="p-4 flex gap-3">
                @php
                    $url = $popupEvent->popup_redirect_url ?? '';
                    $urlLower = strtolower($url);
                    $isInstagram = str_contains($urlLower, 'instagram.com');
                    $isForms = str_contains($urlLower, 'docs.google.com') || str_contains($urlLower, 'forms.gle') || str_contains($urlLower, 'form');
                    $isWa = str_contains($urlLower, 'wa.me') || str_contains($urlLower, 'whatsapp.com') || empty($url);
                    $isYoutube = str_contains($urlLower, 'youtube.com') || str_contains($urlLower, 'youtu.be');
                @endphp

                @if($isInstagram)
                    <a
                        href="{{ $url }}"
                        target="_blank"
                        class="flex-1 bg-gradient-to-r from-fuchsia-600 to-purple-600 text-white text-center py-2.5 px-4
                               rounded-xl text-sm font-semibold hover:from-fuchsia-700 hover:to-purple-700 transition-colors
                               flex items-center justify-center gap-2"
                    >
                        <x-icons name="instagram" class="w-4 h-4" />
                        Lihat di Instagram
                    </a>
                @elseif($isForms)
                    <a
                        href="{{ $url }}"
                        target="_blank"
                        class="flex-1 bg-blue-600 text-white text-center py-2.5 px-4
                               rounded-xl text-sm font-semibold hover:bg-blue-700 transition-colors
                               flex items-center justify-center gap-2"
                    >
                        <x-icons name="document-text" class="w-4 h-4" />
                        Daftar Sekarang
                    </a>
                @elseif($isYoutube)
                    <a
                        href="{{ $url }}"
                        target="_blank"
                        class="flex-1 bg-red-600 text-white text-center py-2.5 px-4
                               rounded-xl text-sm font-semibold hover:bg-red-700 transition-colors
                               flex items-center justify-center gap-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                            <path d="M4.5 5.653A2.25 2.25 0 0 1 7.875 3.68l12.75 7.099a2.25 2.25 0 0 1 0 3.942L7.875 21.82A2.25 2.25 0 0 1 4.5 19.847V5.653Z"/>
                        </svg>
                        Tonton di YouTube
                    </a>
                @elseif($isWa)
                    <a
                        href="{{ setting('wa_group_link', '#') }}"
                        target="_blank"
                        class="flex-1 bg-[#25D366] text-white text-center py-2.5 px-4
                               rounded-xl text-sm font-semibold hover:bg-[#1fb855] transition-colors
                               flex items-center justify-center gap-2"
                    >
                        <x-icons name="whatsapp" class="w-4 h-4" />
                        Gabung via WhatsApp
                    </a>
                @else
                    <a
                        href="{{ $url }}"
                        target="_blank"
                        class="flex-1 bg-emerald-900 text-white text-center py-2.5 px-4
                               rounded-xl text-sm font-semibold hover:bg-emerald-950 transition-colors
                               flex items-center justify-center gap-2"
                    >
                        <x-icons name="arrow-right" class="w-4 h-4" />
                        Lihat Selengkapnya
                    </a>
                @endif
            </div>

            <p class="text-center text-xs text-gray-400 pb-3 px-4">
                Klik di luar untuk menutup • Popup ini muncul setiap refresh
            </p>
        </div>
    </div>
@endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('heroSlider', (total, meta) => ({
                current: 0,
                total: total,
                meta: meta || [],
                init() {
                    if (this.total > 1) {
                        setInterval(() => {
                            this.current = (this.current + 1) % this.total;
                        }, 3000);
                    }
                },
                get isFullCover() {
                    const m = this.meta[this.current];
                    return m && !m.is_default && !m.has_content;
                },
                goTo(i) {
                    if (i >= 0 && i < this.total) {
                        this.current = i;
                    }
                },
            }));
        });
    </script>
@endpush
