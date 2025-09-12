<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>{{ $invoice->number ?? 'Invoice' }}</title>
  <style>
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color: #222; }
    .header { display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; }
    .title { font-weight:700; font-size:18px; }
    .muted { color:#666; font-size:11px; }
    table { width:100%; border-collapse: collapse; margin-bottom:10px; }
    th, td { padding:8px; border:1px solid #ddd; }
    .text-right { text-align:right; }
    .fw-bold { font-weight:700; }
    .small { font-size:11px; }
  </style>
</head>
<body>
  <div class="header">
    <div>
      <div class="title">Invoice: {{ $invoice->number_full ?? $invoice->invoice_no ?? $invoice->number }}</div>
      <div class="small muted">Advertiser: {{ optional($invoice->compaign->user)->name . " ". optional($invoice->compaign->user)->last_name ?? '-' }}</div>
      <div class="small muted">Campaign: {{ $invoice->compaign->name ?? '-' }}</div>
    </div>
    <div class="text-right">
      <div class="small muted">Invoice Date: {{ optional($invoice->date)->format('M j, Y') ?? optional($invoice->created_at)->format('M j, Y') }}</div>
    </div>
  </div>

  <!-- CPM Breakdown (statique as requested) -->
  <h4>💰 CPM Breakdown</h4>
  <table>
    <tbody>
      <tr><th>Description</th><th>Value</th></tr>
      <tr><td>Base CPM</td><td class="text-right">RM 30.00</td></tr>
      <tr><td>Targeting Surcharge</td><td class="text-right">RM 12.00</td></tr>
      <tr><td>Slot Multiplier (x2)</td><td class="text-right">RM 84.00</td></tr>
      <tr><td>Impressions</td><td class="text-right">53,000</td></tr>
      <tr><td class="fw-bold">Gross Cost</td><td class="text-right fw-bold">RM 4,452.00</td></tr>
    </tbody>
  </table>

  <!-- Proof of Play (statique) -->
  <h4>🎬 Proof of Play</h4>
  <table>
    <thead>
      <tr><th>Date</th><th>Time</th><th>Cinema</th><th>Film</th><th>Slot</th></tr>
    </thead>
    <tbody>
      <tr><td>Apr 5</td><td>19:30</td><td>KLCC</td><td>Kung Fu Panda 4</td><td>Slot A</td></tr>
      <tr><td>Apr 6</td><td>21:45</td><td>Sunway Pyramid</td><td>Inside Out 2</td><td>Slot A</td></tr>
    </tbody>
  </table>

  <!-- Totals (données depuis $invoice si dispo) -->
  <table>
    <tr>
      <td class="small">Subtotal (RM)</td>
      <td class="text-right">{{ number_format($invoice->subtotal ?? $invoice->total_ht ?? 0, 2) }}</td>
    </tr>
    <tr>
      <td class="small">Tax</td>
      <td class="text-right">{{ number_format($invoice->tax ?? 0, 2) }} % </td>
    </tr>
    <tr>
      <td class="fw-bold">Total (RM)</td>
      <td class="text-right fw-bold">{{ number_format($invoice->total ?? $invoice->total_ttc ?? ($invoice->subtotal + $invoice->tax ?? 0), 2) }}</td>
    </tr>
  </table>

</body>
</html>
