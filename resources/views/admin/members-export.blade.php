<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Member records - Kamakhya Devalaya</title>
    <style>
        body{font:14px Arial,sans-serif;color:#263342;margin:32px}.export-heading{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:22px}.export-heading h1{font-size:24px;margin:0 0 6px}.export-heading p{margin:0;color:#687686;font-size:12px}.export-heading button{border:0;border-radius:6px;background:#a65436;color:#fff;padding:10px 15px;font-size:12px;cursor:pointer}table{border-collapse:collapse;width:100%;font-size:11px}th{text-align:left;background:#f4f5f6;color:#687686;font-size:9px;letter-spacing:.08em;padding:10px;border-bottom:1px solid #dfe3e7}td{padding:8px 10px;border-bottom:1px solid #e8eaed;vertical-align:middle}.photo-cell{width:58px}.member-photo{display:block;width:40px;height:40px;object-fit:cover;border-radius:50%;background:#f1f2f4}.member-name{font-weight:bold}.member-email{display:block;color:#687686;font-size:10px;margin-top:3px}.empty{text-align:center;padding:25px;color:#687686}@page{size:landscape;margin:14mm}@media print{body{margin:0}.no-print{display:none}thead{display:table-header-group}tr{break-inside:avoid}}
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
                <tr><td>{{ $loop->iteration }}</td><td class="photo-cell">@if($member->photo)<img class="member-photo" src="{{ Storage::url($member->photo) }}" alt="">@else<div class="member-photo" aria-label="No photo"></div>@endif</td><td><span class="member-name">{{ $member->name_assamese ?: $member->name }}{{ $member->name_assamese ? ' / '.$member->name : '' }}</span><span class="member-email">{{ $member->email ?: 'No email provided' }}</span></td><td>{{ $member->father_name_assamese ? $member->father_name_assamese.' / ' : '' }}{{ $member->father_name ?: '—' }}</td><td>{{ $member->mobile }}</td><td>{{ $member->role ?: '—' }}</td></tr>
            @empty
                <tr><td class="empty" colspan="6">No member records found.</td></tr>
            @endforelse
        </tbody>
    </table>
    <script>window.addEventListener('load', () => window.print());</script>
</body>
</html>
