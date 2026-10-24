<x-layout>
    <x-slot:title>
        {{ $user->name }}
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <div class="flex items-center gap-4 mt-8">
            <div class="avatar">
                <div class="size-16 rounded-full">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}'s avatar" class="rounded-full" />
                </div>
            </div>
            <div>
                <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                <p class="text-sm text-base-content/60">
                    Joined {{ $user->created_at->format('F Y') }}
                    · {{ $chirps->total() }} {{ Str::plural('chirp', $chirps->total()) }}
                </p>
            </div>
        </div>

        <div class="space-y-4 mt-8">
            @forelse ($chirps as $chirp)
                <x-chirp :chirp="$chirp" />
            @empty
                <p class="text-center text-base-content/60 py-12">{{ $user->name }} hasn't chirped yet.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $chirps->links() }}
        </div>
    </div>
</x-layout>