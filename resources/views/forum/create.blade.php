@extends('layouts.app_public')

@section('title', 'Buat Post Baru · Forum Peduli Lingkungan')

@section('content')
    <section class="bg-cream py-10 sm:py-16 rv">
        <div class="max-w-3xl mx-auto px-4 sm:px-6">
            <div class="mb-6">
                <a href="{{ route('forum.index') }}" class="inline-flex items-center gap-1 text-xs text-moss/70 hover:text-leaf">
                    <x-icons name="arrow-left" class="w-3.5 h-3.5" />
                    Kembali ke Forum
                </a>
            </div>

            <div class="bg-white rounded-3xl border border-gray-100 p-5 sm:p-7 shadow-sm">
                <h1 class="font-heading text-xl md:text-2xl text-forest mb-2">
                    Buat Diskusi Baru
                </h1>
                <p class="text-xs md:text-sm text-moss/80 mb-5">
                    Ceritakan isu lingkungan, ajukan pertanyaan, atau ajak kolaborasi aksi hijau bersama komunitas.
                </p>

                @if ($errors->any())
                    <div class="mb-4 rounded-2xl bg-red-50 border border-red-100 px-4 py-3 text-xs text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('forum.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="label-field" for="title">Judul Diskusi</label>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            required
                            class="input-field"
                        >
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="label-field" for="category">Kategori</label>
                            <select
                                id="category"
                                name="category"
                                class="input-field"
                            >
                                <option value="">Pilih kategori</option>
                                <option value="Laporan" @selected(old('category') === 'Laporan')>Laporan</option>
                                <option value="Diskusi" @selected(old('category') === 'Diskusi')>Diskusi</option>
                                <option value="Pertanyaan" @selected(old('category') === 'Pertanyaan')>Pertanyaan</option>
                            </select>
                        </div>
                        <div>
                            <label class="label-field" for="location">Lokasi (opsional)</label>
                            <input
                                id="location"
                                type="text"
                                name="location"
                                value="{{ old('location') }}"
                                class="input-field"
                                placeholder="Contoh: Sungai Klawing, Purbalingga"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="label-field" for="body">Deskripsi</label>
                        <textarea
                            id="body"
                            name="body"
                            rows="6"
                            required
                            class="w-full rounded-2xl border border-gray-200 text-sm px-3 py-2.5 focus:ring-leaf focus:border-leaf"
                            placeholder="Jelaskan masalah, ide, atau pertanyaanmu secara jelas..."
                        >{{ old('body') }}</textarea>
                    </div>

                    <div
                        x-data="{ preview: null }"
                        class="space-y-2"
                    >
                        <label class="label-field">Gambar (opsional)</label>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                            <label class="inline-flex items-center gap-2 text-[11px] text-moss/80 cursor-pointer">
                                <input
                                    type="file"
                                    name="image"
                                    accept="image/*"
                                    class="hidden"
                                    @change="
                                        const [file] = $event.target.files;
                                        if (file) {
                                            preview = URL.createObjectURL(file);
                                        }
                                    "
                                >
                                <span class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-gray-200">
                                    <x-icons name="photo" class="w-4 h-4 text-moss/80" />
                                </span>
                                <span>Pilih gambar pendukung (maks. 2MB)</span>
                            </label>
                        </div>
                        <template x-if="preview">
                            <div class="mt-2">
                                <img :src="preview" alt="Preview" class="w-full max-h-56 rounded-2xl object-cover">
                            </div>
                        </template>
                    </div>

                    <div class="pt-3">
                        <button type="submit" class="btn-primary justify-center w-full sm:w-auto px-6 py-2.5 text-sm">
                            Terbitkan Diskusi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection

