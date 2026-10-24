<x-layout>
    <x-slot:title>
        Feed
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">Latest Chirps</h1>

        <div class="space-y-4 mt-8">
            @forelse ($chirps as $chirp)
                <x-chirp :chirp="$chirp" />
            @empty
                <p class="text-center text-base-content/60 py-12">No chirps yet.</p>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $chirps->links() }}
        </div>
    </div>
</x-layout>