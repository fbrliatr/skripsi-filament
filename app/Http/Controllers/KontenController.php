<?php

namespace App\Http\Controllers;

use Parsedown;
use App\Models\Konten;
use Illuminate\Http\Request;

class KontenController extends Controller

{
    public function index()
    {
        // Ambil data dari model Konten
        $contents = Konten::orderBy('tgl_rilis', 'desc') // Mengurutkan berdasarkan tanggal rilis terbaru
                    ->limit(5) // Membatasi hasil hingga 6
                    ->get();
        // dd($contents); // Ini akan menampilkan isi dari $contents di browser

        // Kirim data ke view
        return view('landing', compact('contents'));
    }
    public function show($id)
    {
        $content = Konten::findOrFail($id);
        $parsedown = new Parsedown();
        $content->description_html = $parsedown->text($content->description);

        return view('konten.konten', compact('content'));
    }
}

