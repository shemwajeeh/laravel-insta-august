@extends('layouts.app')

@section('title', 'Show Post')

@section('content')
    <style>
        .col-4 {
            overflow-y: scroll;
        }

        .card-body {
            position: absolute;
            top: 65px;
        }
    </style>

    <div class="row border shadow">
        <div class="col p-0 border-end">
            <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="w-100">
        </div>
        <div class="col-4 px-0 bg-white">
            <div class="card border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $post->user->id) }}">
                                @if ($post->user->avatar)
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}"
                                        class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>
                        <div class="col ps-0">
                            <a href="{{ route('profile.show', $post->user->id) }}"
                                class="text-decoration-none text-dark">{{ $post->user->name }}</a>
                        </div>
                        <div class="col-auto">
                            {{-- If you are the owner, you can edit or delete --}}
                            @if (Auth::user()->id === $post->user->id)
                                <div class="dropdown">
                                    <button class="btn btn-sm shadow-none" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a href="{{ route('post.edit', $post->id) }}" class="dropdown-item">
                                            <i class="fa-regular fa-pen-to-square"></i> Edit
                                        </a>
                                        <button class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#delete-post-{{ $post->id }}">
                                            <i class="fa-regular fa-trash-can"></i> Delete
                                        </button>
                                    </div>
                                    {{-- Include modal here --}}
                                    @include('users.posts.contents.modals.delete')


                                </div>
                            @else
                                {{-- show follow button for now (if not the owner) --}}
                                @if ($post->user->isFollowed())
                                    <form action="{{ route('follow.destroy', $post->user->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="border-0 bg-transparent p-0 text-secondary">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('follow.store', $user->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body w-100">
                    {{-- heart button + no. of likes + categories --}}
                    <div class="row align-items-center">
                        <div class="col-auto">
                            @if ($post->isLiked())
                                <form action="{{ route('like.destroy', $post->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm shadow-none p-0">
                                        <i class="fa-solid fa-heart text-danger"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('like.store', $post->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-sm shadow-none p-0">
                                        <i class="fa-regular fa-heart"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                        <div class="col-auto px-0">
                            <span>{{ $post->likes->count() }}</span>
                        </div>
                        <div class="col text-end">
                            @foreach ($post->categoryPost as $category_post)
                                <div class="badge bg-secondary bg-opacity-50">
                                    {{ $category_post->category->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- owner + description --}}
                    <a href="{{ route('profile.show', $post->user->id) }}"
                        class="text-decoration-none text-dark fw-bold">{{ $post->user->name }}</a>
                    &nbsp;
                    <p class="d-inline fw-light">{{ $post->description }}</p>
                    @php
                        $targets = collect(config('translate.targets')); // code => label
                        $supported = collect(config('translate.deepl_supported'))
                            ->map(fn($c) => strtolower($c))
                            ->flip(); // key 存在チェック用

                        // ラベルでアルファベット順（大文字小文字を無視）
                        $sorted = $targets->sort(function ($a, $b) {
                            return strcasecmp($a, $b);
                        });
                    @endphp

                    <label for="translate-lang" class="small mb-0 text-muted">Translate to</label>
                    <select id="translate-lang" class="form-select form-select-sm d-inline-block ms-2" style="width:auto;">
                        @foreach ($sorted as $code => $label)
                            @php $ok = $supported->has(strtolower($code)); @endphp
                            <option value="{{ $code }}" {{ $ok ? '' : 'disabled' }}>
                                {{ $label }}{{ $ok ? '' : ' (not supported by DeepL)' }}
                            </option>
                        @endforeach
                    </select>


                    <button id="btn-translate" class="btn btn-sm btn-outline-primary ms-2">
                        🌐 Show Translation
                    </button>

                    <div id="caption-translated" class="mt-2 small text-body" style="display:none;"></div>


                    {{-- === AI Translate (EN-only): start === --}}
                    {{-- <p class="mb-2" id="caption-original">{{ $post->description ?? $post->caption }}</p>

                    <div class="mt-2">
                        <div class="d-flex gap-2 align-items-center">
                            <label for="translate-lang" class="small mb-0 text-muted">Translate to</label>
                            <select id="translate-lang" class="form-select form-select-sm" style="width:auto;">
                                <option value="en">English</option>
                                <option value="ja">Japanese</option>
                                <option value="ko">Korean</option>
                                <option value="zh">Chinese</option>
                            </select>

                            <button id="btn-translate" class="btn btn-sm btn-outline-primary">
                                🌐 Show Translation
                            </button>
                        </div>

                        <div id="caption-translated" class="mt-2 small text-body" style="display:none;"></div>
                    </div>

                    <script>
                        (function() {
                            const btn = document.getElementById('btn-translate');
                            const langSel = document.getElementById('translate-lang');
                            const box = document.getElementById('caption-translated');
                            const base = @json(route('post.translate', ['post' => $post->id], false));

                            btn.addEventListener('click', async function() {
                                const url = base + '?lang=' + encodeURIComponent(langSel.value);

                                btn.disabled = true;
                                const prev = btn.textContent;
                                btn.textContent = 'Translating...';

                                try {
                                    const res = await fetch(url, {
                                        headers: {
                                            'X-Requested-With': 'XMLHttpRequest'
                                        }
                                    });
                                    if (!res.ok) throw new Error('HTTP ' + res.status);
                                    const data = await res.json();
                                    box.style.display = 'block';
                                    box.textContent = data.text || '(No translation)';
                                } catch (e) {
                                    console.error(e);
                                    alert('Translation failed. Please try again later.');
                                } finally {
                                    btn.disabled = false;
                                    btn.textContent = prev;
                                }
                            });
                        })();
                    </script> --}}
                    {{-- === AI Translate (EN-only): end === --}}



                    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

                    {{-- comment --}}


                    <form action="{{ route('comment.store', $post->id) }}" method="post">
                        @csrf

                        <div class="input-group">
                            <textarea name="comment_body{{ $post->id }}" rows="1" class="form-control form-control-sm"
                                placeholder="Add a comment...">{{ old('comment_body' . $post->id) }}</textarea>
                            <button type="submit" class="btn btn-outline-secondary btn-sm">Post</button>
                        </div>
                        {{-- Error --}}
                        @error('comment_body' . $post->id)
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </form>

                    {{-- Show all comments here --}}
                    @if ($post->comments->isNotEmpty())
                        <ul class="list-group mt-2">
                            @foreach ($post->comments as $comment)
                                <li class="list-group-item border-0 p-0 mb-2">
                                    <a href="{{ route('profile.show', $comment->user->id) }}"
                                        class="text-decoration-none text-dark fw-bold">{{ $comment->user->name }}</a>
                                    &nbsp;
                                    <p class="d-inline fw-light">{{ $comment->body }}</p>

                                    <form action="{{ route('comment.destroy', $comment->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <span
                                            class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>

                                        {{-- if the logged in user is the owner, show delete button --}}
                                        @if (Auth::user()->id === $comment->user->id)
                                            &middot;
                                            <button type="submit"
                                                class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
                                        @endif
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            const btn = document.getElementById('btn-translate');
            const langSel = document.getElementById('translate-lang');
            const box = document.getElementById('caption-translated');
            const base = @json(route('post.translate', ['post' => $post->id], false)); // 相対URL

            if (!btn || !langSel || !box) return;

            btn.addEventListener('click', async function() {
                const url = base + '?lang=' + encodeURIComponent(langSel.value);

                btn.disabled = true;
                const prev = btn.textContent;
                btn.textContent = 'Translating...';

                try {
                    const res = await fetch(url, {
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const raw = await res.text();
                    let data = {};
                    try {
                        data = JSON.parse(raw);
                    } catch (_) {}

                    if (!res.ok || typeof data.text === 'undefined') {
                        const msg = (data && data.message) ? data.message : raw.slice(0, 200);
                        alert('Translation failed: ' + msg + ' (HTTP ' + res.status + ')');
                        return;
                    }

                    box.style.display = 'block';
                    box.textContent = data.text || '(No translation)';
                } catch (e) {
                    alert('Translation failed: Network error');
                    console.error(e);
                } finally {
                    btn.disabled = false;
                    btn.textContent = prev;
                }
            });
        })();
    </script>


@endsection
