<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'company_name' => Setting::getValue('company_name', 'Tecsisa'),
            'company_logo' => Setting::getValue('company_logo'),
            'company_footer' => Setting::getValue('company_footer', 'Sistema de Gestión de Infraestructura Hospitalaria'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_logo' => 'nullable|image|max:2048',
            'company_footer' => 'nullable|string|max:255',
        ]);

        Setting::setValue('company_name', $validated['company_name']);
        Setting::setValue('company_footer', $validated['company_footer'] ?? '');

        if ($request->hasFile('company_logo')) {
            // Delete old logo if exists
            $oldLogo = Setting::getValue('company_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = $request->file('company_logo')->store('company', 'public');
            Setting::setValue('company_logo', $path);
        }

        return redirect()->back()->with('success', 'Configuración de la empresa actualizada correctamente.');
    }
}
