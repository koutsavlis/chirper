<x-layout>
    <x-slot:title>
        Create a new chirp
    </x-slot:title>

    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold mt-8">Create a new chirp</h1>

        <!-- Chirp Form -->
        <div class="card bg-base-100 shadow mt-8">
            <div class="card-body">
                <form method="POST" action="/chirps">
                    @csrf
                    <div class="form-control w-full">

                        <div class="form-control w-full">
                            <label class="label" for="title">
                                <span class="label-text">Title</span>
                            </label>

                            <input id="title" name="title" type="text" value="{{ old('title', $chirp->title ?? '') }}"
                                class="input input-bordered w-full @error('title') input-error @enderror">

                            @error('title')
                            <label class="label">
                                <span class="label-text-alt text-error">
                                    {{ $message }}
                                </span>
                            </label>
                            @enderror
                        </div>

                        <div class="form-control w-full mt-4">
                            <label class="label" for="message">
                                <span class="label-text">Description</span>
                            </label>

                            <textarea id="message" name="message" rows="5"
                                class="textarea textarea-bordered w-full resize-none @error('message') textarea-error @enderror">{{ old('message', $chirp->message ?? '') }}</textarea>

                            @error('message')
                            <label class="label">
                                <span class="label-text-alt text-error">
                                    {{ $message }}
                                </span>
                            </label>
                            @enderror
                        </div>

                        <div class="card-actions justify-between mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-ghost btn-sm">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-sm">
                                Create Chirp
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>