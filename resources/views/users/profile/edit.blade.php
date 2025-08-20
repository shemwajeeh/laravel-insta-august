@extends('layouts.app')

@section('title', $user->name)

@section('content')
    <style>
        body {
            background-color: #FAF9EE;
        }

        .edit-profile-card {
            background: #fff;
            border-radius: 1rem;
            padding: 2rem 3rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .avatar-lg {
            width: 120px;
            height: 120px;
            border: 4px solid #A2AF9B;
            object-fit: cover;
        }

        .btn-warning {
            background-color: #A2AF9B !important;
            border: none !important;
            color: #fff !important;
        }

        .btn-warning:hover {
            background-color: #7D8A6F !important;
        }

        label {
            color: #2C2C2C;
        }

        input, textarea {
            border-radius: 0.5rem !important;
            border: 1px solid #ccc;
        }

        input:focus, textarea:focus {
            border-color: #A2AF9B !important;
            box-shadow: 0 0 0 0.2rem rgba(162, 175, 155, 0.25) !important;
        }
    </style>

    <div class="row justify-content-center">
        <div class="col-8">
            <form action="{{ route('profile.update') }}" method="post" class="edit-profile-card" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <h2 class="h3 mb-3 fw-light text-muted">Update Profile</h2>

                <div class="row mb-3">
                    <div class="col-4">
                        @if ($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}"
                                class="img-thumbnail rounded-circle d-block mx-auto avatar-lg">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
                        @endif
                    </div>
                    <div class="col-auto align-self-end">
                        <input type="file" name="avatar" id="avatar" class="form-control form-control-sm mt-1"
                            aria-describedby="avatar-info">
                        <div id="avatar-info" class="form-text">
                            Acceptable formats: jpeg, jpg, png, gif only <br>
                            Max file size is 1048kb
                        </div>
                        {{-- Error --}}
                        @error('avatar')
                            <p class="text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', $user->name) }}" autofocus>
                    @error('name')
                        <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">E-Mail Address</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', $user->email) }}" autofocus>
                    @error('email')
                        <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="introduction" class="form-label fw-bold">Introduction</label>
                    <textarea name="introduction" id="introduction" rows="5" class="form-control" placeholder="Describe yourself">{{ old('introduction', $user->introduction) }}</textarea>
                    @error('introduction')
                        <p class="text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning px-5">Save</button>
            </form>
        </div>
    </div>
@endsection
