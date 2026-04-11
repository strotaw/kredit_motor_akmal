<?php

namespace App\Http\Controllers;

use App\Models\Motor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MotorCatalogController extends Controller
{
    public function index(Request $request)
    {
        $motors = Motor::query()
            ->with('jenisMotor')
            ->where('status_aktif', true)
            ->when($request->string('search')->isNotEmpty(), function (Builder $query) use ($request): void {
                $search = $request->string('search')->toString();

                $query->where('nama_motor', 'like', "%{$search}%");
            })
            ->when($request->filled('merk'), function (Builder $query) use ($request): void {
                $merk = $request->string('merk')->toString();

                $query->whereHas('jenisMotor', fn (Builder $jenisMotorQuery) => $jenisMotorQuery->where('merk', $merk));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('motors.index', [
            'motors' => $motors,
            'selectedMerk' => $request->string('merk')->toString(),
            'search' => $request->string('search')->toString(),
        ]);
    }

    public function show(Motor $motor)
    {
        $motor->load('jenisMotor');

        return view('motors.show', [
            'motor' => $motor,
            'relatedMotors' => Motor::query()
                ->where('status_aktif', true)
                ->where('id_jenis_motor', $motor->id_jenis_motor)
                ->whereKeyNot($motor->getKey())
                ->take(4)
                ->get(),
        ]);
    }
}
