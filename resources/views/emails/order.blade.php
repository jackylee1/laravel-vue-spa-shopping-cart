@extends('emails.layout')

@section('contents')
    @include('emails.header')

    @include('emails.text')

    @include('emails.information_order')

    <table class="es-content" cellspacing="0" cellpadding="0" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;">
        <tr style="border-collapse:collapse;">
            <td align="center" style="padding:0;Margin:0;">
                <table class="es-content-body" width="600" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;">
                    @include('emails.products')

                    @include('emails.total_prices')
                </table>
            </td>
        </tr>
    </table>

    @include('emails.footer')
@endsection