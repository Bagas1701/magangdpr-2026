<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use App\Models\Attachment;
use App\Models\Konstituen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AspirasiSubmissionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'max:20'],
            'kontak' => ['nullable', 'string', 'max:50'],
            'kategori_aspirasi_id' => ['nullable', 'exists:kategori_aspirasis,id'],
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'tanggal_kejadian' => ['nullable', 'date'],
            'lokasi_kejadian' => ['nullable', 'string', 'max:255'],
            'privacy_consent' => ['accepted'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:5120'],
        ]);

        $aspirasi = DB::transaction(function () use ($request, $validated): Aspirasi {
            $konstituen = Konstituen::updateOrCreate(
                ['nik' => $validated['nik']],
                [
                    'nama' => $validated['nama'],
                    'kontak' => $validated['kontak'] ?? null,
                ]
            );

            $aspirasi = Aspirasi::create([
                'konstituen_id' => $konstituen->id,
                'kategori_aspirasi_id' => $validated['kategori_aspirasi_id'] ?? null,
                'judul' => $validated['judul'],
                'deskripsi' => $validated['deskripsi'],
                'tanggal_kejadian' => $validated['tanggal_kejadian'] ?? null,
                'lokasi_kejadian' => $validated['lokasi_kejadian'] ?? null,
                'prioritas' => 'normal',
                'created_by' => null,
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('attachments/aspirasi-awal', 'public');

                    Attachment::create([
                        'aspirasi_id' => $aspirasi->id,
                        'status_id' => $aspirasi->status_id,
                        'uploaded_by' => null,
                        'original_name' => $file->getClientOriginalName(),
                        'file_path' => $path,
                        'file_type' => $file->getClientMimeType(),
                        'file_size' => $file->getSize(),
                        'stage' => Attachment::STAGE_AWAL,
                        'attachment_category' => Attachment::CATEGORY_BUKTI_AWAL,
                        'is_locked' => false,
                        'description' => 'Lampiran awal dari pengajuan publik.',
                    ]);
                }
            }

            return $aspirasi;
        });

        return redirect()->route('frontend.aspirasi.success', $aspirasi);
    }

    public function success(Aspirasi $aspirasi): View
    {
        $aspirasi->load(['status', 'kategoriAspirasi', 'attachments']);

        return view('frontend.aspirasi.success', compact('aspirasi'));
    }
}