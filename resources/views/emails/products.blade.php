@if(isset($order) && $order->products->count() > 0)
    <tr style="border-collapse:collapse;">
        <td align="left" style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px;">
            <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="200" valign="top"><![endif]-->
            <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                <tr style="border-collapse:collapse;">
                    <td class="es-m-p0r es-m-p20b" width="200" valign="top" align="center" style="padding:0;Margin:0;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td class="es-m-txt-c" align="left" style="padding:0;Margin:0;padding-left:5px;">
                                    <h4 style="Margin:0;line-height:120%;mso-line-height-rule:exactly;font-family:'trebuchet ms', helvetica, sans-serif;">Товары в заказе</h4> </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if mso]></td><td width="20"></td><td width="340" valign="top"><![endif]-->
            <table cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                <tr style="border-collapse:collapse;">
                    <td width="340" align="left" style="padding:0;Margin:0;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td align="left" style="padding:0;Margin:0;">
                                    <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;" class="cke_show_border" cellspacing="1" cellpadding="1" border="0">
                                        <tr style="border-collapse:collapse;">
                                            <td style="padding:0;Margin:0;"><span style="font-size:13px;">Наименование</span></td>
                                            <td style="padding:0;Margin:0;text-align:center;" width="60"><span style="font-size:13px;">Количество</span></td>
                                            <td style="padding:0;Margin:0;text-align:center;" width="100"><span style="font-size:13px;"><span style="line-height:100%;">Цена</span></span>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
        </td>
    </tr>
    <tr style="border-collapse:collapse;">
        <td align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;">
            <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                <tr style="border-collapse:collapse;">
                    <td width="560" valign="top" align="center" style="padding:0;Margin:0;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td align="center" style="padding:0;Margin:0;padding-bottom:10px;">
                                    <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tr style="border-collapse:collapse;">
                                            <td style="padding:0;Margin:0px;border-bottom:1px solid #EFEFEF;background:rgba(0, 0, 0, 0) none repeat scroll 0% 0%;height:1px;width:100%;margin:0px;"></td>
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

    @foreach($order->products as $product)
    <tr style="border-collapse:collapse;">
        <td align="left" style="Margin:0;padding-top:5px;padding-bottom:10px;padding-left:20px;padding-right:20px;">
            <!--[if mso]><table  width="560" cellpadding="0" cellspacing="0"><tr><td width="95" valign="top"><![endif]-->
            @if($product->product->mainImage !== null)
            <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                <tr style="border-collapse:collapse;">
                    <td class="es-m-p0r es-m-p20b" width="95" valign="top" align="center" style="padding:0;Margin:0;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td class="es-m-txt-c" align="left" style="padding:0;Margin:0;">
                                    <a href="{{url('/product/' . $product->product->slug)}}"
                                       target="_blank">
                                        <img src="{{asset('/app/public/images/products/' . $product->product->mainImage->preview)}}"
                                             alt="{{$product->product->name}}" class="adapt-img"
                                             title="{{$product->product->name}}" style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;" width="94">
                                    </a>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            @endif
            <!--[if mso]></td><td width="20"></td><td width="445" valign="top"><![endif]-->
            <table cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                <tr style="border-collapse:collapse;">
                    <td width="445" align="left" style="padding:0;Margin:0;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td align="left" style="padding:0;Margin:0;">
                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                        <br>
                                    </p>
                                    <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%;" class="cke_show_border" cellspacing="1" cellpadding="1" border="0">
                                        @php
                                            $price = ($product->discount_price > 0 && $product->discount_price < $product->price)
                                                ? $product->discount_price : $product->price;
                                        @endphp
                                        <tr style="border-collapse:collapse;">
                                            <td style="padding:0;Margin:0;">
                                                <a href="{{url('/product/' . $product->product->slug)}}"
                                                    target="_blank"
                                                    style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;text-decoration:none;color:#000;">
                                                    {{$product->product->name}}
                                                </a>
                                                @isset($product->available)
                                                    <br>
                                                    @foreach($product->available->filters as $filter)
                                                        @php
                                                            $data_filter = $filters->firstWhere('id', $filter->filter_id);
                                                            $data_parent_filter = $filters->firstWhere('id', $data_filter->parent_id);
                                                        @endphp
                                                        @if($data_parent_filter->active)
                                                            {{$data_parent_filter->name}} : {{$data_filter->name}}
                                                            @if($loop->iteration !== $product->available->filters->count()) + @endif
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td style="padding:0;Margin:0;text-align:center;" width="60">{{$product->quantity}}</td>
                                            <td style="padding:0;Margin:0;text-align:center;" width="100">
                                                @if($product->discount_price > 0 && $product->discount_price < $product->price)
                                                    <strike>{{$product->price}} грн.</strike>
                                                @endif
                                                {{$price}} грн.
                                            </td>
                                        </tr>
                                    </table>
                                    <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;">
                                        <br>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <!--[if mso]></td></tr></table><![endif]-->
        </td>
    </tr>
    <tr style="border-collapse:collapse;">
        <td align="left" style="padding:0;Margin:0;padding-left:20px;padding-right:20px;">
            <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                <tr style="border-collapse:collapse;">
                    <td width="560" valign="top" align="center" style="padding:0;Margin:0;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td align="center" style="padding:0;Margin:0;padding-bottom:10px;">
                                    <table width="100%" height="100%" cellspacing="0" cellpadding="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tr style="border-collapse:collapse;">
                                            <td style="padding:0;Margin:0px;border-bottom:1px solid #EFEFEF;background:rgba(0, 0, 0, 0) none repeat scroll 0% 0%;height:1px;width:100%;margin:0px;"></td>
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
    @endforeach
@endif