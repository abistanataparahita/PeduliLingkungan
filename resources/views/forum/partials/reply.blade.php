@php
    $isAdmin = $reply->is_admin_reply;
@endphp

<div class="flex gap-3 {{ $level > 0 ? 'ml-6' : '' }}">
    <img
        src="{{ $reply->user->avatar_url }}"
        alt="{{ $reply->user->name }}"
        class="w-8 h-8 rounded-full object-cover ring-2 {{ $isAdmin ? 'ring-emerald-500/70' : 'ring-leaf/40' }}"
    >
    <div class="flex-1">
        <div class="rounded-2xl border px-3.5 py-2.5 mb-1 {{ $isAdmin ? 'border-emerald-500/60 bg-emerald-50/60' : 'border-gray-100 bg-gray-50' }}">
            <div class="flex items-center gap-2 text-[11px] text-moss/70 mb-1">
                <span class="font-semibold">{{ $reply->user->name }}</span>
                @if($isAdmin)
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-700 text-white text-[10px] font-semibold">
                        <x-icons name="leaf" class="w-3 h-3" />
                        Tim Peduli Lingkungan
                    </span>
                @endif
                <span>· {{ $reply->created_at->diffForHumans() }}</span>
            </div>
            <div class="text-xs text-moss/90">
                {!! nl2br(e(strip_tags($reply->body))) !!}
            </div>
            @if($reply->image_url)
                <img
                    src="{{ $reply->image_url }}"
                    alt="Lampiran"
                    class="mt-2 w-full max-h-48 rounded-xl object-cover"
                >
            @endif
        </div>
    </div>
</div>

@foreach($reply->children as $child)
    @include('forum.partials.reply', ['reply' => $child, 'post' => $post, 'level' => $level + 1])
@endforeach

