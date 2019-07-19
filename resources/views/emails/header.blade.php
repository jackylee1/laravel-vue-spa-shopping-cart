<table class="es-header" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top;">
    <tr style="border-collapse:collapse;">
        <td align="center" style="padding:0;Margin:0;">
            <table class="es-header-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FEF5E4;">
                <tr style="border-collapse:collapse;">
                    <td align="left" bgcolor="#0b0b0b" style="Margin:0;padding-top:5px;padding-bottom:5px;padding-left:15px;padding-right:15px;background-color:#0B0B0B;">
                        <!--[if mso]><table  width="570" cellpadding="0" cellspacing="0"><tr><td width="180" valign="top"><![endif]-->
                        <table class="es-left" cellspacing="0" cellpadding="0" align="left" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;float:left;">
                            <tr style="border-collapse:collapse;">
                                <td class="es-m-p0r es-m-p20b" width="180" valign="top" align="center" style="padding:0;Margin:0;">
                                    <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                        <tr style="border-collapse:collapse;">
                                            <td class="es-m-p0l es-m-txt-c" align="left" style="padding:0;Margin:0;padding-top:10px;padding-left:15px;">
                                                <a href="{{url('/')}}" target="_blank" style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:14px;text-decoration:underline;color:#999999;">
                                                    <img src="{{asset('/assets/public/images/logo.png')}}"
                                                         alt="{{env('APP_NAME')}}"
                                                         title="{{env('APP_NAME')}}"
                                                         style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic;"
                                                         width="165"></a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        @if(isset($types) && $types->count() > 0)
                            @php
                                $header_types = $types->filter(function ($item) {
                                    return $item->show_on_header === 1;
                                });
                            @endphp

                            @if($header_types->count() > 0)
                            <!--[if mso]></td><td width="20"></td><td width="370" valign="top"><![endif]-->
                            <table cellspacing="0" cellpadding="0" align="right" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                <tr class="es-mobile-hidden" style="border-collapse:collapse;">
                                    <td width="370" align="left" style="padding:0;Margin:0;">
                                        <table width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                            <tr style="border-collapse:collapse;">
                                                <td style="padding:0;Margin:0;">
                                                    <table class="es-menu" width="100%" cellspacing="0" cellpadding="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;">
                                                        <tr class="links" style="border-collapse:collapse;">
                                                            @foreach($header_types as $type)
                                                            <td @if($loop->index === 0)
                                                                style="Margin:0;padding-left:5px;padding-right:5px;padding-top:20px;padding-bottom:20px;border:0;"
                                                                @else
                                                                style="Margin:0;padding-left:5px;padding-right:5px;padding-top:20px;padding-bottom:20px;border:0;border-left:0px solid #000000;"
                                                                @endif
                                                                id="esd-menu-id-{{$loop->index}}"
                                                                width="25.00%"
                                                                bgcolor="transparent"
                                                                align="center">
                                                                <a style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:16px;text-decoration:none;display:block;color:#EEEAEA;"
                                                                   href="{{url('/catalog?type=' . $type->slug)}}">
                                                                    {{$type->name}}
                                                                </a>
                                                            </td>
                                                            @endforeach
                                                        </tr>
                                                    </table>
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