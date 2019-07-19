@isset($order)
<table cellpadding="0" cellspacing="0" class="es-content" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
    <tr style="border-collapse:collapse;">
        <td align="center" style="padding:0;Margin:0;">
            <table bgcolor="#ffffff" class="es-content-body" align="center" cellpadding="0" cellspacing="0" width="600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;">
                <tr style="border-collapse:collapse;">
                    <td align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;padding-bottom:30px;">
                        <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td width="560" align="center" valign="top" style="padding:0;Margin:0;">
                                    <table cellpadding="0" cellspacing="0" width="100%" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" style="padding:0;Margin:0;padding-bottom:10px;padding-top:20px;padding-right:20px;">
                                                <h4 style="Margin:0;line-height:22px;mso-line-height-rule:exactly;font-family:'trebuchet ms', helvetica, sans-serif;font-size:18px;">О заказе:</h4> </td>
                                        </tr>
                                        <tr class="es-mobile-hidden" style="border-collapse:collapse;">
                                            <td align="center" style="padding:0;Margin:0;padding-bottom:20px;padding-right:20px;">
                                                <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="left">
                                                    <tr style="border-collapse:collapse;">
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">ID:</td>
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">{{$order->id}}</td>
                                                    </tr>
                                                    @if($order->user_surname !== null || $order->user_name !== null)
                                                    <tr style="border-collapse:collapse;">
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">Покупатель:</td>
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">
                                                            <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                                <strong>
                                                                    {{$order->user_surname . ' ' . $order->user_name}}
                                                                </strong>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    <tr style="border-collapse:collapse;">
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">Статус заказа:</td>
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">{{$order->status->name}}</td>
                                                    </tr>
                                                    @if($order->paymentMethod !== null)
                                                    <tr style="border-collapse:collapse;">
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">Метод оплаты:</td>
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">{{$order->paymentMethod->name}}</td>
                                                    </tr>
                                                    @endif
                                                    @if($order->delivery_method !== null)
                                                    <tr style="border-collapse:collapse;">
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">Метод доставки:</td>
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">
                                                            @if($order->delivery_method === 1)
                                                                Новая почта
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @if($order->delivery_method !== null)
                                                    <tr style="border-collapse:collapse;">
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">Данные отделения:</td>
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">
                                                            обл. {{$order->npArea->description}},
                                                            {{$order->npCity->description}},
                                                            {{$order->npWarehouse->description}}
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    <tr style="border-collapse:collapse;">
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;"><strong>Сумма заказа:</strong></td>
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;"><u><strong>{{$order->total_price}} грн.</strong></u></td>
                                                    </tr>

                                                    @if($order->total_discount_price > 0
                                                        && $order->total_discount_price < $order->total_price)
                                                    <tr style="border-collapse:collapse;">
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">
                                                            <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;"><strong>Сумма заказа</strong></p>
                                                            <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;"><strong>с учётом скидок:</strong></p>
                                                        </td>
                                                        <td style="padding:0;Margin:0;font-size:14px;line-height:21px;">
                                                            <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;"><u><strong>{{$order->total_discount_price}} грн.</strong></u></p>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                </table>
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                    <br>
                                                </p>
                                            </td>
                                        </tr>
                                        <tr class="es-desk-hidden" style="display:none;float:left;overflow:hidden;width:0;max-height:0;line-height:0;mso-hide:all;border-collapse:collapse;">
                                            <td align="left" style="padding:0;Margin:0;">
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;text-align:left;">
                                                    <strong>ID:</strong> {{$order->id}}
                                                </p>
                                                @if($order->user_surname !== null || $order->user_name !== null)
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                    <strong>ФИО покупателя: </strong>
                                                    {{$order->user_surname . ' ' . $order->user_name}}
                                                </p>
                                                @endif
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                                    <strong>Статус заказа: </strong>
                                                    {{$order->status->name}}
                                                </p>
                                                @if($order->paymentMethod !== null)
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;"><strong>Метод оплаты: </strong>{{$order->paymentMethod->name}}</p>
                                                @endif
                                                @if($order->delivery_method !== null)
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;"><strong>Метод доставки: </strong>
                                                    @if($order->delivery_method === 1)
                                                        Новая почта
                                                    @endif
                                                </p>
                                                @endif
                                                @if($order->delivery_method !== null)
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;"><strong>Данные отделения: </strong>
                                                    обл. {{$order->npArea->description}},
                                                    {{$order->npCity->description}},
                                                    {{$order->npWarehouse->description}}
                                                </p>
                                                @endif
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;"><strong>Сумма заказа: <u>{{$order->total_price}} грн.</u></strong></p>
                                                @if($order->total_discount_price > 0
                                                    && $order->total_discount_price < $order->total_price)
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;"><strong>Сумма заказа </strong></p>
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;"><strong>с учётом скидок: <u>{{$order->total_discount_price}} грн.</u></strong></p>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
@endisset