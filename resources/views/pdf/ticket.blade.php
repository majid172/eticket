<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <title>Jatri e-Ticket Receipt - {{ $booking->booking_reference }}</title>
    <style>
        @page {
            margin: 0.3in;
        }
        body {
            font-family: 'SolaimanLipi', 'Nirmala UI', Arial, sans-serif;
            font-size: 10px;
            color: #000;
            line-height: 1.2;
            margin: 0;
            padding: 0;
        }
        .header-bar {
            background-color: #000;
            color: #fff;
            padding: 10px 15px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .subheader {
            padding: 5px 15px;
            font-style: italic;
            border-bottom: 2px solid #000;
            font-size: 9px;
            margin-bottom: 15px;
        }
        .content {
            padding: 0 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .main-table th {
            background-color: #000;
            color: #fff;
            text-align: left;
            padding: 4px 8px;
            font-size: 9px;
            text-transform: uppercase;
        }
        .main-table td {
            padding: 8px;
            vertical-align: top;
            border-bottom: 1px solid #eee;
        }
        .city-name {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .detail-row {
            margin-bottom: 15px;
        }
        .detail-item {
            display: inline-block;
            width: 48%;
            vertical-align: top;
            margin-bottom: 5px;
        }
        .detail-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 100px;
        }
        .detail-value {
            font-weight: bold;
        }
        .boxes-container {
            width: 100%;
            margin-top: 20px;
            border-top: 1px solid #000;
            padding-top: 10px;
        }
        .box {
            display: inline-block;
            width: 45%;
            border: 1px solid #000;
            padding: 10px;
            vertical-align: top;
            min-height: 120px;
        }
        .box-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 10px;
            display: block;
            text-transform: uppercase;
        }
        .box-row {
            margin-bottom: 4px;
        }
        .box-row span:last-child {
            float: right;
            font-weight: bold;
        }
        .footer-note {
            margin-top: 20px;
            font-size: 9px;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .clear { clear: both; }
    </style>
</head>
<body>

<div class="header-bar">
    JATRI E-TICKETING RECEIPT
</div>
<div class="subheader">
    At check-in, please show a picture identification and the document you gave for reference at reservation time.
</div>

<div class="content">
    <table class="main-table">
        <thead>
            <tr>
                <th style="width: 25%">From</th>
                <th style="width: 25%">To</th>
                <th style="width: 20%">Coach/Bus</th>
                <th style="width: 15%">Departure</th>
                <th style="width: 15%">Arrival</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="city-name">{{ $booking->scheduleBus->schedule->route->departure_city }}</div>
                </td>
                <td>
                    <div class="city-name">{{ $booking->scheduleBus->schedule->route->arrival_city }}</div>
                </td>
                <td>
                    <div>{{ $booking->scheduleBus->bus->name ?? 'BUS-'.$booking->scheduleBus->id }}</div>
                    <div style="font-size: 9px; color: #666;">{{ $booking->scheduleBus->bus->registration_number ?? '' }}</div>
                </td>
                <td>
                    <div style="font-weight: bold;">{{ \Carbon\Carbon::parse($booking->scheduleBus->schedule->departure_time)->format('H:i') }}</div>
                    <div>{{ \Carbon\Carbon::parse($booking->scheduleBus->schedule->departure_time)->format('d M Y') }}</div>
                </td>
                <td>
                    <div style="font-weight: bold;">{{ \Carbon\Carbon::parse($booking->scheduleBus->schedule->arrival_time ?? $booking->scheduleBus->schedule->departure_time)->format('H:i') }}</div>
                    <div>{{ \Carbon\Carbon::parse($booking->scheduleBus->schedule->arrival_time ?? $booking->scheduleBus->schedule->departure_time)->format('d M Y') }}</div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="detail-row">
        <div class="detail-item">
            <span class="detail-label">Class:</span>
            <span class="detail-value">{{ $booking->scheduleBus->bus->is_ac ? 'Premium AC' : 'Economy' }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Operated by:</span>
            <span class="detail-value">{{ $booking->scheduleBus->bus->company->name }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Baggage (4):</span>
            <span class="detail-value">20KG Standard</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">Booking Status:</span>
            <span class="detail-value">{{ strtoupper($booking->booking_status) }}</span>
        </div>
        <div class="detail-item">
            <span class="detail-label">PNR Reference:</span>
            <span class="detail-value">{{ $booking->booking_reference }}</span>
        </div>
         <div class="detail-item">
            <span class="detail-label">Selected Seats:</span>
            <span class="detail-value">
                @foreach($booking->bookingSeats as $seat) [{{ $seat->seat_number }}] @endforeach
            </span>
        </div>
    </div>

    <div class="boxes-container">
        <div class="box" style="margin-right: 4%;">
            <span class="box-title">Payment Details</span>
            <div class="box-row">
                <span>PNR Reference:</span>
                <span>{{ $booking->booking_reference }}</span>
            </div>
            <div class="box-row">
                <span>Form of payment:</span>
                <span>{{ strtoupper($booking->payment_status) }}</span>
            </div>
             <div class="box-row">
                <span>Issued On:</span>
                <span>{{ \Carbon\Carbon::parse($booking->booked_at)->format('dM Y') }}</span>
            </div>
            <div style="margin-top: 10px; font-size: 8px; color: #666;">
                Endorsements: NONREF/ CHNG RESTRICTED
            </div>
        </div>

        <div class="box">
            <span class="box-title">Fare Details</span>
            <div class="box-row">
                <span>Base Fare:</span>
                <span>BDT {{ number_format($booking->total_amount, 2) }}</span>
            </div>
            <div class="box-row">
                <span>Taxes & Fees:</span>
                <span>BDT 0.00</span>
            </div>
            <div class="box-row" style="margin-top: 15px; padding-top: 5px; border-top: 1px dashed #ccc;">
                <span style="font-weight: bold;">Grand Total:</span>
                <span style="font-weight: bold;">BDT {{ number_format($booking->total_amount, 2) }}</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>

    <div class="footer-note">
        <strong>RECEIPT REMARKS</strong><br>
        The carriage of certain hazardous materials like: aerosols, fireworks and flammable liquids aboard the bus is strictly prohibited. 
        Jatri E-Ticketing System &copy; 2026. All rights reserved.
    </div>

    <!-- QR Code Section for Digital Verification -->
    <div style="margin-top: 20px; text-align: center;">
        <div style="font-size: 8px; margin-bottom: 5px;">Scan for digital validation</div>
        @if(class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode'))
            <img src="data:image/png;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(60)->generate($booking->booking_reference)) }}" alt="QR Code">
        @else
            <div style="border: 1px solid #ccc; padding: 10px; display: inline-block;">
                <strong>{{ $booking->booking_reference }}</strong>
            </div>
        @endif
    </div>
</div>

</body>
</html>
