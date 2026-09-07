<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::with('updater')->get();

        return view('admin.policies.index', compact('policies'));
    }

    public function update(Request $request)
    {
        $data = $request->input('policies', []);

        foreach ($data as $key => $val) {
            Policy::where('key', $key)->update([
                'value' => $val,
                'updated_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Kebijakan dan aturan bisnis villa berhasil diperbarui.');
    }
}
