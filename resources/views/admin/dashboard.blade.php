@extends('layouts.app')
@section('title','Admin dashboard')
@section('content')
<div class="admin-shell">
    <div class="admin-heading">
        <div><span class="section-kicker">OVERVIEW</span>
            <h1>Membership dashboard</h1>
            <p>Manage your community register and member details.</p>
        </div><a href="{{ route('admin.profile') }}" class="admin-user"><span
                class="user-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</span><span><b>{{ auth()->user()->name }}</b><small>Administrator</small></span></a>
    </div>
    @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
    <div class="stat-grid">
        <div class="stat-card surface"><span class="stat-icon">♧</span><small>TOTAL
                MEMBERS</small><b>{{ number_format($total) }}</b><span class="stat-foot">Registered community
                members</span></div>
        <div class="stat-card surface"><span class="stat-icon stat-icon-gold">✦</span><small>ADDED
                TODAY</small><b>{{ number_format($today) }}</b><span class="stat-foot">New registrations today</span>
        </div>
    </div>
    <section class="surface table-card">
        <div class="table-heading">
            <div>
                <h2>Member records</h2>
                <p>View, print, edit or remove a member entry.</p>
            </div>
            <div class="table-export-actions" aria-label="Member export options"><a class="button button-soft export-button" href="{{ route('admin.members.export.excel') }}"><span class="export-button-label">Excel</span><span class="export-button-detail">Download spreadsheet</span></a><a class="button button-soft export-button" href="{{ route('admin.members.export.pdf', request()->only('q', 'role')) }}" target="_blank" rel="noopener"><span class="export-button-label">All members PDF</span><span class="export-button-detail">Export matching records</span></a><form class="selected-export-form" id="selected-members-export" method="GET" action="{{ route('admin.members.export.pdf') }}" target="_blank"><input type="hidden" name="q" value="{{ request('q') }}"><input type="hidden" name="role" value="{{ request('role') }}"><input type="hidden" name="selected" value="1"><button class="button button-primary export-button" id="export-selected-button" type="submit" disabled><span class="export-button-label">Selected PDF <span class="export-count">(<span id="selected-member-count">0</span>)</span></span><span class="export-button-detail">Choose rows below</span></button></form></div>
        </div>
        <div class="table-tools">
            <form class="per-page-form" method="GET" action="{{ route('admin.dashboard') }}"><label
                    class="per-page-control">Show <select name="per_page" aria-label="Records per page"
                        onchange="this.form.submit()">
                        <option value="10" @selected($pageSize==='10' )>10</option>
                        <option value="50" @selected($pageSize==='50' )>50</option>
                        <option value="100" @selected($pageSize==='100' )>100</option>
                        <option value="all" @selected($pageSize==='all' )>All</option>
                    </select><span>records</span></label>@if(request('q'))<input type="hidden" name="q"
                    value="{{ request('q') }}">@endif @if(request('role'))<input type="hidden" name="role" value="{{ request('role') }}">@endif</form>
            <form class="search-form" method="GET" action="{{ route('admin.dashboard') }}"><input type="hidden"
                    name="per_page" value="{{ $pageSize }}">
                <div class="search-input-wrap"><input type="search" name="q" value="{{ request('q') }}"
                        placeholder="Search name or mobile" aria-label="Search by name or mobile">@if(request('q'))<a
                        class="clear-search" href="{{ route('admin.dashboard', ['per_page' => $pageSize]) }}"
                    aria-label="Clear search" title="Clear search">×</a>@endif</div><select name="role" aria-label="Filter by community role" onchange="this.form.submit()"><option value="">All community roles</option>@foreach($communityRoles as $communityRole)<option value="{{ $communityRole }}" @selected(request('role') === $communityRole)>{{ $communityRole }}</option>@endforeach</select><button
                    class="button button-soft" type="submit">Search</button>
            </form>
        </div>
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th><input type="checkbox" id="select-visible-members" aria-label="Select all members on this page"></th>
                        <th>SL NO</th>
                        <th>MEMBER</th>
                        <th>MOBILE</th>
                        <th>COMMUNITY ROLE</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>@forelse($members as $member)<tr>
                        <td><input type="checkbox" class="member-export-checkbox" name="ids[]" value="{{ $member->id }}" form="selected-members-export" aria-label="Select {{ $member->name }} for PDF export"></td>
                        <td>{{ $members->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="member-cell">@if($member->photo)<img src="{{ Storage::url($member->photo) }}"
                                    alt="">@else<span
                                    class="table-avatar">{{ strtoupper(substr($member->name,0,1)) }}</span>@endif<div>
                                    <b>{{ $member->name_assamese ?: $member->name }}</b><small>{{ $member->name_assamese ? $member->name : '' }}</small>@if($member->name_assamese)<small>{{ $member->email ?: 'No email provided' }}</small>@else<small>{{ $member->email ?: 'No email provided' }}</small>@endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $member->mobile }}</td>
                        <td><span class="role-pill">{{ $member->role ?: '—' }}</span></td>
                        <td>
                            <details class="actions-menu"><summary><span>Actions</span><svg aria-hidden="true" viewBox="0 0 16 16"><path d="m4 6 4 4 4-4"/></svg></summary><div class="actions-menu-panel"><a href="{{ route('admin.members.show',$member) }}"><span class="action-menu-icon">↗</span>View</a><a href="{{ route('admin.members.edit',$member) }}"><span class="action-menu-icon">✎</span>Edit</a><button type="button" class="delete-action" data-delete-form="delete-member-{{ $member->id }}"><span class="action-menu-icon">⌫</span>Delete</button></div></details>
                            <form id="delete-member-{{ $member->id }}" method="POST" action="{{ route('admin.members.destroy',$member) }}" hidden>@csrf @method('DELETE')</form>
                        </td>
                    </tr>@empty<tr>
                        <td colspan="6" class="empty-row">No member records found. @if(request('q'))Try a different
                            search.@endif</td>
                    </tr>@endforelse</tbody>
            </table>
        </div>
        @if($members->hasPages())
            @php
                $lastPage = $members->lastPage();
                $currentPage = $members->currentPage();
                $startPage = max(2, $currentPage - 1);
                $endPage = min($lastPage - 1, $currentPage + 1);
            @endphp
            <nav class="member-pagination" aria-label="Member records pages">
                <span class="pagination-summary">Showing {{ $members->firstItem() }}–{{ $members->lastItem() }} of {{ $members->total() }}</span>
                <div class="pagination-links">
                    @if($members->onFirstPage())<span class="pagination-disabled">Previous</span>@else<a href="{{ $members->previousPageUrl() }}">Previous</a>@endif
                    @if($currentPage === 1)<span class="pagination-current" aria-current="page">1</span>@else<a href="{{ $members->url(1) }}">1</a>@endif
                    @if($lastPage > 2)
                        @if($startPage > 2)<span class="pagination-ellipsis" aria-hidden="true">…</span>@endif
                        @foreach(range($startPage, $endPage) as $page)
                            @if($page === $currentPage)<span class="pagination-current" aria-current="page">{{ $page }}</span>@else<a href="{{ $members->url($page) }}">{{ $page }}</a>@endif
                        @endforeach
                        @if($endPage < $lastPage - 1)<span class="pagination-ellipsis" aria-hidden="true">…</span>@endif
                        @if($currentPage === $lastPage)<span class="pagination-current" aria-current="page">{{ $lastPage }}</span>@else<a href="{{ $members->url($lastPage) }}">{{ $lastPage }}</a>@endif
                    @endif
                    @if($members->hasMorePages())<a href="{{ $members->nextPageUrl() }}">Next</a>@else<span class="pagination-disabled">Next</span>@endif
                </div>
            </nav>
        @endif
    </section>
