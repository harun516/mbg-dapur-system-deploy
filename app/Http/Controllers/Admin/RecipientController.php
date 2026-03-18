<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master_Penerima\Recipient;
use Illuminate\Http\Request;

class RecipientController extends Controller
{
    public function index()
    {
        $recipients = Recipient::orderBy('nama_lembaga', 'asc')->get();

        return view('admin.recipient.index', compact('recipients'));
    }

    public function create()
    {
        return view('admin.recipient.create');
    }

    public function edit($id)
    {
        $recipient = Recipient::findOrFail($id);

        return view('admin.recipient.edit', compact('recipient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lembaga' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp_pic' => 'required|string',
            'nama_pic' => 'required|string',
            'jumlah_porsi' => 'required|numeric|min:1',
        ]);

        // Ambil semua data, dan pastikan status_enable ada nilainya
        $data = $request->all();
        $data['status_enable'] = $request->status_enable ?? 1; // Default aktif jika tidak diisi

        Recipient::create($data);

        return redirect()->route('admin.recipient.index')->with('success', 'Data penerima berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_lembaga' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp_pic' => 'required|string',
            'nama_pic' => 'required|string',
            'jumlah_porsi' => 'required|numeric|min:1',
        ]);

        $recipient = Recipient::findOrFail($id);
        $recipient->update($request->all());

        return redirect()->route('admin.recipient.index')->with('success', 'Data berhasil diperbarui!');
    }
}
