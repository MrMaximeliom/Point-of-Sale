<div>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{$lang->data['daily_report'] ?? 'Daily Report'}}</title>
        <style>
            #main {
                border-collapse: collapse;
                line-height: 1rem;
                text-align: center;
            }
            th {
                background-color: rgb(101, 104, 101);
                Color: white;
                font-size: 0.75rem;
                line-height: 1rem;
                font-weight: bold;
                text-transform: uppercase;
                text-align: center;
                padding: 10px;
            }
            td {
                text-align: center;
                border: 1px solid;
                font-size: 0.75rem;
                line-height: 1rem;
            }
            .col {
                border: none;
                text-align: left;
                padding: 10px;
                font-size: 0.75rem;
                line-height: 1rem;
            }
        </style>
    </head>
    <body onload="">
         @php
          
            $lang = null;
        if (session()->has('selected_language')) {
        $lang = \App\Models\Translation::where('id', session()->get('selected_language'))->first();
        } else {
            $lang = \App\Models\Translation::where('default', 1)->first();
        }
        @endphp

        <h3 class="fw-500 text-dark">{{$lang->data['daily_report'] ?? 'Daily Report'}}</h3>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
            <tr>
                <td class="col"> <label>{{$lang->data['Date'] ?? 'Date'}}: </label>
                    {{ \Carbon\Carbon::parse($from_date)->format('d/m/Y') }}</td>

            </tr>
        </table>
        <table id="main" width="100%" cellpadding="0" cellspacing="0">
            <thead class="table-dark">
                <tr>
                    <th class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['total_orders'] ?? 'Total Orders'}}</th>
                    <th class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['delivered_orders'] ?? 'No. of orders delivered'}}</th>
                    <th class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['total_sales'] ?? 'Total sales for fully paid orders'}}</th>
                    <th class="text-uppercase text-secondary text-xs  opacity-7">{{$lang->data['total_payment'] ?? 'Total Payment'}}</th>
                    <th class="text-uppercase text-secondary text-xs opacity-7">{{$lang->data['total_expenses'] ?? 'Total Expenses'}}</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                        <td>
                            <p class="text-xs px-3 mb-0">
                                {{ $total_orders }}
                            </p>
                        </td>
                        <td>
                            <p class="text-xs px-3 mb-0">
                                {{ $delivered_orders }}
                            </p>
                        </td>
                        <td>
                            <p class="text-xs px-3 font-weight-bold mb-0">
                                  {{ getCurrency() }}&nbsp;{{ number_format($total_sales, 2) }}</p>
                        </td>
                        <td style="text-align: center">
                            <p class="text-xs px-3 font-weight-bold mb-0">
                                {{ getCurrency() }}&nbsp;{{ number_format($total_payment, 2) }}</p>
                        </td>
                        <td style="text-align: center">
                           <p class="text-xs px-3 font-weight-bold mb-0">
                                {{ getCurrency() }}&nbsp;{{ number_format($total_expenses, 2) }}</p>
                        </td>
                    </tr>
            </tbody>
        </table>
        
    </body>
    </html>
</div>