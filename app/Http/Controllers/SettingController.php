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
            'company_name'    => Setting::getValue('company_name', 'Tecsisa'),
            'company_logo'    => Setting::getValue('company_logo'),
            'company_footer'  => Setting::getValue('company_footer', 'Sistema de Gestión de Infraestructura Hospitalaria'),
            'engineer_name'   => Setting::getValue('engineer_name', ''),
            'engineer_cargo'  => Setting::getValue('engineer_cargo', 'Ingeniero Responsable de Obra'),
            'project_name'    => Setting::getValue('project_name', 'Hospital Anita Moreno — Sistemas Especiales'),
            'contract_number' => Setting::getValue('contract_number', ''),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name'    => 'required|string|max:255',
            'company_logo'    => 'nullable|image|max:2048',
            'company_footer'  => 'nullable|string|max:500',
            'engineer_name'   => 'nullable|string|max:255',
            'engineer_cargo'  => 'nullable|string|max:255',
            'project_name'    => 'nullable|string|max:255',
            'contract_number' => 'nullable|string|max:100',
        ]);

        Setting::setValue('company_name',    $validated['company_name']);
        Setting::setValue('company_footer',   $validated['company_footer'] ?? '');
        Setting::setValue('engineer_name',    $validated['engineer_name']   ?? '');
        Setting::setValue('engineer_cargo',   $validated['engineer_cargo']  ?? '');
        Setting::setValue('project_name',     $validated['project_name']    ?? '');
        Setting::setValue('contract_number',  $validated['contract_number'] ?? '');

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
