<?php
namespace App\Http\Livewire\Admin\Orders;
use App\Models\Order;
use App\Models\Translation;
use Livewire\Component;
use Auth;
class OrderStatusScreen extends Component
{
    public $orders,$pending_orders,$processing_orders,$delivered_orders,$ready_orders,$lang,$from_date, $to_date;
    protected $general_query;
    
    /* render the page */
    public function render()
    {
        
        $this->report();
        return view('livewire.admin.orders.order-status-screen');
    }
    /* process before render */
    public function mount()
    {
        $this->from_date =  \Carbon\Carbon::today()->toDateString();
        $this->to_date = \Carbon\Carbon::today()->toDateString();

        //$this->general_query  = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date);

        if(session()->has('selected_language'))
        {  /* if session has selected language */
            $this->lang = Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = Translation::where('default',1)->first();
        }
         $this->report();
      
    }
     /*processed on update of the element */
    public function updated($name, $value)
    {
        $this->report();
    }
    /* report section */
    public function report()
    {
        $this->general_query = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date);

    //$general_query =Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date);
       if(Auth::user()->user_type==1)
        {
        $this->pending_orders = $this->general_query->clone()->where('status',0)->get();
        $this->processing_orders = $this->general_query->clone()->where('status',1)->get();
        $this->ready_orders = $this->general_query->clone()->where('status',2)->get();
        $this->delivered_orders = $this->general_query->clone()->where('status',3)->get();

        } else {
            $this->pending_orders = $this->general_query->clone()->where('created_by',Auth::user()->id)->where('status',0)->get();
            $this->processing_orders = $this->general_query->clone()->where('created_by',Auth::user()->id)->where('status',1)->get();
            $this->ready_orders = $this->general_query->clone()->where('created_by',Auth::user()->id)->where('status',2)->get();
            $this->delivered_orders = $this->general_query->clone()->where('created_by',Auth::user()->id)->where('status',3)->get();

        }
    }
    /* change the order status */
    public function changestatus($order,$status)
    {
        $orderz = Order::whereDate('order_date', '>=', $this->from_date)->whereDate('order_date', '<=', $this->to_date)->where('id',$order)->first();
        switch($status)
        {
            case 'processing':
                $orderz->status = 1;
                $orderz->save();
                $message = sendOrderStatusChangeSMS($orderz->id,1);
                break;
            case 'ready':
                $orderz->status = 2;
                $orderz->save();
                $message = sendOrderStatusChangeSMS($orderz->id,2);
                break;
            case 'pending':
                $orderz->status = 0;
                $orderz->save();
                $message = sendOrderStatusChangeSMS($orderz->id,3);
                break;
            case 'delivered':
                $orderz->status = 3;
                $orderz->save();
                $message = sendOrderStatusChangeSMS($orderz->id,4);
                break;
        }

        if($message)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => $message,'title'=>'SMS Error']);
        }
    }
}