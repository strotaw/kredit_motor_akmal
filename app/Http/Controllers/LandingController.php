<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use App\Models\PengajuanKredit;
use App\Models\User;

class LandingController extends Controller
{
    public function index()
    {
        return view('home', [
            'featuredMotors' => Motor::query()->with('jenisMotor')->where('status_aktif', true)->latest()->take(6)->get(),
            'stats' => [
                'motor' => Motor::query()->count(),
                'pengajuan' => PengajuanKredit::query()->count(),
                'user' => User::query()->where('role', 'user')->count(),
            ],
        ]);
    }
}
