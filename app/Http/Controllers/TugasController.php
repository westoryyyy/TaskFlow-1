<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Models\Kategori;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();

        $totalTugas = Tugas::where('user_id', $userId)->count();

        $selesai = Tugas::where('user_id', $userId)
            ->where('is_selesai', true)
            ->count();

        $mendekatiDeadline = Tugas::where('user_id', $userId)
            ->where('is_selesai', false)
            ->whereNotNull('deadline')
            ->where('deadline', '<=', now()->addDays(3))
            ->count();

        $tugasList = Tugas::with('kategori')
            ->where('user_id', $userId)
            ->where('is_selesai', false)
            ->orderBy('deadline', 'asc')
            ->get();

        return view('dashboard', compact(
            'totalTugas',
            'selesai',
            'mendekatiDeadline',
            'tugasList'
        ));
    }

    public function create()
    {
        $kategoris = Kategori::all();

        return view('tugas.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_tugas' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategoris,id',
        ]);

        Tugas::create([
            'user_id' => auth()->id(),
            'judul' => $request->judul_tugas,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->tanggal_deadline,
            'kategori_id' => $request->id_kategori,
            'waktu_reminder' => $request->waktu_reminder,
            'status_aktif' => $request->status_aktif ?? 'aktif',
            'is_selesai' => false,
        ]);

        return redirect('/dashboard')
            ->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    public function show($id)
    {
        $tugas = Tugas::with('kategori')->findOrFail($id);

        return view('tugas.show', compact('tugas'));
    }

    public function edit($id)
    {
        $tugas = Tugas::findOrFail($id);

        $kategoris = Kategori::all();

        return view('tugas.edit', compact('tugas', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        $tugas->update([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'deadline' => $request->deadline,
            'kategori_id' => $request->kategori,
        ]);

        return redirect("/tugas/$id")
            ->with('success', 'Perubahan berhasil disimpan!');
    }

    public function selesai($id)
    {
        $tugas = Tugas::findOrFail($id);

        $tugas->update([
            'is_selesai' => true
        ]);

        return redirect('/dashboard')
            ->with('success', 'Mantap! Tugas berhasil diselesaikan.');
    }

    public function destroy($id)
    {
        $tugas = Tugas::findOrFail($id);

        $tugas->delete();

        return redirect('/dashboard')
            ->with('success', 'Tugas berhasil dihapus!');
    }
}