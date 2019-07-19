@isset($order)
<tr style="border-collapse:collapse;">
    <td align="left" style="Margin:0;padding-top:5px;padding-left:20px;padding-bottom:30px;padding-right:40px;">
        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
            <tr style="border-collapse:collapse;">
                <td width="540" valign="top" align="center" style="padding:0;Margin:0;">
                    <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                        <tr style="border-collapse:collapse;">
                            <td align="right" style="padding:0;Margin:0;">
                                <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:500px;" class="cke_show_border" cellspacing="1" cellpadding="1" border="0" align="right">
                                    <tr style="border-collapse:collapse;">
                                        @php
                                            $total_quantity = $order->products->sum('quantity');
                                        @endphp
                                        <td style="padding:0;Margin:0;text-align:right;font-size:18px;line-height:27px;">
                                            {{$total_quantity}} {{num2str($total_quantity, ['товар', 'товара', 'товаров'])}}:
                                        </td>
                                        <td style="padding:0;Margin:0;text-align:right;font-size:18px;line-height:27px;">{{$order->total_price}} грн.</td>
                                    </tr>
                                    @php
                                        $discount = $order->total_price - $order->total_discount_price;
                                    @endphp
                                    @if ($discount !== $order->total_price)
                                    <tr style="border-collapse:collapse;">
                                        <td style="padding:0;Margin:0;text-align:right;font-size:18px;line-height:27px;">Скидка:</td>
                                        <td style="padding:0;Margin:0;text-align:right;font-size:18px;line-height:27px;">{{$discount}} грн.</td>
                                    </tr>
                                    @endif
                                    <tr style="border-collapse:collapse;">
                                        <td style="padding:0;Margin:0;text-align:right;font-size:18px;line-height:27px;"><strong>Итоговая сумма:</strong></td>
                                        <td style="padding:0;Margin:0;text-align:right;font-size:18px;line-height:27px;color:#000000;">
                                            <strong>
                                                {{($discount !== $order->total_price) ? $order->total_discount_price : $order->total_price}}
                                                грн.
                                            </strong>
                                        </td>
                                    </tr>
                                </table>
                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#000000;">
                                    <br>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
@endisset