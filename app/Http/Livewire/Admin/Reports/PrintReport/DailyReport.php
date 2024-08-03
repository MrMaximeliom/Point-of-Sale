<?php
namespace App\Http\Livewire\Admin\Reports\PrintReport;
use Livewire\Component;
use App\Models\Translation;

class DailyReport extends Component
{
        public $from_date, $total_orders, $delivered_orders, $total_payment, $total_expenses, $total_sales,$lang;

    /* render the content */
    public function render()
    {
        return view('livewire.admin.reports.print-report.daily-report')
        ->extends('layouts.print-layout')
        ->section('content');
    }
        public function report(){
             $this->total_orders = \App\Models\Order::whereDate('order_date',$this->from_date)->count();
             $this->delivered_orders = \App\Models\Order::whereDate('order_date',$this->from_date)->where('status',3)->count();
             $this->total_payment = \App\Models\Payment::whereDate('payment_date',$this->from_date)->sum('received_amount');
             $this->total_expenses = \App\Models\Expense::whereDate('expense_date',$this->from_date)->sum('expense_amount');
             $this->total_sales = \App\Models\Order::whereDate('order_date',$this->from_date)->where('is_fully_paid',1)->sum('total');
    }
    /* process before render */
    public function mount($from_date = null) {
        $this->from_date = $from_date ?? \Carbon\Carbon::today()->toDateString();
        if(session()->has('selected_language'))
        {
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            $this->lang = Translation::where('default',1)->first();
        }
        $this->report();
    }
}