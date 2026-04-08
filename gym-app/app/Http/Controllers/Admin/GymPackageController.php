<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymPackage;
use Illuminate\Http\Request;

class GymPackageController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q', ''));

        $packages = GymPackage::latest();

        if ($search !== '') {
            $packages->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('duration', 'like', "%{$search}%");
            });
        }

        return view('admin.packages.index', [
            'packages' => $packages->get(),
            'search' => $search,
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

        return redirect()->route('admin.packages.index')->with('success', 'Da tao goi tap.');
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

        return redirect()->route('admin.packages.index')->with('success', 'Da cap nhat goi tap.');
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
