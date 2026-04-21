<?php

namespace App\Http\Controllers;

use App\Models\Asuransi;
use App\Models\JenisCicilan;
use App\Models\JenisMotor;
use App\Models\Motor;
use App\Services\CreditSimulationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MotorCatalogController extends Controller
{
    public function index(Request $request, CreditSimulationService $simulationService)
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
            ->when($request->filled('tipe'), function (Builder $query) use ($request): void {
                $tipe = $request->string('tipe')->toString();

                $query->whereHas('jenisMotor', fn (Builder $jenisMotorQuery) => $jenisMotorQuery->where('tipe', $tipe));
            })
            ->when($request->filled('min_price'), fn (Builder $query) => $query->where('harga_jual', '>=', (float) $request->input('min_price')))
            ->when($request->filled('max_price'), fn (Builder $query) => $query->where('harga_jual', '<=', (float) $request->input('max_price')))
            ->when($request->filled('stock'), function (Builder $query) use ($request): void {
                match ($request->string('stock')->toString()) {
                    'ready' => $query->where('stok', '>', 0),
                    'low' => $query->where('stok', '<=', 5)->where('stok', '>', 0),
                    'empty' => $query->where('stok', '<=', 0),
                    default => null,
                };
            })
            ->when($request->string('sort')->isNotEmpty(), function (Builder $query) use ($request): void {
                match ($request->string('sort')->toString()) {
                    'harga_termahal' => $query->orderByDesc('harga_jual'),
                    'stok_terbanyak' => $query->orderByDesc('stok'),
                    'terlama' => $query->oldest(),
                    'terbaru' => $query->latest(),
                    default => $query->orderBy('harga_jual'),
                };
            }, fn (Builder $query) => $query->latest())
            ->paginate(12)
            ->withQueryString();

        $featuredMotor = $motors->first();
        $featuredTenor = JenisCicilan::query()->orderBy('lama_cicilan')->first();
        $featuredInsurance = Asuransi::query()->orderBy('nama_asuransi')->first();
        $featuredSimulation = $featuredMotor && $featuredTenor && $featuredInsurance
            ? $simulationService->calculate(
                $featuredMotor,
                $featuredTenor,
                $featuredInsurance,
                $simulationService->minimumDownPayment($featuredMotor)
            )
            : null;

        return view('motors.index', [
            'motors' => $motors,
            'motorTypes' => JenisMotor::query()
                ->select('tipe')
                ->distinct()
                ->orderBy('tipe')
                ->get(),
            'selectedMerk' => $request->string('merk')->toString(),
            'selectedType' => $request->string('tipe')->toString(),
            'selectedStock' => $request->string('stock')->toString(),
            'search' => $request->string('search')->toString(),
            'selectedMinPrice' => $request->string('min_price')->toString(),
            'selectedMaxPrice' => $request->string('max_price')->toString(),
            'selectedSort' => $request->string('sort')->toString(),
            'featuredSimulation' => $featuredSimulation,
        ]);
    }

    public function show(Motor $motor, CreditSimulationService $simulationService)
    {
        $motor->load('jenisMotor');
        $tenors = JenisCicilan::query()->orderBy('lama_cicilan')->get();
        $insurances = Asuransi::query()->orderBy('nama_asuransi')->get();
        $selectedTenor = $tenors->first();
        $selectedInsurance = $insurances->first();

        return view('motors.show', [
            'motor' => $motor,
            'relatedMotors' => Motor::query()
                ->with('jenisMotor')
                ->where('status_aktif', true)
                ->where('id_jenis_motor', $motor->id_jenis_motor)
                ->whereKeyNot($motor->getKey())
                ->take(4)
                ->get(),
            'tenors' => $tenors,
            'insurances' => $insurances,
            'defaultSimulation' => $selectedTenor && $selectedInsurance
                ? $simulationService->calculate(
                    $motor,
                    $selectedTenor,
                    $selectedInsurance,
                    $simulationService->minimumDownPayment($motor)
                )
                : null,
        ]);
    }

    public function simulation(Request $request, CreditSimulationService $simulationService)
    {
        $motors = Motor::query()->with('jenisMotor')->where('status_aktif', true)->orderBy('nama_motor')->get();
        $tenors = JenisCicilan::query()->orderBy('lama_cicilan')->get();
        $insurances = Asuransi::query()->orderBy('nama_asuransi')->get();

        $selectedMotor = $motors->firstWhere('id', (int) $request->input('motor')) ?? $motors->first();
        $selectedTenor = $tenors->firstWhere('id', (int) $request->input('tenor')) ?? $tenors->first();
        $selectedInsurance = $insurances->firstWhere('id', (int) $request->input('asuransi')) ?? $insurances->first();
        $downPayment = $selectedMotor
            ? (float) ($request->input('dp') ?: $simulationService->minimumDownPayment($selectedMotor))
            : 0;

        return view('motors.simulation', [
            'motors' => $motors,
            'tenors' => $tenors,
            'insurances' => $insurances,
            'selectedMotor' => $selectedMotor,
            'selectedTenor' => $selectedTenor,
            'selectedInsurance' => $selectedInsurance,
            'downPayment' => $downPayment,
            'simulation' => $selectedMotor && $selectedTenor && $selectedInsurance
                ? $simulationService->calculate($selectedMotor, $selectedTenor, $selectedInsurance, $downPayment)
                : null,
        ]);
    }
}
