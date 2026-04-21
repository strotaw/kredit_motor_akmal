<?php

namespace App\Services;

use App\Models\Asuransi;
use App\Models\JenisCicilan;
use App\Models\Motor;

class CreditSimulationService
{
    public function minimumDownPayment(Motor $motor): float
    {
        return round(((float) $motor->harga_jual) * 0.2, 2);
    }

    public function calculate(Motor $motor, JenisCicilan $jenisCicilan, Asuransi $asuransi, float $downPayment): array
    {
        $minimumDownPayment = $this->minimumDownPayment($motor);
        $downPayment = round(max($downPayment, $minimumDownPayment), 2);
        $principal = max(((float) $motor->harga_jual) - $downPayment, 0);
        $tenor = max((int) $jenisCicilan->lama_cicilan, 1);
        $marginRate = ((float) $jenisCicilan->margin_kredit) / 100;
        $insuranceRate = ((float) $asuransi->margin_asuransi) / 100;

        $marginNominal = round($principal * $marginRate, 2);
        $hargaKredit = round($principal + $marginNominal, 2);
        $biayaAsuransiPerbulan = round((((float) $motor->harga_jual) * $insuranceRate) / $tenor, 2);
        $cicilanPokokPerbulan = round($hargaKredit / $tenor, 2);
        $cicilanPerbulan = round($cicilanPokokPerbulan + $biayaAsuransiPerbulan, 2);
        $totalKewajiban = round($downPayment + ($cicilanPerbulan * $tenor), 2);

        return [
            'harga_cash' => (float) $motor->harga_jual,
            'dp' => $downPayment,
            'minimum_dp' => $minimumDownPayment,
            'tenor_bulan' => $tenor,
            'margin_kredit_persen' => (float) $jenisCicilan->margin_kredit,
            'margin_kredit_nominal' => $marginNominal,
            'harga_kredit' => $hargaKredit,
            'biaya_asuransi_perbulan' => $biayaAsuransiPerbulan,
            'cicilan_pokok_perbulan' => $cicilanPokokPerbulan,
            'cicilan_perbulan' => $cicilanPerbulan,
            'total_kewajiban' => $totalKewajiban,
        ];
    }
}
