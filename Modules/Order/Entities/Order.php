<?php

namespace Modules\Order\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use SoftDeletes;

    public $table = 'orders';

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected $fillable = [
        'ticket_number',
        'phone',
        'address',
        'total_amount',
        'total_discount',
        'total_to_pay',
        'payment_type',
        'status',
        'note',
        'manager_note',
        'customer_id',
        'creator_id',

        'completed_at',
        'order_date',
        'deleted_at',
        'created_at',
        'updated_at'
    ];



    public static $sortable = [
        "id",
        "ticket_number",
        "total_amount",
        "total_to_pay",
        'completed_at',
        'order_date',
        'created_at',
    ];

    const STATUS_LIST = [
        1 => 'pending',
        2 => 'ontheway',
        3 => 'shipped',
        4 => 'completed',
        5 => 'canceled',
    ];

    public static function getStatusList($val = false){
        $list = [
            ['value' => 1, 'label' => 'Pending', ],
            ['value' => 2, 'label' => 'On The Way', ],
            ['value' => 3, 'label' => 'Shipped', ],
            ['value' => 4, 'label' => 'Completed', ],
            ['value' => 5, 'label' => 'Canceled', ],
        ];

        return $val ? $list[$val - 1] : $list;
    }

    const PAYMENT_TYPES = [
        1 => 'cash',
        2 => 'card',
        3 => 'online',
    ];

    public static function getPaymentTypes($val = false){
        $list = [
            ['value' => 1,'label' => 'Cash', ],
            ['value' => 2,'label' => 'Card', ],
            ['value' => 3,'label' => 'Online', ],
        ];

        return $val ? $list[$val - 1] : $list;
    }

    //RELATIONSHIPS
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class,'creator_id')->withTrashed();
    }



    //EXTRA FUNCTIONS
    public static function getNextTicketNum()
    {
        $lastOrder = Order::orderBy('ticket_number', 'desc')->first();
        $lastTicketNum = $lastOrder ? $lastOrder->ticket_num : 0;
        return $lastTicketNum + 1;
    }


    public static function prepareValidRequestData($request,$items)
    {
        $validRequest = $request->validated();

        $validRequest['ticket_number'] = self::getNextTicketNum();
        $validRequest['creator_id'] = Auth::id();

        $validRequest['total_amount'] = 0;
        $validRequest['total_discount'] = 0;
        $validRequest['total_to_pay'] = 0;

        foreach ($items as $item){
            $total = $item['total'];
            $totalDiscount = $item['total_discount'];
            $totalToPay = $total - $totalDiscount;

            $validRequest['total_amount'] += $total;
            $validRequest['total_discount'] += $totalDiscount;
            $validRequest['total_to_pay'] += $totalToPay;
        }

        return $validRequest;
    }

}
