<?php

namespace App\Models;

use App\Events\AdminEvent;
use App\Tools\DateTimeTools;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Order
 *
 * @property int $id
 * @property int|null $user_id
 * @property string|null $user_name
 * @property string|null $user_surname
 * @property string|null $user_patronymic
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $note
 * @property int|null $order_status_id
 * @property int|null $delivery_method
 * @property int|null $payment_method_id
 * @property int|null $promotion_code_id
 * @property float|null $total_price
 * @property float|null $total_discount_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereDeliveryMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereOrderStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order wherePromotionalCodeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalDiscountPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserPatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereUserSurname($value)
 * @mixin \Eloquent
 * @property int|null $order_payment_method_id
 * @property int|null $promotional_code_id
 * @property int $read_status
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderHistoryStatus[] $historyStatuses
 * @property-read \App\Models\OrderPaymentMethod $paymentMethod
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderProduct[] $products
 * @property-read \App\Models\PromotionalCode $promotionalCode
 * @property-read \App\Models\OrderStatus $status
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order disableCache()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereOrderPaymentMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereReadStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order withCacheCooldownSeconds($seconds)
 * @property string|null $area_id
 * @property string|null $city_id
 * @property string|null $warehouse_id
 * @property-read \App\Models\NovaPoshtaArea $npArea
 * @property-read \App\Models\NovaPoshtaCity $npCity
 * @property-read \App\Models\NovaPoshtaWarehouse $npWarehouse
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Order whereWarehouseId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\OrderPromotionalCodeUsedCash[] $promotionalCodeUsedCash
 */
