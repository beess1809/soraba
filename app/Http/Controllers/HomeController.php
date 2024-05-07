<?php

namespace App\Http\Controllers;

use App\Models\Transaction\Transaction;
use App\Models\Master\Item;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:employee');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $transaksi = Transaction::where('transactions.status_id', 2)->whereDate('date', Carbon::now()->toDateString());
        
        $query = Transaction::join('transaction_details as td', 'transactions.id','=','td.transaction_id')->where('transactions.status_id', 2)->whereDate('date', Carbon::now()->toDateString())->get();
        $hpp_total = 0;
        $pendapatan_total = 0;
        
        foreach($query as $q => $value) {
            $items = json_decode($value->item_id);
            $qty = json_decode($value->qty_item);
            $price = json_decode($value->item_price);

            foreach ($items as $idx => $item) {
                $dtl = Item::find($item);
                $hpp_subtotal = $dtl->hpp * $qty[$idx];

                
                
                // $sub_total = $qty[$idx] * $price[$idx];
                // $details[] = array(
                //     'id' => $dtl->id,
                //     'qty' => $qty[$idx],
                //     'subtotal' => $price[$idx],
                // );

                $hpp_total = $hpp_total + $hpp_subtotal;
                $pendapatan_total = $pendapatan_total + $price[$idx];
            }
        }
        $laba = $pendapatan_total - $hpp_total;
        $data['transaksi']  = $transaksi->count();
        $data['pendapatan'] = $transaksi->sum('grand_total');
        $data['items'] = Item::orderBy('qty','asc')->limit(10);
        return view('home', $data);
    }

    public function tes() {
        echo Hash::make('HealthyFit1809');
    }
}
