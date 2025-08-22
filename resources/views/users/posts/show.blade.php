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

        /* === AI Translate styles === */
        .ai-card {
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, .6);
            border-radius: 16px;
            border: 1px solid rgba(255, 255, 255, .5);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
            width: 100%;
            max-width: 100%;
        }

        .ai-glow {
            position: relative;
        }

        .ai-glow::before {
            content: "";
            position: absolute;
            inset: -2px;
            border-radius: 18px;
            background: linear-gradient(90deg, #7c3aed, #06b6d4, #22c55e, #f59e0b, #ef4444);
            background-size: 300% 100%;
            filter: blur(8px);
            opacity: .6;
            z-index: -1;
            animation: glow 2s linear infinite;
        }

        @keyframes glow {
            0% { background-position: 0% 50% }
            100% { background-position: 300% 50% }
        }

        .shimmer {
            animation: shimmer 1.2s ease-in-out infinite;
            background: linear-gradient(90deg, rgba(0, 0, 0, .06) 25%, rgba(0, 0, 0, .12) 37%, rgba(0, 0, 0, .06) 63%);
            background-size: 400% 100%;
        }

        @keyframes shimmer {
            0% { background-position: 100% 0 }
            100% { background-position: -100% 0 }
        }

        /* セレクトは幅100%でハミ出し防止 */
        #translate-lang { max-width: 100%; width: 100%; }

        /* 訳文は折り返し＆改行維持でハミ出し防止 */
        #caption-translated {
            white-space: pre-wrap;
            word-break: break-word;
            overflow-wrap: anywhere;
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
                                    <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>
                        <div class="col ps-0">
                            <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark">
                                {{ $post->user->name }}
                            </a>
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
                                        <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#delete-post-{{ $post->id }}">
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
                                        <button type="submit" class="border-0 bg-transparent p-0 text-secondary">Following</button>
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
                    <a href="{{ route('profile.show', $post->user->id) }}" class="text-decoration-none text-dark fw-bold">
                        {{ $post->user->name }}
                    </a>
                    &nbsp;
                    <p class="d-inline fw-light">{{ $post->description }}</p>

                    {{-- === AI Translate UI (single set, セレクト変更で自動翻訳) === --}}
                    @php
                        // Labels in A→Z order (fallback if config/translate.php doesn't exist)
                        $targets = collect(
                            config('translate.targets', [
                                'ar'=>'Arabic','bg'=>'Bulgarian','cs'=>'Czech','da'=>'Danish','de'=>'German','el'=>'Greek',
                                'en-GB'=>'English (UK)','en-US'=>'English (US)',
                                'es'=>'Spanish','es-419'=>'Spanish (LatAm)','et'=>'Estonian','fi'=>'Finnish','fr'=>'French',
                                'he'=>'Hebrew','hu'=>'Hungarian','id'=>'Indonesian','it'=>'Italian','ja'=>'Japanese','ko'=>'Korean',
                                'lt'=>'Lithuanian','lv'=>'Latvian','nb'=>'Norwegian (Bokmål)','nl'=>'Dutch','pl'=>'Polish',
                                'pt-BR'=>'Portuguese (Brazil)','pt-PT'=>'Portuguese (EU)','ro'=>'Romanian','ru'=>'Russian',
                                'sk'=>'Slovak','sl'=>'Slovenian','sv'=>'Swedish','th'=>'Thai','tr'=>'Turkish','uk'=>'Ukrainian',
                                'vi'=>'Vietnamese','zh-HANS'=>'Chinese (Simplified)','zh-HANT'=>'Chinese (Traditional)',
                                // DeepL unsupported (UI only; disabled)
                                'ceb'=>'Cebuano','fil'=>'Filipino (Tagalog)',
                            ])
                        );
                        $supported = collect(
                            config('translate.deepl_supported', [
                                'ar','bg','cs','da','de','el','en','en-gb','en-us','es','es-419','et','fi','fr','he','hu',
                                'id','it','ja','ko','lt','lv','nb','nl','pl','pt','pt-br','pt-pt','ro','ru','sk','sl','sv',
                                'th','tr','uk','vi','zh','zh-hans','zh-hant',
                            ])
                        )->map(fn($c)=>strtolower($c))->flip();
                        $sorted = $targets->sort(fn($a,$b)=>strcasecmp($a,$b)); // sort by label A→Z
                    @endphp

                    <div class="mt-3">
                        <label for="translate-lang" class="small mb-1 text-muted">Translate to</label>
                        <select id="translate-lang" class="form-select form-select-sm w-100">
                            @foreach($sorted as $code => $label)
                                @php $ok = $supported->has(strtolower($code)); @endphp
                                <option value="{{ $code }}" {{ $ok ? '' : 'disabled' }}>
                                    {{ $label }}{{ $ok ? '' : ' (not supported by DeepL)' }}
                                </option>
                            @endforeach
                        </select>

                        <!-- Glass card: translation result -->
                        <div id="ai-translate-box" class="ai-card p-3 mt-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="small text-muted">
                                    <span id="ai-meta">Detected: — / Target: —</span>
                                    <span id="ai-cache" class="badge bg-light text-secondary ms-2" style="display:none;">from cache</span>
                                </div>
                                <div class="btn-group btn-group-sm">
                                    <button id="ai-copy" class="btn btn-outline-secondary">Copy</button>
                                    <button id="ai-speak" class="btn btn-outline-secondary">🔊</button>
                                </div>
                            </div>
                            <div id="caption-translated" class="mt-2 small text-body" aria-live="polite"></div>
                        </div>
                    </div>
                    {{-- === /AI Translate UI === --}}

                    <p class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($post->created_at)) }}</p>

                    {{-- comment --}}
                    <form action="{{ route('comment.store', $post->id) }}" method="post">
                        @csrf
                        <div class="input-group">
                            <textarea name="comment_body{{ $post->id }}" rows="1" class="form-control form-control-sm" placeholder="Add a comment...">{{ old('comment_body' . $post->id) }}</textarea>
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
                                    <a href="{{ route('profile.show', $comment->user->id) }}" class="text-decoration-none text-dark fw-bold">
                                        {{ $comment->user->name }}
                                    </a>
                                    &nbsp;
                                    <p class="d-inline fw-light">{{ $comment->body }}</p>

                                    <form action="{{ route('comment.destroy', $comment->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <span class="text-uppercase text-muted xsmall">{{ date('M d, Y', strtotime($comment->created_at)) }}</span>

                                        {{-- if the logged in user is the owner, show delete button --}}
                                        @if (Auth::user()->id === $comment->user->id)
                                            &middot;
                                            <button type="submit" class="border-0 bg-transparent text-danger p-0 xsmall">Delete</button>
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
    (function () {
      const sel   = document.getElementById('translate-lang');
      const box   = document.getElementById('ai-translate-box');
      const out   = document.getElementById('caption-translated');
      const meta  = document.getElementById('ai-meta');
      const cache = document.getElementById('ai-cache');
      const base  = @json(route('post.translate', ['post'=>$post->id], false)); // relative URL (important)

      if (!sel || !box || !out) return;

      // Restore last selected language (if available)
      const last = localStorage.getItem('translate:lang');
      if (last) {
        const opt = [...sel.options].find(o => o.value.toLowerCase() === last.toLowerCase() && !o.disabled);
        if (opt) sel.value = opt.value;
      }

      // Helper: do translate
      async function doTranslate(lang){
        // Save selection
        localStorage.setItem('translate:lang', lang);

        // Effects ON
        box.classList.add('ai-glow');
        out.classList.add('shimmer');
        out.textContent = ' ';

        try {
          const url = base + '?lang=' + encodeURIComponent(lang);
          const res = await fetch(url, {
            credentials: 'same-origin',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });

          const raw = await res.text();
          let data = {};
          try { data = JSON.parse(raw); } catch (_) {}

          if (!res.ok || typeof data.text === 'undefined') {
            const msg = (data && data.message) ? data.message : ('HTTP ' + res.status);
            alert('Translation failed: ' + msg);
            return;
          }

          // Meta + result
          const label = sel.options[sel.selectedIndex].text;
          meta.textContent = 'Detected: auto / Target: ' + label;
          cache.style.display = data.cached ? 'inline-block' : 'none';

          out.classList.remove('shimmer');
          // Typewriter
          out.textContent = '';
          let i = 0, text = data.text || '(No translation)';
          (function step(){
            out.textContent += text.charAt(i++);
            if (i <= text.length) requestAnimationFrame(step);
          })();

        } catch (e) {
          alert('Translation failed: Network error');
          console.error(e);
        } finally {
          box.classList.remove('ai-glow');
        }
      }

      // Debounce to avoid spamming API on fast scrolling
      let timer = null;
      sel.addEventListener('change', function(){
        clearTimeout(timer);
        const lang = sel.value;
        timer = setTimeout(() => doTranslate(lang), 250);
      });

      // Auto-translate once on load (using current selection)
      // ※ 直前セッションの言語が復元された場合はその言語で即時翻訳
      doTranslate(sel.value);

      // Copy
      document.getElementById('ai-copy').onclick = () => {
        navigator.clipboard.writeText(out.textContent || '');
      };

      // Speak (Web Speech API)
      document.getElementById('ai-speak').onclick = () => {
        const u = new SpeechSynthesisUtterance(out.textContent || '');
        window.speechSynthesis.cancel();
        window.speechSynthesis.speak(u);
      };
    })();
    </script>
@endsection
