@php
  use Carbon\Carbon;
  $t   = $d['totals'];
  $c   = $d['campaign'];
  $adv = $d['advertiser'];
  $pop = $d['proof_of_play'];
  $fmt = fn ($v) => $v ? Carbon::parse($v)->format('j M Y') : '-';
  $secs = function ($s) { $s = (int) $s; return ($s >= 3600 ? intdiv($s, 3600) . 'h ' : '') . ($s >= 60 ? intdiv($s % 3600, 60) . 'm ' : '') . ($s % 60) . 's'; };
@endphp
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>{{ $invoice->number ?? 'Invoice' }}</title>
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11px; color: #222; }
    h4 { margin: 14px 0 6px; font-size: 13px; }
    .title { font-weight:700; font-size:20px; }
    .muted { color:#666; font-size:10px; }
    table { width:100%; border-collapse: collapse; margin-bottom:8px; }
    th, td { padding:5px 6px; border:1px solid #ddd; vertical-align: top; }
    th { background:#f3f4f6; text-align:left; }
    .noborder td { border:none; padding:2px 6px; }
    .text-right { text-align:right; }
    .fw-bold { font-weight:700; }
    .sub td { color:#666; font-size:10px; }
    .grp td { background:#f9fafb; font-weight:700; }
    .badge { padding:2px 6px; border-radius:3px; font-size:10px; font-weight:700; }
  </style>
</head>
<body>

  <table class="noborder">
    <tr>
      <td>
        <div class="title">Invoice {{ $invoice->number }}</div>
        <div class="muted">Issued {{ $fmt($invoice->date) }} &middot; Due {{ $fmt($invoice->due_date) }}</div>
      </td>
      <td class="text-right">
        @php
          $label = $t['overdue'] ? 'OVERDUE' : strtoupper(str_replace('_', ' ', $invoice->status ?? 'unpaid'));
        @endphp
        <span class="badge" style="border:1px solid #999;">{{ $label }}</span>
      </td>
    </tr>
  </table>

  <table class="noborder">
    <tr>
      <td style="width:50%">
        <strong>Billed to</strong><br>
        @if($adv)
          {{ $adv['name'] }}<br>
          <span class="muted">{{ $adv['email'] }}</span><br>
        @endif
        @foreach($d['customers'] as $cu)
          <br><strong>{{ $cu['name'] }}</strong>@if($cu['brand']) ({{ $cu['brand'] }})@endif<br>
          @if($cu['address'])<span class="muted">{{ $cu['address'] }}</span><br>@endif
          @if($cu['email'] || $cu['phone'])<span class="muted">{{ collect([$cu['email'], $cu['phone']])->filter()->implode(' · ') }}</span><br>@endif
          @if($cu['pic'])<span class="muted">Contact: {{ $cu['pic'] }}</span><br>@endif
        @endforeach
      </td>
      <td style="width:50%">
        @if($c)
          <strong>Campaign</strong><br>
          {{ $c['name'] }}<br>
          <span class="muted">
            {{ $fmt($c['start_date']) }} &rarr; {{ $fmt($c['end_date']) }}@if($c['days']) ({{ $c['days'] }} days)@endif<br>
            @if(count($c['cinema_chains'])) Chains: {{ implode(', ', $c['cinema_chains']) }}<br>@endif
            @if(count($c['locations'])) Locations: {{ collect($c['locations'])->pluck('name')->implode(', ') }}<br>@endif
            @if(count($c['slots'])) Slots: {{ implode(', ', $c['slots']) }}<br>@endif
            @if(count($c['hall_types'])) Hall types: {{ implode(', ', $c['hall_types']) }}<br>@endif
          </span>
        @endif
      </td>
    </tr>
  </table>

  <h4>Billing details</h4>
  @php
    $mx = fn ($v) => rtrim(rtrim(number_format((float) ($v ?? 1), 3, '.', ''), '0'), '.');
  @endphp
  <table>
    <thead>
      <tr>
        <th>Ad</th><th>Slot</th><th>Location</th>
        <th class="text-right">Plays</th>
        <th class="text-right">Base (RM)</th>
        <th class="text-right">Multiplier</th>
        <th class="text-right">Per play (RM)</th>
        <th class="text-right">Amount (RM)</th>
      </tr>
    </thead>
    <tbody>
      @forelse($invoice->lines as $line)
        @if($line->line_type === 'minimum')
          <tr>
            <td colspan="3">{{ $line->dcp_name }} - {{ $line->location_name }}</td>
            <td class="text-right">-</td><td class="text-right">-</td><td class="text-right">-</td><td class="text-right">-</td>
            <td class="text-right">{{ number_format($line->amount, 2) }}</td>
          </tr>
        @else
          @php $base = (float) $line->location_price + (float) $line->slot_price + (float) $line->hall_price; @endphp
          <tr>
            <td>{{ $line->dcp_name }}</td>
            <td>{{ $line->slot_name ?? '-' }}</td>
            <td>{{ $line->location_name }}</td>
            <td class="text-right">{{ number_format($line->played) }}</td>
            <td class="text-right">{{ number_format($base, 2) }}</td>
            <td class="text-right">x{{ $mx($line->multiplier) }}@if((float) $line->multiplier_raw > (float) $line->multiplier) (cap)@endif</td>
            <td class="text-right">{{ number_format($line->unit_price ?? $base, 2) }}</td>
            <td class="text-right">{{ number_format($line->amount, 2) }}</td>
          </tr>
          @if((float) $line->multiplier != 1.0)
            <tr>
              <td colspan="8" class="muted" style="font-size:10px;">
                Multiplier x{{ $mx($line->multiplier) }} = position x{{ $mx($line->multiplier_position) }}
                &middot; targeting x{{ $mx($line->multiplier_targeting) }}@if($line->targeting_level) ({{ $line->targeting_level }})@endif
                &middot; length x{{ $mx($line->multiplier_length) }}
                &middot; season x{{ $mx($line->multiplier_season) }}
                &middot; volume x{{ $mx($line->multiplier_volume) }}@if((float) $line->multiplier_raw > (float) $line->multiplier) &middot; capped from x{{ $mx($line->multiplier_raw) }}@endif
              </td>
            </tr>
          @endif
        @endif
      @empty
        <tr><td colspan="8">No detail available for this invoice.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="muted">Amount = plays &times; per-play price. Per-play price = (location + slot + hall types price) &times; multipliers (position, targeting, ad length, season, volume; combined multiplier capped). A minimum charge per cinema and per week may apply.</div>

  @if($pop && $pop['fetched'])
    <h4>Proof of play</h4>
    <div class="muted" style="margin-bottom:4px;">
      Confirmed by the cinema servers. Data up to {{ $pop['synced_until'] ? Carbon::parse($pop['synced_until'])->format('j M Y H:i') : 'unknown' }}.
    </div>
    <table>
      <thead>
        <tr>
          <th>Ad / location / day</th>
          <th class="text-right">Played</th>
          <th class="text-right">Incomplete</th>
          <th class="text-right">Failed</th>
          <th class="text-right">Duration</th>
        </tr>
      </thead>
      <tbody>
        @foreach($pop['dcps'] as $ad)
          <tr class="grp">
            <td>{{ $ad['name'] }}</td>
            <td class="text-right">{{ $ad['played'] }}</td>
            <td class="text-right">{{ $ad['incomplete'] }}</td>
            <td class="text-right">{{ $ad['failed'] }}</td>
            <td class="text-right">{{ $secs($ad['played_seconds']) }}</td>
          </tr>
          @foreach($ad['by_location'] as $loc)
            <tr>
              <td style="padding-left:16px;">{{ $loc['location'] }}</td>
              <td class="text-right">{{ $loc['played'] }}</td>
              <td class="text-right">{{ $loc['incomplete'] }}</td>
              <td class="text-right">{{ $loc['failed'] }}</td>
              <td class="text-right">{{ $secs($loc['played_seconds']) }}</td>
            </tr>
            @foreach($loc['by_day'] as $day)
              <tr class="sub">
                <td style="padding-left:30px;">{{ $fmt($day['date']) }}</td>
                <td class="text-right">{{ $day['played'] }}</td>
                <td class="text-right">{{ $day['incomplete'] }}</td>
                <td class="text-right">{{ $day['failed'] }}</td>
                <td></td>
              </tr>
            @endforeach
          @endforeach
        @endforeach
      </tbody>
    </table>
  @endif

  <h4>Summary</h4>
  <table style="width:50%; margin-left:50%;">
    <tr><td>Subtotal (RM)</td><td class="text-right">{{ number_format($t['subtotal'], 2) }}</td></tr>
    @if($t['discount'])
      <tr><td>Discount @if($t['discount_type'] === 'percent') ({{ rtrim(rtrim(number_format($t['discount_value'], 2), '0'), '.') }}%)@endif</td><td class="text-right">- {{ number_format($t['discount'], 2) }}</td></tr>
      <tr><td>Net subtotal (RM)</td><td class="text-right">{{ number_format($t['net'], 2) }}</td></tr>
    @endif
    <tr><td>Tax ({{ rtrim(rtrim(number_format($t['tax_rate'], 2), '0'), '.') }}%)</td><td class="text-right">{{ number_format($t['tax_amount'], 2) }}</td></tr>
    <tr><td class="fw-bold">Total (RM)</td><td class="text-right fw-bold">{{ number_format($t['total'], 2) }}</td></tr>
    <tr><td>Paid (RM)</td><td class="text-right">{{ number_format($t['paid'], 2) }}</td></tr>
    <tr><td class="fw-bold">Balance due (RM)</td><td class="text-right fw-bold">{{ number_format($t['remaining'], 2) }}</td></tr>
  </table>

</body>
</html>
