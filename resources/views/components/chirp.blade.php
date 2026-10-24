@props(['chirp'])

<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex space-x-3">
            @if ($chirp->user)
                <a href="{{ route('users.show', $chirp->user) }}" class="avatar">
                    <div class="size-10 rounded-full">
                        <img src="{{ $chirp->user->avatar_url }}"
                            alt="{{ $chirp->user->name }}'s avatar" class="rounded-full" />
                    </div>
                </a>
            @else
                <div class="avatar placeholder">
                    <div class="size-10 rounded-full">
                        <img src="https://avatars.laravel.cloud/f61123d5-0b27-434c-a4ae-c653c7fc9ed6?vibe=stealth"
                            alt="Anonymous User" class="rounded-full" />
                    </div>
                </div>
            @endif

            <div class="min-w-0 flex-1">
                <div class="flex justify-between w-full">
                    <div class="flex items-center gap-1">
                        @if ($chirp->user)
                            <a href="{{ route('users.show', $chirp->user) }}" class="text-sm font-semibold">
                                {{ $chirp->user->name }}
                            </a>
                        @else
                            <span class="text-sm font-semibold">Anonymous</span>
                        @endif
                        <span class="text-base-content/60">·</span>
                        <span class="text-sm text-base-content/60">{{ $chirp->created_at->diffForHumans() }}</span>
                        @if ($chirp->updated_at->gt($chirp->created_at->addSeconds(5)))
                            <span class="text-base-content/60">·</span>
                            <span class="text-sm text-base-content/60 italic">edited</span>
                        @endif
                    </div>

                    @can('update', $chirp)
                        <div class="flex gap-1">
                            <a href="{{ route('chirps.edit', $chirp) }}" class="btn btn-ghost btn-xs">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('chirps.destroy', $chirp) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this chirp?')"
                                    class="btn btn-ghost btn-xs text-error">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endcan
                </div>

                <a href="{{ route('chirps.show', $chirp) }}">
                    <p class="mt-1">{{ $chirp->title }}</p>
                </a>
            </div>
        </div>
    </div>
</div>