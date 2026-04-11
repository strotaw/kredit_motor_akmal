<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    public function index(Request $request)
    {
        $motors = Motor::query()
            ->with('jenisMotor')
            ->where('status_aktif', true)
            ->when($request->filled('search'), function ($query) use ($request): void {
                $query->where('nama_motor', 'like', '%'.$request->string('search')->toString().'%');
            })
            ->latest()
            ->paginate(12);

        return response()->json($motors);
    }

    public function show(Motor $motor)
    {
        return response()->json($motor->load('jenisMotor'));
    }
}
