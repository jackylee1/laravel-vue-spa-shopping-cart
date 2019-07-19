<table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
    <tr style="border-collapse:collapse;">
        <td align="center" style="padding:0;Margin:0;">
            <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;">
                <tr style="border-collapse:collapse;">
                    <td align="left" style="Margin:0;padding-top:10px;padding-bottom:10px;padding-left:20px;padding-right:20px;">
                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                            <tr style="border-collapse:collapse;">
                                <td width="560" valign="top" align="center" style="padding:0;Margin:0;">
                                    <table style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:separate;border-spacing:0px;border-radius:0px;" width="100%" cellspacing="0" cellpadding="0">
                                        @isset($text)
                                        <tr style="border-collapse:collapse;">
                                            <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:15px;">
                                                <h3 style="Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:'trebuchet ms', helvetica, sans-serif;font-size:30px;font-style:normal;font-weight:normal;color:#333333;">
                                                    {{$text->title}}
                                                </h3>
                                            </td>
                                        </tr>
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" style="padding:0;Margin:0;">
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#110707;">
                                                    {{$text->description}}
                                                </p>
                                            </td>
                                        </tr>
                                        @endisset
                                        @isset($new_order_status)
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="padding:0;Margin:0;padding-top:10px;padding-bottom:15px;">
                                                    <p style="Margin:0;line-height:36px;mso-line-height-rule:exactly;font-family:'trebuchet ms', helvetica, sans-serif;font-size:14px;font-style:normal;font-weight:normal;color:#333333;">
                                                        <b>Новый статус заказа: {{$new_order_status}}</b>
                                                    </p>
                                                </td>
                                            </tr>
                                        @endisset
                                        @if(isset($btn_url) && isset($btn_name) && $btn_url !== null)
                                            <tr style="border-collapse:collapse;">
                                                <td align="center" style="padding:0;Margin:0;padding-bottom:10px;padding-top:15px;">
                                                    <span class="es-button-border"
                                                          style="border-style:solid;border-color:#2CB543;background:#131313;border-width:0px;display:inline-block;border-radius:5px;width:auto;border-top-width:0px;border-bottom-width:0px;">
                                                        <a href="{{$btn_url}}"
                                                           class="es-button" target="_blank"
                                                           style="mso-style-priority:100 !important;text-decoration:underline;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:16px;color:#FFFFFF;border-style:solid;border-color:#131313;border-width:10px 20px 10px 20px;display:inline-block;background:#131313;border-radius:5px;font-weight:normal;font-style:normal;line-height:19px;width:auto;text-align:center;border-top-width:10px;border-bottom-width:10px;">
                                                            {{$btn_name}}
                                                        </a>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endif
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