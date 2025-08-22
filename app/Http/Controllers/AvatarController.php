<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avatar;
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    public function edit(Request $request)
    {
        $avatar = Avatar::where('user_id', Auth::id())->latest()->first();

        // resources/views/avatars/editor.blade.php （複数形）に合わせる
        return view('avatars.editor', [
            'avatar' => $avatar,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'config' => ['required', 'json'],
            'preview' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:4096'],
        ]);

        $previewUrl = null;
        if ($request->hasFile('preview')) {
            $previewUrl = $request->file('preview')->store('avatars', 'public');
            $previewUrl = '/storage/' . $previewUrl;
        }

        Avatar::create([
            'user_id'     => Auth::id(),
            'config'      => json_decode($data['config'], true),
            'preview_url' => $previewUrl,
        ]);

        return redirect()->route('avatar.edit')->with('status', 'Saved');
    }
}
