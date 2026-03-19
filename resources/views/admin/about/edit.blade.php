@extends('admin.layouts.dashboard')

@section('page_title', 'Tentang Kami')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-lg font-semibold text-forest">Tentang Kami</h2>
            <p class="text-xs text-gray-500 mt-1">
                Atur visi, misi, dan gambar utama section "Tentang Komunitas" di halaman depan.
            </p>
        </div>
    </div>

    <form
        action="{{ route('admin.about.update') }}"
        method="POST"
        enctype="multipart/form-data"
        class="grid md:grid-cols-3 gap-6"
    >
        @csrf

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-forest mb-2">Visi Komunitas</h3>
                <p class="text-[11px] text-gray-500 mb-3">
                    Teks ini akan tampil sebagai paragraf utama di card hijau pada section Tentang Komunitas.
                </p>
                <textarea
                    name="about_vision"
                    rows="4"
                    class="w-full rounded-lg border-gray-200 text-sm focus:ring-leaf focus:border-leaf"
                >{{ old('about_vision', $about_vision) }}</textarea>
                @error('about_vision')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-forest mb-3">Misi Komunitas</h3>
                <p class="text-[11px] text-gray-500 mb-4">
                    Setiap poin misi akan ditampilkan sebagai list dengan ikon checklist di bawah visi komunitas.
                </p>

                <div class="space-y-3">
                    @for($i = 1; $i <= 5; $i++)
                        @php
                            $field = "about_mission_{$i}";
                        @endphp
                        <div>
                            <label for="{{ $field }}" class="block text-xs font-semibold text-gray-600 mb-1">
                                Misi {{ $i }}
                            </label>
                            <textarea
                                id="{{ $field }}"
                                name="{{ $field }}"
                                rows="2"
                                class="w-full rounded-lg border-gray-200 text-sm focus:ring-leaf focus:border-leaf"
                            >{{ old($field, ${$field}) }}</textarea>
                            @error($field)
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-white rounded-2xl border border-gray-100 p-5 shadow-sm">
                <h3 class="text-sm font-semibold text-forest mb-3">Gambar "Tentang Kami"</h3>
                <p class="text-[11px] text-gray-500 mb-3">
                    Gambar ini akan tampil di kolom kanan section Tentang Komunitas. Resolusi disarankan minimal 1200×800px.
                </p>

                <div
                    x-data="{
                        preview: '{{ $about_image ? asset('storage/'.$about_image) : '' }}'
                    }"
                    class="space-y-3"
                >
                    <div class="aspect-video rounded-xl border border-dashed border-gray-200 overflow-hidden bg-gray-50 flex items-center justify-center">
                        <template x-if="preview">
                            <img :src="preview" alt="Preview gambar Tentang Kami" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!preview">
                            <div class="text-center text-gray-400 text-xs flex flex-col items-center">
                                <x-icons name="photo" class="w-8 h-8 mb-2" />
                                <span>Belum ada gambar. Unggah untuk membuat section lebih hidup.</span>
                            </div>
                        </template>
                    </div>

                    <label class="inline-flex items-center gap-2 px-3 py-2 rounded-full bg-forest text-spring text-xs font-semibold cursor-pointer hover:bg-forest/90">
                        <x-icons name="arrow-up-tray" class="w-4 h-4" />
                        <span>Pilih Gambar</span>
                        <input
                            type="file"
                            name="about_image"
                            accept="image/*"
                            class="hidden"
                            @change="
                                const [file] = $event.target.files;
                                if (file) {
                                    preview = URL.createObjectURL(file);
                                }
                            "
                        >
                    </label>

                    @error('about_image')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="bg-transparent">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center w-full rounded-full bg-leaf text-forest text-sm font-semibold px-4 py-2.5 shadow-sm hover:bg-leaf/90 transition"
                >
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
@endsection

