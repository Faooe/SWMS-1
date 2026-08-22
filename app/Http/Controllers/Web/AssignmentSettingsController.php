<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Pengaturan review hasil kerja Assignment -- durasi revisi default
 * (assignment_revision_minutes) & mode Auto Approve
 * (assignment_auto_approve), keduanya kolom di tabel companies.
 * Sengaja halaman tersendiri yang ringan (bukan menumpuk di form
 * Assignment) karena ini setting company-wide, bukan per-assignment.
 */
class AssignmentSettingsController extends Controller
{
    public function edit()
    {
        return view('assignment.settings', [
            'company' => Auth::user()->company,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([

            'assignment_revision_minutes' => ['required', 'integer', 'min:5', 'max:43200'],

            'assignment_auto_approve' => ['nullable', 'boolean'],

        ]);

        $data['assignment_auto_approve'] = $request->boolean('assignment_auto_approve');

        Auth::user()->company->update($data);

        return back()->with('success', 'Pengaturan assignment berhasil disimpan.');
    }
}
