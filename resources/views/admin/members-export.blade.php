<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member records - Kamakhya Devalaya</title>
    <style>
        *{box-sizing:border-box}body{font:16px Arial,sans-serif;color:#263342;margin:32px}.export-heading{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px}.export-heading h1{font-size:27px;margin:0 0 7px}.export-heading p{margin:0;color:#687686;font-size:14px;line-height:1.45}.export-heading button{border:0;border-radius:6px;background:#a65436;color:#fff;padding:11px 16px;font-size:14px;cursor:pointer}table{border-collapse:collapse;width:100%;table-layout:fixed;font-size:14px}th{text-align:left;background:#f4f5f6;color:#526171;font-size:12px;letter-spacing:.04em;padding:11px 8px;border-bottom:1px solid #dfe3e7}td{padding:11px 8px;border-bottom:1px solid #e8eaed;vertical-align:middle;line-height:1.35;overflow-wrap:anywhere}th:nth-child(1),td:nth-child(1){width:8%}th:nth-child(2),td:nth-child(2){width:12%}th:nth-child(3),td:nth-child(3){width:28%}th:nth-child(4),td:nth-child(4){width:22%}th:nth-child(5),td:nth-child(5){width:15%}th:nth-child(6),td:nth-child(6){width:15%}.photo-cell{width:58px}.member-photo{display:block;width:48px;height:48px;object-fit:cover;border-radius:50%;background:#f1f2f4}.member-name{font-weight:bold}.member-email{display:block;color:#687686;font-size:12px;margin-top:4px;overflow-wrap:anywhere}.empty{text-align:center;padding:25px;color:#687686}@page{size:A4 portrait;margin:12mm}@media print{body{margin:0;font-size:14px}.export-heading{margin-bottom:18px}.export-heading h1{font-size:23px}table{font-size:12px}th{font-size:10px;padding:8px 6px}td{padding:9px 6px;line-height:1.3}.member-photo{width:40px;height:40px}.member-email{font-size:10px}.no-print{display:none}thead{display:table-header-group}tr{break-inside:avoid}}
    </style>
</head>
<body>
    <header class="export-heading">
        <div><h1>Member records</h1><p>Kamakhya Devalaya · {{ $members->count() }} {{ Str::plural('member', $members->count()) }}@if(request('q')) · Search: {{ request('q') }}@endif @if(request('role')) · Community role: {{ request('role') }}@endif</p></div>
        <button class="no-print" onclick="window.print()">Save as PDF</button>
    </header>
    <table>
        <thead><tr><th>SL NO</th><th>PHOTO</th><th>MEMBER</th><th>FATHER'S NAME</th><th>MOBILE</th><th>COMMUNITY ROLE</th></tr></thead>
        <tbody>
            @forelse($members as $member)
                <tr><td>{{ $member->sl_no }}</td><td class="photo-cell">@if($member->photo)<img class="member-photo" src="{{ Storage::url($member->photo) }}" alt="">@else<div class="member-photo" aria-label="No photo"></div>@endif</td><td><span class="member-name">{{ $member->name_assamese ?: $member->name }}{{ $member->name_assamese ? ' / '.$member->name : '' }}</span><span class="member-email">{{ $member->email ?: 'No email provided' }}</span></td><td>{{ $member->father_name_assamese ? $member->father_name_assamese.' / ' : '' }}{{ $member->father_name ?: '—' }}</td><td>{{ $member->mobile }}</td><td>{{ $member->role ?: '—' }}</td></tr>
            @empty
                <tr><td class="empty" colspan="6">No member records found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
