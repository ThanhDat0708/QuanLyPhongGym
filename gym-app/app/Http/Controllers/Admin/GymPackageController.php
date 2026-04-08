<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymPackage;
use Illuminate\Http\Request;

class GymPackageController extends Controller
{
    public function index()
    {
        return view('admin.packages.index', [
            'packages' => GymPackage::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        GymPackage::create($validated);

        return back()->with('success', 'Da tao goi tap.');
    }

    public function update(Request $request, GymPackage $package)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'duration' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ]);

        $package->update($validated);

        return back()->with('success', 'Da cap nhat goi tap.');
    }

    public function edit(GymPackage $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function destroy(GymPackage $package)
    {
        $package->delete();

        return back()->with('success', 'Da xoa goi tap.');
    }
}