</div>
<dialog class="delete-modal" id="delete-member-modal" aria-labelledby="delete-modal-title"><div class="delete-modal-content"><h2 id="delete-modal-title">Delete member record?</h2><p>This will permanently remove the member and their stored documents.</p><div class="delete-modal-actions"><button type="button" class="button button-soft" data-close-delete>Cancel</button><button type="button" class="button button-danger" id="confirm-delete-member">Delete record</button></div></div></dialog>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const selectVisible = document.getElementById('select-visible-members');
    const memberCheckboxes = [...document.querySelectorAll('.member-export-checkbox')];
    const exportButton = document.getElementById('export-selected-button');
    const selectedCount = document.getElementById('selected-member-count');
    const updateSelection = () => {
        const count = memberCheckboxes.filter(checkbox => checkbox.checked).length;
        selectedCount.textContent = count;
        exportButton.disabled = count === 0;
        selectVisible.checked = memberCheckboxes.length > 0 && count === memberCheckboxes.length;
        selectVisible.indeterminate = count > 0 && count < memberCheckboxes.length;
    };
    selectVisible.addEventListener('change', () => {
        memberCheckboxes.forEach(checkbox => { checkbox.checked = selectVisible.checked; });
        updateSelection();
    });
    memberCheckboxes.forEach(checkbox => checkbox.addEventListener('change', updateSelection));

    const modal = document.getElementById('delete-member-modal');
    let formId = null;
    const menus = [...document.querySelectorAll('.actions-menu')];
    menus.forEach(menu => menu.addEventListener('toggle', () => {
        if (menu.open) {
            menus.forEach(other => { if (other !== menu) other.open = false; });
            requestAnimationFrame(() => {
                if (!menu.open) return;
                const summary = menu.querySelector('summary');
                const panel = menu.querySelector('.actions-menu-panel');
                const rect = summary.getBoundingClientRect();
                const panelRect = panel.getBoundingClientRect();
                const top = rect.bottom + panelRect.height + 6 > window.innerHeight ? rect.top - panelRect.height - 6 : rect.bottom + 6;
                panel.style.left = `${Math.max(8, Math.min(rect.right - panelRect.width, window.innerWidth - panelRect.width - 8))}px`;
                panel.style.top = `${Math.max(8, top)}px`;
            });
        }
    }));
    document.addEventListener('click', event => {
        menus.forEach(menu => { if (menu.open && !menu.contains(event.target)) menu.open = false; });
    });
    window.addEventListener('scroll', () => menus.forEach(menu => { menu.open = false; }), true);
    document.querySelectorAll('[data-delete-form]').forEach(button => button.addEventListener('click', () => {
        formId = button.dataset.deleteForm;
        modal.showModal();
    }));
    document.querySelector('[data-close-delete]').addEventListener('click', () => modal.close());
    document.getElementById('confirm-delete-member').addEventListener('click', () => document.getElementById(formId)?.requestSubmit());
    modal.addEventListener('click', event => { if (event.target === modal) modal.close(); });
});
</script>
@endsection
