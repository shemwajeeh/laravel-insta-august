<div>
    {{-- <div class="d-flex align-items-center gap-2"> --}}
    {{-- Like button --}}
    <button wire:click="toggleLike" class="btn btn-sm border-0 bg-transparent p-0">
        @if ($isLiked)
            <i class="fa-solid fa-heart text-danger"></i>
        @else
            <i class="fa-regular fa-heart"></i>
        @endif
    </button>

    {{-- <span class="fw-semibold small">{{ $likesCount }} likes</span> --}}


    @if ($likesCount > 0)
        <span class="small text-dark m-0">
            Liked by
            <strong>{{ $likedUsers[0] }}</strong>
            @if ($likesCount > 1)
                and <span style="cursor:pointer" wire:click="toggleModal" class="fw-bold">{{ $likesCount - 1 }}
                    others</span>
            @endif
        </span>
    @else
        <span class="small text-dark m-0 fw-bold">No Likes</span>
    @endif

    {{-- Modal --}}
    @if ($showModal)
        <!-- Bootstrap Modal -->
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);"
            wire:click.self="toggleModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h5 class="modal-title text-center w-100">Liked by</h5>
                        <button type="button" class="btn-close" wire:click="toggleModal"></button>
                    </div>

                    <!-- Modal Body -->
                    <div class="modal-body p-0">
                        <ul class="list-group list-group-flush">
                            @forelse ($likedUsers as $user)
                                <li class="list-group-item">{{ $user }}</li>
                            @empty
                                <li class="list-group-item text-center text-muted">No likes yet</li>
                            @endforelse
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    @endif



    {{-- Likes display --}}
    {{-- @if ($likesCount > 0)
            <div class="text-sm">
                @if ($likesCount === 1)
                    Liked by {{ $likedUsers[0] ?? '' }}
                @else
                    Liked by {{ $likedUsers[0] ?? '' }}
                    and
                    <a href="#" data-bs-toggle="modal" data-bs-target="#likesModal-{{ $post->id }}"
                        class="fw-semibold text-dark text-decoration-none">
                        {{ $likesCount - 1 }} others
                    </a>
                @endif
            </div>
        @endif
    </div> --}}

    {{-- Likes Modal (Bootstrap) --}}
    {{-- <div wire:ignore.self class="modal fade" id="likesModal-{{ $post->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-semibold">Liked by</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div>
                        @if ($likedUsers && count($likedUsers))
                            @foreach ($likedUsers as $user)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fa-regular fa-user-circle me-2 text-secondary fs-5"></i>
                                    <span>{{ $user }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No likes yet</p>
                        @endif
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                        data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

</div>
