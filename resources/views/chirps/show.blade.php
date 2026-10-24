<x-layout>
    <x-slot:title>
        {{ $chirp->title }}
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">{{ $chirp->title }}</h1>

        <div class="flex items-center gap-1">
            <span class="text-sm font-semibold">{{ $chirp->user ? $chirp->user->name : 'Anonymous' }}</span>
            <span class="text-base-content/60">·</span>
            <span class="text-sm text-base-content/60">{{ $chirp->created_at->diffForHumans() }}</span>
            @if ($chirp->updated_at->gt($chirp->created_at->addSeconds(5)))
            <span class="text-base-content/60">·</span>
            <span class="text-sm text-base-content/60 italic">edited</span>
            @endif
        </div>

        <div class="card bg-base-100 shadow mt-8">
            <div class="card-body">
                <p class="mt-4">
                    {{ $chirp->message }}
                </p>

                <div class="card-actions justify-between mt-4">
                    <a href="/" class="btn btn-ghost btn-sm">
                        Return Back
                    </a>
                    <a href="/chirps/{{ $chirp->id }}/edit" class="btn btn-primary btn-sm">
                        Edit Chirp
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layout>