<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Order;
use App\Models\ProductAvailable;
use App\Models\PromotionalCode;
use App\Notifications\Api\Admin\SendOrderStatusNotification;
use App\Traits\ValidateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    use ValidateTrait;

    private function validationId() {
        $this->setValidateRule([
            'id' => 'required|integer|exists:orders,id'
        ]);
    }

    public function index(Request $request)
    {
        $this->setValidateRule([
            'id' => 'nullable|integer',
            'user_name' => 'nullable|string|max:191',
            'user_surname' => 'nullable|string|max:191',
            'user_patronymic' => 'nullable|string|max:191',
            'user_id' => 'nullable|integer',
            'only_new' => 'nullable|boolean',
        ]);
        $this->setValidateAttribute([
            'id' => 'ID заказа',
            'user_name' => 'Имя',
            'user_surname' => 'Фамилия',
            'user_patronymic' => 'Отчество',
            'user_id' => 'ID пользователя',
            'only_new' => 'Только новые заказы',
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        return response()->json([
            'status' => 'success',
            'orders' => Order::orders()
        ]);
    }

    public function store(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Заказ успешно создан',
            'order' => Order::createModel()
        ]);
    }

    public function show($id)
    {
        $this->validationId();
        $validator = Validator::make(['id' => $id], $this->validate_rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $order = Order::getOrder($id);

        return response()->json([
            'status' => 'success',
            'order' => $order
        ]);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find(\request()->get('id'));
        $promotional_code = null;

        $this->validationId();
        $this->setValidateRule([
            'user_id' => 'nullable|integer|exists:users,id',
            'user_name' => 'nullable|string|max:191',
            'user_surname' => 'nullable|string|max:191',
            'user_patronymic' => 'nullable|string|max:191',
            'phone' => 'required|string|max:191',
            'email' => 'nullable|email|max:191',
            'note' => 'nullable|string|max:50000',
            'order_payment_method_id' => 'required|integer|exists:order_payment_methods,id',
            'order_status_id' => 'required|integer|exists:order_statuses,id',
            'promotional_code_id' => [
                'nullable',
                'integer',
                'exists:promotional_codes,id',
                function ($attribute, $value, $fail) use ($order) {
                    if (\request()->filled('promotional_code_id')
                        && request()->get('promotional_code_id') != $order->promotional_code_id) {
                        $promotional_code = PromotionalCode::getCodeById(\request()->get('promotional_code_id'));
                        if ($promotional_code->status == 0) {
                            return $fail('Промокод который вы выбрали уже был использован');
                        }
                    }
                }
            ],
            'input_promotional_code' => [
                'nullable',
                'string',
                'max:191',
                'exists:promotional_codes,code',
                function ($attribute, $value, $fail) use ($order, &$promotional_code) {
                    if (\request()->filled('input_promotional_code')) {
                        $promotional_code = PromotionalCode::getModelByCode(\request()->get('input_promotional_code'));
                        if ($promotional_code->id != $order->promotional_code_id) {
                            if ($promotional_code->status == 0) {
                                return $fail('Промокод который вы ввели уже был использован');
                            }
                        }
                    }
                }
            ],
        ]);
        $this->setValidateAttribute([
            'user_id' => 'Пользователь',
            'user_name' => 'Имя',
            'user_surname' => 'Фамилия',
            'user_patronymic' => 'Отвество',
            'phone' => 'Телефон',
            'email' => 'E-Mail',
            'note' => 'Комментарий',
            'order_payment_method_id' => 'Метод оплаты',
            'order_status_id' => 'Статус',
            'promotional_code_id' => 'Промокод'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $order = Order::updateModel($promotional_code);

        return response()->json([
            'status' => 'success',
            'message' => 'Данные были успешно обновлены',
            'order' => $order,
        ]);
    }

    public function destroy($id)
    {
        $this->validationId();
        $validator = Validator::make(['id' => $id], $this->validate_rules);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        Order::destroyModel($id);

        return response()->json([
            'status' => 'success',
            'message' => 'Заказ был успешно удален'
        ]);
    }

    public function addProduct(Request $request) {
        $this->setValidateRule([
            'order_id' => 'required|integer|exists:orders,id',
            'product_id' => 'required|integer|exists:products,id',
            'product_available_id' => 'required|integer|exists:product_availables,id',
            'quantity' => [
                'integer',
                'min:1',
                function ($attribute, $value, $fail) {
                    $available = ProductAvailable::getItem(\request()->get('product_available_id'));
                    if ($available->product_id != \request()->get('product_id')) {
                        return $fail('Эта опция не пренадлежит указанному товару');
                    }
                    if ($available->quantity < $value) {
                        return $fail('Вы передали не допустимое количество товара по данной опции');
                    }
                }
            ]
        ]);
        $this->setValidateAttribute([
            'order_id' => 'Заказ',
            'product_id' => 'Продукция',
            'product_available_id' => 'Наличие',
            'quantity' => 'Количество',
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $order = Order::addProduct();

        return response()->json([
            'status' => 'success',
            'message' => 'Товар был добавлен в заказ',
            'order' => $order,
        ]);
    }

    public function deleteProduct(Request $request) {
        $this->setValidateRule([
            'order_id' => 'required|integer|exists:orders,id',
            'order_product_id' => 'required|integer|exists:order_products,id'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $operation = Order::deleteProduct();

        return response()->json([
            'status' => 'success',
            'message' => 'Товар был удален с заказа',
            'order' => $operation['order'],
            'order_product' => $operation['order_product']
        ]);
    }

    public function deleteStatus(Request $request) {
        $this->setValidateRule([
            'order_id' => 'required|integer|exists:orders,id',
            'order_status_id' => [
                'required',
                'integer',
                'exists:order_history_statuses,id',
                function ($attribute, $value, $fail) {
                    $first_status = Order::getFirstStatus();
                    if ($first_status->id === $value) {
                        return $fail('Вы не можете удалить первый статус заказа');
                    }
                }
            ]
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $order = Order::deleteStatus();

        return response()->json([
            'status' => 'success',
            'message' => 'Статус был успешно удален',
            'order' => $order,
        ]);
    }

    public function sendStatus(Request $request) {
        $order = Order::getOrder($request->order_id);
        $status = null;
        $this->setValidateRule([
            'order_id' => 'required|integer|exists:orders,id',
            'status_id' => [
                'required',
                'integer',
                'exists:order_history_statuses,id',
                function ($attribute, $value, $fail) use ($order, &$status) {
                    $status = $order->historyStatuses->where('id', $value)->first();

                    $order_status = $status->status()->where('id', $status->order_status_id)->first();
                    $status->setRelation('status', $order_status);

                    if ($status->send_status == 1) {
                        return $fail('Вы уже отправляли этот статус пользователю');
                    }
                }
            ]
        ]);
        $this->setValidateAttribute([
            'order_id' => 'Заказ',
            'status_id' => 'Статус'
        ]);
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $email = '';
        if ($order->email !== null) {
            $email = $order->email;
        }
        elseif ($order->user !== null) {
            $email = $order->user->email;
        }

        Notification::route('mail', $email)
            ->notify(new SendOrderStatusNotification($status, $order->id));

        $status->send_status = true;
        $status->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Статус был успешно отправлен',
            'order_status' => $status
        ]);
    }

    public function updateReadStatus(Request $request) {
        $this->validationId();
        $request->validate($this->validate_rules, [], $this->validate_attributes);

        $order = Order::updateReadStatus();

        return response()->json([
            'status' => 'success',
            'order' => $order
        ]);
    }
}
