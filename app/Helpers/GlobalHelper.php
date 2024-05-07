<?php

use App\Models\Master\Parameter;
use App\Models\Transaction\Transaction;
use Carbon\Carbon;

if (!function_exists('valid_date')) {
    function valid_date($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        if ($d && $d->format($format) == $date) {
            return $d;
        }
        return null;
    }
}

if (!function_exists('indo_date')) {
    function indo_date($date)
    {
        if (valid_date($date, 'd/m/Y')) {
            $expl = explode('/', $date);
            return $expl[2] . '-' . $expl[1] . '-' . $expl[0];
        }
        return null;
    }
}

if (!function_exists('sqlindo_date')) {
    function sqlindo_date($date)
    {
        $d = valid_date($date, 'Y-m-d');
        if ($d) {
            return $d->format('d/m/Y');
        }
        return null;
    }
}

if (!function_exists('sqlindo_datetime_to_date')) {
    function sqlindo_datetime_to_date($datetime)
    {
        $d = valid_date($datetime, 'Y-m-d H:i:s');
        if ($d) {
            return $d->format('d/m/Y');
        }
        return null;
    }
}

if (!function_exists('sqlindo_datetime_to_time')) {
    function sqlindo_datetime_to_time($datetime)
    {
        $d = valid_date($datetime, 'Y-m-d H:i:s');
        if ($d) {
            return $d->format('H:i');
        }
        return null;
    }
}

if (!function_exists('sqlindo_datetime_to_datetime')) {
    function sqlindo_datetime_to_datetime($datetime)
    {
        $d = valid_date($datetime, 'Y-m-d H:i:s');
        if ($d) {
            return $d->format('d/m/Y H:i:s');
        }
        return null;
    }
}

if (!function_exists('tanggalDb')) {
    function tanggalDb($date)
    {
        $exp = explode('-', $date);
        $date = $exp[2] . '-' . $exp[1] . '-' . $exp[0];
        return $date;
    }
}
if (!function_exists('format_rupiah')) {
    function format_rupiah($angka)
    {
        // $hasil = number_format($angka, 0, ',', '.');
        $hasil = number_format($angka, 0, ',', '.');
        return $hasil;
    }
}
if (!function_exists('getRomawi')) {
    function getRomawi($bln)
    {
        switch ($bln) {
            case 1:
                return "I";
                break;
            case 2:
                return "II";
                break;
            case 3:
                return "III";
                break;
            case 4:
                return "IV";
                break;
            case 5:
                return "V";
                break;
            case 6:
                return "VI";
                break;
            case 7:
                return "VII";
                break;
            case 8:
                return "VIII";
                break;
            case 9:
                return "IX";
                break;
            case 10:
                return "X";
                break;
            case 11:
                return "XI";
                break;
            case 12:
                return "XII";
                break;
        }
    }
}

if (!function_exists('returnJson')) {
    function returnJson(bool $success, $data)
    {
        $content = [
            'success' => $success,
            'data' => $data,
        ];
        return $content;
    }
}
if (!function_exists('getNumber')) {
    function getNumber()
    {
        $month = date('m');
        $year = date('Y');

        $trx = Transaction::orderByDesc('id')->whereMonth('created_at', Carbon::now()->month)->first();

        if ($trx) {
            $lastTrx = $trx->invoice_no;
            $no = explode("/", $lastTrx);
            $no = $no[1];
        } else {
            $lastTrx =  "INV/0000/" . $month . "/NATHA/HF-PIK/" . $year;
            $no = "0000";
        }


        $lastNumber = "INV/" . sprintf('%04d', $no + 1) . "/" . getRomawi($month) . "/NATHA/HF-PIK/" . $year;

        return $lastNumber;
    }
}

if (!function_exists('pajak')) {
    function pajak($total)
    {
        $param = Parameter::where('code', 'pajak')->first();

        $pajak = $total * ($param->value / 100);

        return $pajak;
    }
}

if (!function_exists('reverse_tax')) {
    function reverse_tax($total)
    {
        $pajak = $total * (100 / 111);

        return $pajak;
    }
}