class Order extends Model
{
    use Cachable;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_surname',
        'phone',
        'email',
        'note',
        'order_status_id',
        'delivery_method',
        'area_id',
        'city_id',
        'warehouse_id',
        'order_payment_method_id',
        'promotional_code_id',
        'total_price',
        'total_discount_price',
        'read_status'
    ];
    protected $casts = [
        'user_id' => 'integer',
        'order_status_id' => 'integer',
        'delivery_method' => 'integer',
        'order_payment_method_id' => 'integer',
        'promotional_code_id' => 'integer',
        'total_price' => 'float',
        'total_discount_price' => 'float',
        'read_status' => 'integer'
    ];

    protected $with = [
        'products',
        'historyStatuses',
        'promotionalCode',
        'paymentMethod',
        'npArea',
        'npCity',
        'npWarehouse'
    ];

    public function npArea() {
        return $this->hasOne('App\Models\NovaPoshtaArea', 'ref', 'area_id');
    }

    public function npCity() {
        return $this->hasOne('App\Models\NovaPoshtaCity', 'ref', 'city_id');
    }

    public function npWarehouse() {
        return $this->hasOne('App\Models\NovaPoshtaWarehouse', 'ref', 'warehouse_id');
    }

    public function user() {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function status() {
        return $this->hasOne('App\Models\OrderStatus', 'id', 'order_status_id');
    }

    public function paymentMethod() {
        return $this->hasOne('App\Models\OrderPaymentMethod', 'id', 'order_payment_method_id');
    }

    public function promotionalCode() {
        return $this->hasOne('App\Models\PromotionalCode', 'id', 'promotional_code_id');
    }

    public function products() {
        return $this->hasMany('App\Models\OrderProduct', 'order_id', 'id');
    }

    public function historyStatuses() {
        return $this->hasMany('App\Models\OrderHistoryStatus', 'order_id', 'id');
    }

    public function promotionalCodeUsedCash() {
        return $this->hasMany('App\Models\OrderPromotionalCodeUsedCash', 'order_id', 'id');
    }

    public static function newOrders() {
        return Order::where('read_status', false)->count();
    }

    public static function createModel($note = null) {
        $order = new Order();

        $default_order_status = OrderStatus::getDefault();
        if ($default_order_status === null) {
            $default_order_status = OrderStatus::createDefaultStatus();
        }
        $order->order_status_id = $default_order_status->id;
        $order->note = $note;
        $order->save();

        $order->historyStatuses()->create([
            'order_status_id' => $default_order_status->id,
            'send_status' => 0
        ]);

        return $order->fresh();
    }

    protected static function recalculatePrice($order, $promotional_code = null) {
        $discount = 0;
        if (isset($order->user) && $order->user !== null) {
            if ($order->user->discount > 0) {
                $discount = $order->user->discount;
            }
            elseif ($order->user->group !== null) {
                $user_group = UserGroup::userGroup($order->user->group->user_group_id);
                $discount = $user_group->discount;
            }
        }

        if ($promotional_code !== null && $promotional_code->type === 0) {
            $discount = (($discount + $promotional_code->discount) > 100)
                ? 100
                : $discount + $promotional_code->discount;

            $promotional_code->status = 0;
        }

        $total_discount_price = 0;
        $only_price = 0;
        $total_price = 0;
        $order->products->each(function ($product) use ($discount, &$total_price, &$only_price, &$total_discount_price) {
            $total_price += $product->price;
            $only_price += ($product->discount_price == null) ? $product->price : 0;
            if ($product->discount_price != null) {
                $total_discount_price += $product->discount_price;
            }
            else {
                $deduct_discount = $product->price * ((100 - $discount) / 100);
                $total_discount_price += $deduct_discount;
            }
        });

        if ($promotional_code !== null && $promotional_code->type === 1) {
            $promotional_code->cash_value += $order->promotionalCodeUsedCash()->sum('cash');
            $cash_value = $promotional_code->cash_value;
            $order->promotionalCodeUsedCash()->delete();

            $difference_cash_value = $only_price - $promotional_code->cash_value;
            if ($difference_cash_value < 0) {
                $order->promotionalCodeUsedCash()->create([
                    'promotional_code_id' => $promotional_code->id,
                    'cash' => $promotional_code->cash_value - abs($difference_cash_value)
                ]);

                $promotional_code->cash_value = abs($difference_cash_value);
                $promotional_code->status = 1;
            }
            else {
                $order->promotionalCodeUsedCash()->create([
                    'promotional_code_id' => $promotional_code->id,
                    'cash' => $promotional_code->cash_value
                ]);
                $promotional_code->status = 0;
                $promotional_code->cash_value = 0;
            }
            $promotional_code->save();

            $only_price_diff_cash_value = $only_price - $cash_value;
            $only_price_diff_cash_value = ($only_price_diff_cash_value < 0) ? 0 : $only_price_diff_cash_value;
            $difference_cash_value = $only_price_diff_cash_value + ($total_discount_price - $only_price);

            $total_discount_price = ($difference_cash_value < 0) ? 0 : $difference_cash_value;
        }

        $order->total_price = $total_price;
        $order->total_discount_price = $total_discount_price;

        return $order;
    }

    protected function updateModel($promotional_code = null) {
        $order = Order::find(request()->get('id'));

        $order->user_id = request()->get('user_id');
        $order->user_name = request()->get('user_name');
        $order->user_surname = request()->get('user_surname');
        $order->phone = request()->get('phone');
        $order->email = request()->get('email');
        $order->note = request()->get('note');

        $order->delivery_method = request()->get('delivery_method');
        $order->area_id = request()->get('area_id');
        $order->city_id = request()->get('city_id');
        $order->warehouse_id = request()->get('warehouse_id');

        if ($order->order_status_id != request()->get('order_status_id')) {
            $order->order_status_id = request()->get('order_status_id');
            $order_history_status = $order->historyStatuses()->create([
                'order_status_id' => request()->get('order_status_id'),
                'send_status' => 0
            ]);
            $order->historyStatuses->push($order_history_status);
        }
        $order->order_payment_method_id = request()->get('order_payment_method_id');
        $order_promotional_code_id = $order->promotional_code_id;
        $order->promotional_code_id = request()->get('promotion_code_id');

        if ($promotional_code === null && !request()->filled('promotional_code_id')) {
            if ($order_promotional_code_id !== null) {
                $code = PromotionalCode::getCodeById($order_promotional_code_id);
                $code->cash_value += $order->promotionalCodeUsedCash()->sum('cash');
                $code->status = 1;
                $code->save();

                event(new AdminEvent('promotional_code', $code));

                $order->promotionalCodeUsedCash()->delete();
            }

            $order->promotional_code_id = null;
            $order->setRelation('promotional_code', null);
        }
        elseif (request()->filled('promotional_code_id') || $promotional_code !== null) {
            if (request()->filled('promotional_code_id')
                && $promotional_code !== null
                && request()->get('promotional_code_id') !== $promotional_code) {
                $promotional_code_id = $promotional_code->id;
            }
            elseif (request()->filled('promotional_code_id')) {
                $promotional_code = PromotionalCode::getCodeById(request()->get('promotional_code_id'));
                $promotional_code_id = request()->get('promotional_code_id');
            }
            else {
                $promotional_code_id = $promotional_code->id;
            }

            if ($promotional_code_id != $order->promotional_code_id) {
                $order->setRelation('promotional_code', $promotional_code);

                if ($order->user !== null) {
                    $order->user->promotionalCodeUsage()->create([
                        'promotional_code_id' => $promotional_code_id
                    ]);
                }
            }

            $order->promotional_code_id = $promotional_code_id;
        }

        $order = self::recalculatePrice($order, $promotional_code);

        $order->save();

        return $order;
    }

    protected function getOrder($id, $check_auth_user = false) {
        $where = [];
        $where[] = ['id', $id];
        if ($check_auth_user) {
            $where[] = ['user_id', auth()->user()->id];
        }
        return $this::where($where)->first();
    }

    protected function orders() {
        $query = Order::query();
        if (request()->filled('id')) {
            $query->where('id', request()->get('id'));
        }

        if (request()->filled('user_name')) {
            $query->where(function ($query) {
                $query->whereRaw('lower(user_name) like ?', ['%'. request()->get('user_name') .'%']);
                $query->orWhereHas('user', function ($query) {
                    $query->whereRaw('lower(user_name) like ?', ['%'. request()->get('user_name') .'%']);
                });
            });
        }

        if (request()->filled('phone')) {
            $query->where(function ($query) {
                $query->whereRaw('lower(phone) like ?', ['%'. request()->get('phone') .'%']);
                $query->orWhereHas('user', function ($query) {
                    $query->whereRaw('lower(phone) like ?', ['%'. request()->get('phone') .'%']);
                });
            });
        }

        if (request()->filled('user_surname')) {
            $query->where(function ($query) {
                $query->whereRaw('lower(user_surname) like ?', ['%'. request()->get('user_surname') .'%']);
                $query->orWhereHas('user', function ($query) {
                    $query->whereRaw('lower(user_surname) like ?', ['%'. request()->get('user_surname') .'%']);
                });
            });
        }

        if (request()->filled('article')) {
            $query->whereHas('products', function ($query) {
                $query->whereHas('product', function ($query) {
                    $query->where('article', request()->get('article'));
                });
            });
        }

        if (request()->filled('date_start') || request()->filled('date_end')) {
            $date_start = $date_end = null;

            if (request()->filled('date_start')) {
                $date_start = DateTimeTools::explodeRequestDateTime(request()->get('date_start'))->date;
            }

            if (request()->filled('date_end')) {
                $date_end = DateTimeTools::explodeRequestDateTime(request()->get('date_end'))->date;
            }

            if ($date_start !== null && $date_end !== null) {
                $query->whereDate('created_at', '>=', $date_start);
                $query->whereDate('created_at', '<=', $date_end);
            } elseif ($date_start !== null && $date_end === null) {
                $query->whereDate('created_at', '>=', $date_start);
            } elseif ($date_start === null && $date_end !== null) {
                $query->whereDate('created_at', '<=', $date_end);
            }
        }

        if (request()->filled('user_id')) {
            $query->where('user_id', request()->get('user_id'));
        }

        if (request()->filled('only_new') && request()->get('only_new') == 1) {
            $query->where('read_status', false);
        }

        return $query->orderByDesc('created_at')->paginate();
    }

    protected function addProduct() {
        $order = Order::find(request()->get('order_id'));
        $product = Product::getProduct(request()->get('product_id'));

        $bestseller = $product->bestseller()->firstOrCreate([]);
        $bestseller->update([
            'quantity' => $bestseller->quantity + request()->get('quantity')
        ]);

        $available = $product->available()->where('id', request()->get('product_available_id'))->first();
        $available->update([
            'quantity' => $available->quantity - request()->get('quantity')
        ]);

        $price = $product->price  * request()->get('quantity');
        $discount_price = $product->discount_price * request()->get('quantity');

        $order_product = $order->products()->create([
            'product_id' => $product->id,
            'product_available_id' => request()->get('product_available_id'),
            'price' => $price,
            'discount_price' => $discount_price,
            'product_price' => $product->price,
            'product_discount_price' => $product->discount_price,
            'quantity' => request()->get('quantity')
        ])->fresh();

        $order->products->push($order_product);

        $order = self::recalculatePrice($order, $order->promotionalCode);

        $order->save();

        return $order;
    }

    protected function deleteProduct() {
        $order = Order::find(request()->get('order_id'));
        $order_product = $order->products->where('id', request()->get('order_product_id'))->first();

        $available = $order_product->available()->where('id', $order_product->product_available_id)->first();
        $available->update([
            'quantity' => $order_product->quantity + $available->quantity
        ]);

        $bestseller = Product::getProduct($order_product->product_id)->bestseller()->firstOrCreate([]);
        $difference_quantity = $bestseller->quantity - $available->quantity;
        $bestseller->update([
            'quantity' => ($difference_quantity < 0) ? 0 : $difference_quantity
        ]);

        $order->products()->where('id', request()->get('order_product_id'))->delete();
        $products = $order->products->filter(function ($item) {
            return $item->id !== request()->get('order_product_id');
        });
        $order->setRelation('products', $products);

        $order = self::recalculatePrice($order, $order->promotionalCode);

        $order->save();

        return [
            'order' => $order->fresh(),
            'order_product' => $order_product
        ];
    }

    protected function deleteStatus() {
        $order = Order::find(request()->get('order_id'));
        $order->historyStatuses()->where('id', request()->get('order_status_id'))->delete();

        return $order->fresh();
    }

    protected function getFirstStatus() {
        return Order::find(request()->get('order_id'))
            ->historyStatuses()->orderBy('id', 'asc')
            ->first();
    }

    protected function getStatus() {
        return Order::find(request()->get('order_id'))
            ->historyStatuses()
            ->where('id', request()->get('status_id'))
            ->first();
    }

    protected function updateReadStatus() {
        $order = Order::find(\request()->get('id'));
        $order->read_status = true;
        $order->save();

        return $order;
    }

    protected function getOrdersPublic($limit = 10) {
        return Order::where('user_id', auth()->user()->id)
            ->orderByDesc('created_at')
            ->paginate($limit);
    }

    protected function checkAccess($order_id) {
        return Order::where([
            ['order_id', $order_id],
            ['user_id', auth()->user()->id]
        ])->first();
    }

    protected function destroyModel($id) {
        Order::find($id)->delete();
    }

    public static function resetPromotionalCode($promotional_code_id) {
        $order = Order::where('promotional_code_id', $promotional_code_id)->first();
        if ($order !== null) {
            $order->update([
                'promotional_code_id' => null
            ]);
            $order = self::recalculatePrice($order);
        }

        return $order;
    }
}
