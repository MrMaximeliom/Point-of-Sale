<?php
namespace App\Http\Livewire\Admin\Reports;
use App\Models\Translation;
use PDF;
use Livewire\Component;
class DailyReport extends Component
{
    public $from_date, $total_orders, $delivered_orders, $total_payment, $total_expense, $total_sales,$lang;
    /* render the page */
    public function render()
    {
        return view('livewire.admin.reports.daily-report');
    }
    /* processed before render */
    public function mount() {
        $this->from_date =\Carbon\Carbon::today()->toDateString();
       
        if(session()->has('selected_language'))
        {
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            $this->lang = Translation::where('default',1)->first();
        }
        $this->report();
    }
    /*processed on update of the element */
  public function updated($name,$value) {

        $this->report();
    }
    /* report section */ 
    public function report(){
             
             $this->total_orders = \App\Models\Order::whereDate('order_date',$this->from_date)->count();
             $this->delivered_orders = \App\Models\Order::whereDate('order_date',$this->from_date)->where('status',3)->count();
             $this->total_payment = \App\Models\Payment::whereDate('payment_date',$this->from_date)->sum('received_amount');
             $this->total_expense = \App\Models\Expense::whereDate('expense_date',$this->from_date)->sum('expense_amount');
             $this->total_sales = \App\Models\Order::whereDate('order_date',$this->from_date)->where('is_fully_paid',1)->sum('total');
    }
        /* download report */
    public function downloadFile()
    {
        $from_date =\Carbon\Carbon::today()->toDateString();
        $total_orders = \App\Models\Order::whereDate('order_date',$this->from_date)->count();
        $delivered_orders = \App\Models\Order::whereDate('order_date',$this->from_date)->where('status',3)->count();
        $total_payment = \App\Models\Payment::whereDate('payment_date',$this->from_date)->sum('received_amount');
        $total_expenses = \App\Models\Expense::whereDate('expense_date',$this->from_date)->sum('expense_amount');
        $total_sales = \App\Models\Order::whereDate('order_date',$this->from_date)->where('is_fully_paid',1)->sum('total');
        $pdfContent = PDF::loadView('livewire.admin.reports.download-report.daily-report', compact('from_date','total_orders','delivered_orders','total_payment','total_expenses','total_sales'))->output();
        return response()->streamDownload(fn () => print($pdfContent), "DailyOrderReport_from_" . $from_date . ".pdf");
    }
}