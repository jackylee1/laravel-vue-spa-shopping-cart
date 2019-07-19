<table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
    <tr style="border-collapse:collapse;">
    </tr>
    <tr style="border-collapse:collapse;">
        <td align="center" style="padding:0;Margin:0;">
            <table class="es-footer-body" width="600" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FEF5E4;">
                <tr style="border-collapse:collapse;">
                    <td align="left" bgcolor="#0b0b0b" style="padding:20px;Margin:0;background-color:#0B0B0B;">
                        <!--[if mso]><table width="560" cellpadding="0" cellspacing="0"><tr><td width="200" valign="top"><![endif]-->
                        <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                            <tr style="border-collapse:collapse;">
                                <td class="es-m-p0r es-m-p20b" width="200" valign="top" align="center" style="padding:0;Margin:0;">
                                    <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tr style="border-collapse:collapse;">
                                            <td class="es-m-p0l es-m-txt-c" align="left" style="padding:0;Margin:0;">
                                                <a href="{{url('/')}}" target="_blank">
                                                    <img src="{{asset('/assets/public/images/logo.png')}}"
                                                         alt="{{env('APP_NAME')}}"
                                                         title="{{env('APP_NAME')}}"
                                                         width="108"
                                                         style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;">
                                                </a>
                                            </td>
                                        </tr>
                                        @if(isset($address) && $address !== null)
                                        <tr style="border-collapse:collapse;">
                                            <td class="es-m-txt-c" align="left" style="padding:0;Margin:0;padding-bottom:5px;padding-top:10px;">
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#E9E0E0;">
                                                    {{$address}}
                                                </p>
                                            </td>
                                        </tr>
                                        @endif

                                        @if(isset($email) && $email !== null)
                                        <tr style="border-collapse:collapse;">
                                            <td align="left" class="es-m-txt-c" style="padding:0;Margin:0;padding-top:5px;">
                                                <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:14px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#FBF7F7;"><a target="_blank" href="tel:(073)0696097" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:14px;text-decoration:underline;color:#F9F7F7;">(073) 069 60 97</a>
                                                    <br>
                                                    <a target="_blank"
                                                           href="mailto:{{$email}}"
                                                           style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:14px;text-decoration:underline;color:#F9F7F7;">
                                                        {{$email}}
                                                    </a>
                                                </p>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @if(isset($types) && $types->count() > 0)
                            @php
                                $footer_types = $types->filter(function ($item) {
                                    return $item->show_on_footer === 1;
                                });
                            @endphp

                            @if($footer_types->count() > 0)
                                <!--[if mso]></td><td width="20"></td><td width="340" valign="top"><![endif]-->
                                <table cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                    <tr style="border-collapse:collapse;">
                                        <td width="340" align="left" style="padding:0;Margin:0;">
                                            <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                <tr style="border-collapse:collapse;">
                                                    <td align="center" class="es-m-txt-c" style="padding:0;Margin:0;padding-top:10px;">
                                                        <p style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-size:12px;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:18px;color:#333333;">
                                                            @foreach($footer_types as $type)
                                                                <a target="_blank"
                                                                   href="{{url('/catalog?type=' . $type->slug)}}"
                                                                   style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:12px;text-decoration:underline;color:#E7E3E3;line-height:18px;">
                                                                    {{$type->name}}
                                                                </a>
                                                                @if($loop->iteration !== $type->count())
                                                                    &nbsp;
                                                                @endif
                                                            @endforeach
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            @endif
                        @endif
                        <!--[if mso]></td></tr></table><![endif]-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>