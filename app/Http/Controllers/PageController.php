<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inout;

class PageController extends Controller
{
    public function index() {
        $total_income = 0;
        $total_outcome = 0;
        $today_date = date('Y-m-d');
        $inoutData = Inout::whereDate('date', $today_date)->get();
        $data = Inout::orderBy('id', 'desc')->get();
        foreach($inoutData as $sInoutData) {
            if($sInoutData->type == 'in') {
                $total_income += $sInoutData->amount;
            }
            if($sInoutData->type == 'out') {
                $total_outcome += $sInoutData->amount;
            }
        }
        $day_arr = [date('D')];
        $date_arr = [
            [
                'year' => date('Y'),
                'month' => date('m'),
                'day' => date('d')
            ]
        ];
        for($i=1;$i<=6;$i++) {
        $day_arr[] = date('D', strtotime("-$i day"));
        $new_date = [
            'year' => date('Y', strtotime("-$i day")),
            'month' => date('m', strtotime("-$i day")),
            'day' => date('d', strtotime("-$i day")),
        ];
        $date_arr[] = $new_date;
        }
        $income_amount = [];
        $outcome_amount = [];

        foreach($date_arr as $d){
            $income_amount[] = Inout::whereYear('date', $d['year'])->whereMonth('date', $d['month'])->whereDay('date', $d['day'])->where('type', 'in')->sum('amount');
            $outcome_amount[] = Inout::whereYear('date', $d['year'])->whereMonth('date', $d['month'])->whereDay('date', $d['day'])->where('type', 'out')->sum('amount');
        }
        return view('welcome', compact('data', 'total_income', 'total_outcome', 'day_arr', 'income_amount', 'outcome_amount'));
    }

    public function store(Request $request) {
        Inout::create([
            'about' => $request->about,
            'amount'=> $request->amount,
            'date' => $request->date,
            'type' => $request->type,
        ]);
        return redirect()->back()->with('success', 'Stored Successfully');
    }
}
