<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'imagem' => 'required|file|mimetypes:image/jpeg,image/png,image/jpg,image/webp|max:5120',
        ]);

        $path = $request->file('imagem')->store('uploads', 'public');

        return response()->json([
            'success' => true,
            'url'     => asset('storage/' . $path),
        ]);
    }
}
