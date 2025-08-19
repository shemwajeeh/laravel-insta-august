<div class="d-flex align-items-center gap-2">
    {{-- Like button (with heart icon) --}}
    <button wire:click="toggleLike" class="btn btn-sm border-0 bg-transparent p-0">
        @if ($isLiked)
            <i class="fa-solid fa-heart text-danger"></i>
        @else
            <i class="fa-regular fa-heart"></i>
        @endif
    </button>

    {{-- Likes display (now beside the heart) --}}
    @if ($likesDisplay)
        <div class="text-sm">
            @if (str_contains($likesDisplay, 'others'))
                {!! str_replace(
                    "others</a>",
                    "<span wire:click='showLikes' class='fw-semibold text-decoration-none' style='cursor:pointer;'>others</span>",
                    $likesDisplay
                ) !!}
            @else
                {!! $likesDisplay !!}
            @endif
        </div>
    @endif
</div>


{{-- Likes Modal --}}
@if ($showLikesModal)
    {{-- Overlay (click outside to close) --}}
    <div 
        class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center"
        style="background: rgba(0,0,0,0.5); z-index:1050;"
        wire:click="$set('showLikesModal', false)"
    >
        <div class="modal-dialog modal-dialog-centered" 
             wire:click.stop  {{-- Prevent closing when clicking inside modal --}}
        >
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">Liked by</h5>
                    <button type="button" class="btn-close" wire:click="$set('showLikesModal', false)"></button>
                </div>
                <div class="modal-body">
                    @forelse($likedUsers as $user)
                        <div class="d-flex align-items-center mb-2">
                            <i class="fa-regular fa-user-circle me-2 text-secondary fs-5"></i>
                            <span>{{ $user }}</span>
                        </div>
                    @empty
                        <p class="text-muted">No likes yet</p>
                    @endforelse
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                            wire:click="$set('showLikesModal', false)">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif
