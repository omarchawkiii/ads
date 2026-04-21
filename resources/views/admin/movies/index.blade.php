@extends('admin.layouts.app')
@section('title') Movies @endsection
@section('content')
<div class="">

    <div class="card card-body py-3 mb-3">
        <div class="d-sm-flex align-items-center justify-space-between">
            <h4 class="mb-0 card-title">Movies Management</h4>
            <div class="ms-auto d-flex gap-2">
                <button class="btn btn-outline-info btn-sm" id="btn-sync-movies">
                    <i class="mdi mdi-sync"></i> Sync from NOC
                </button>
                <button class="btn btn-success btn-sm" id="btn-create-master-movie">
                    <i class="mdi mdi-plus"></i> New Master Movie
                </button>
                <button class="btn btn-primary btn-sm" id="create_movie">
                    <i class="mdi mdi-plus"></i> New Movie
                </button>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="movieTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#tab-master">
                        <i class="mdi mdi-movie-roll"></i> Master Movies
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-linked">
                        <i class="mdi mdi-link-variant"></i> Linked Movies
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#tab-unlinked">
                        <i class="mdi mdi-link-variant-off"></i> Unlinked Movies
                    </a>
                </li>
            </ul>

            <div class="tab-content">

                {{-- ===== TAB 1: MASTER MOVIES ===== --}}
                <div class="tab-pane fade show active" id="tab-master">
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="mdi mdi-magnify fs-5"></i></span>
                            <input type="text" class="form-control border-start-0" id="master-search" placeholder="Search movies...">
                        </div>
                    </div>
                    <div class="row g-3" id="master-cards-container">
                        <div class="col-12 text-center text-muted py-4"><i class="mdi mdi-loading mdi-spin fs-3"></i></div>
                    </div>
                    <nav class="mt-3" id="master-pagination"></nav>
                </div>

                {{-- ===== TAB 2: LINKED MOVIES ===== --}}
                <div class="tab-pane fade" id="tab-linked">
                    <div class="table-responsive">
                        <table id="linked-movies-table" class="table table-striped table-bordered display text-nowrap dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Movie Name</th>
                                    <th>Cinema Chain</th>
                                    <th>Genre</th>
                                    <th>Language</th>
                                    <th>Runtime</th>
                                    <th>Master Movie</th>
                                    <th style="width:120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                {{-- ===== TAB 3: UNLINKED MOVIES ===== --}}
                <div class="tab-pane fade" id="tab-unlinked">
                    <div class="table-responsive">
                        <table id="unlinked-movies-table" class="table table-striped table-bordered display text-nowrap dataTable">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Movie Name</th>
                                    <th>Cinema Chain</th>
                                    <th>Genre</th>
                                    <th>Language</th>
                                    <th>Runtime</th>
                                    <th style="width:120px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ===================== MODALS ===================== --}}

{{-- Create Movie Modal --}}
<div class="modal" id="create_movie_modal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="create_movie_form">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">New Movie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="c_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Genre <span class="text-danger">*</span></label>
                        <select class="form-select" id="c_movie_genre" required>
                            <option value="">-- Select Genre --</option>
                            @foreach($genres ?? [] as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Language <span class="text-danger">*</span></label>
                        <select class="form-select" id="c_langue_id" required>
                            <option value="">-- Select Language --</option>
                            @foreach($langues ?? [] as $lang)
                                <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cinema Chain</label>
                        <select class="form-select" id="c_cinema_chain_id">
                            <option value="">-- None --</option>
                            @foreach($cinemaChains ?? [] as $cc)
                                <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Runtime (min)</label>
                        <input type="number" class="form-control" id="c_runtime" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Movie Modal --}}
<div class="modal" id="edit_movie_modal" tabindex="-1">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <form id="edit_movie_form">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Edit Movie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="e_id">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="e_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Genre <span class="text-danger">*</span></label>
                        <select class="form-select" id="e_movie_genre" required>
                            <option value="">-- Select Genre --</option>
                            @foreach($genres ?? [] as $genre)
                                <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Language <span class="text-danger">*</span></label>
                        <select class="form-select" id="e_langue_id" required>
                            <option value="">-- Select Language --</option>
                            @foreach($langues ?? [] as $lang)
                                <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cinema Chain</label>
                        <select class="form-select" id="e_cinema_chain_id">
                            <option value="">-- None --</option>
                            @foreach($cinemaChains ?? [] as $cc)
                                <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Runtime (min)</label>
                        <input type="number" class="form-control" id="e_runtime" min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="e_status">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Create Master Movie Modal --}}
<div class="modal" id="create_master_modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="create_master_form" enctype="multipart/form-data">
                <div class="modal-header bg-success">
                    <h5 class="modal-title text-white">New Master Movie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cm_title" required>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Year</label>
                                    <input type="text" class="form-control" id="cm_year" maxlength="10">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Rating</label>
                                    <input type="text" class="form-control" id="cm_rating" maxlength="20" placeholder="PG-13, R...">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Runtime (min)</label>
                                    <input type="number" class="form-control" id="cm_runtime" min="1">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Genre(s)</label>
                                <select class="form-select" id="cm_genres" multiple>
                                    @foreach($genres ?? [] as $genre)
                                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Plot</label>
                                <textarea class="form-control" id="cm_plot" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Poster Image</label>
                            <div id="cm_image_preview" class="mb-2 text-center">
                                <img id="cm_preview_img" src="" alt="" class="img-fluid rounded d-none" style="max-height:200px;">
                                <div id="cm_no_image" class="text-muted py-4 border rounded text-center">
                                    <i class="mdi mdi-image-outline" style="font-size:2rem;"></i><br>No image
                                </div>
                            </div>
                            <input type="file" class="form-control" id="cm_image" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Master Movie Modal --}}
<div class="modal" id="edit_master_modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="edit_master_form" enctype="multipart/form-data">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit Master Movie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="em_id">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="em_title" required>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Year</label>
                                    <input type="text" class="form-control" id="em_year" maxlength="10">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Rating</label>
                                    <input type="text" class="form-control" id="em_rating" maxlength="20">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Runtime (min)</label>
                                    <input type="number" class="form-control" id="em_runtime" min="1">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Genre(s)</label>
                                <select class="form-select" id="em_genres" multiple>
                                    @foreach($genres ?? [] as $genre)
                                        <option value="{{ $genre->id }}">{{ $genre->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Plot</label>
                                <textarea class="form-control" id="em_plot" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Poster Image</label>
                            <div class="mb-2 text-center">
                                <img id="em_preview_img" src="" alt="" class="img-fluid rounded mb-2" style="max-height:200px;">
                            </div>
                            <input type="file" class="form-control" id="em_image" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Link Movie Modal --}}
<div class="modal" id="link_movie_modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white">Link to Master Movie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="lm_movie_id">
                <p class="mb-2">Movie: <strong id="lm_movie_name"></strong></p>
                <div class="mb-3">
                    <label class="form-label">Select Master Movie <span class="text-danger">*</span></label>
                    <select class="form-select" id="lm_master_movie_id">
                        <option value="">-- Select --</option>
                        @foreach($masterMovies ?? [] as $mm)
                            <option value="{{ $mm->id }}">{{ $mm->title }} {{ $mm->year ? '('.$mm->year.')' : '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white" id="btn-confirm-link">Link</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_script')
<link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
<style>
/* ── Master Movie Card ── */
.mm-card {
    border-radius: 12px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    transition: box-shadow .2s;
}
.mm-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.18); }
.mm-card-body  { padding: 14px 14px 10px; flex: 1; }
.mm-card-body .d-flex { gap: 12px; }
.mm-title      { font-weight: 700; font-size: 14px; margin-bottom: 5px;
                 white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mm-subtitle   { font-size: 12px; margin-bottom: 10px; }
.mm-footer     { border-top: 1px solid; padding: 8px 14px;
                 display: flex; justify-content: space-between; align-items: center; }
.mm-footer-link { font-size: 12px; text-decoration: none; }
.mm-footer-link:hover { text-decoration: underline; }
.mm-action-btn { background: none; border: none; padding: 2px 4px; cursor: pointer; font-size: 18px; }
.mm-badge      { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; margin-right: 4px; }
.mm-badge-rating { padding: 1px 7px; font-weight: 600; }

/* DARK theme */
[data-bs-theme="dark"] .mm-card          { background: #1a2035; border: 1px solid rgba(255,255,255,.07); }
[data-bs-theme="dark"] .mm-title         { color: #e2e8f0; }
[data-bs-theme="dark"] .mm-subtitle      { color: #4fc3f7; }
[data-bs-theme="dark"] .mm-footer        { border-color: rgba(255,255,255,.07); background: transparent; }
[data-bs-theme="dark"] .mm-footer-link   { color: #6b7a99; }
[data-bs-theme="dark"] .mm-action-btn    { color: #6b7a99; }
[data-bs-theme="dark"] .mm-action-btn:hover { color: #cbd5e1; }
[data-bs-theme="dark"] .mm-badge-rating  { background: #3a4060; color: #c8d2e6; }
[data-bs-theme="dark"] .mm-badge-map     { background: #1e3a5f; color: #60a5fa; }
[data-bs-theme="dark"] .mm-badge-noc     { background: #431407; color: #fb923c; }
[data-bs-theme="dark"] .mm-placeholder   { background: rgba(255,255,255,.08); color: #8897aa; }

/* LIGHT theme */
[data-bs-theme="light"] .mm-card         { background: #ffffff; border: 1px solid rgba(0,0,0,.1); box-shadow: 0 1px 6px rgba(0,0,0,.07); }
[data-bs-theme="light"] .mm-title        { color: #1a202c; }
[data-bs-theme="light"] .mm-subtitle     { color: #0284c7; }
[data-bs-theme="light"] .mm-footer       { border-color: rgba(0,0,0,.09); background: #f8f9fa; }
[data-bs-theme="light"] .mm-footer-link  { color: #6b7280; }
[data-bs-theme="light"] .mm-action-btn   { color: #9ca3af; }
[data-bs-theme="light"] .mm-action-btn:hover { color: #374151; }
[data-bs-theme="light"] .mm-badge-rating { background: #e2e8f0; color: #4a5568; }
[data-bs-theme="light"] .mm-badge-map    { background: #dbeafe; color: #1d4ed8; }
[data-bs-theme="light"] .mm-badge-noc    { background: #fed7aa; color: #c2410c; }
[data-bs-theme="light"] .mm-placeholder  { background: #e5e7eb; color: #9ca3af; }
</style>
<script src="{{ asset('assets/js/helper.js') }}?v={{ filemtime(public_path('assets/js/helper.js')) }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
<script>
$(function () {
    var BASE = "{{ url('') }}";
    var CSRF = "{{ csrf_token() }}";

    // Select2 for genre multi-selects
    $('#cm_genres').select2({ placeholder: '-- Select Genre(s) --', width: '100%', dropdownParent: $('#create_master_modal') });
    $('#em_genres').select2({ placeholder: '-- Select Genre(s) --', width: '100%', dropdownParent: $('#edit_master_modal') });

    // =============================================
    //  HELPERS
    // =============================================
    function dtDestroy(id) { if ($.fn.DataTable.isDataTable('#' + id)) $('#' + id).DataTable().destroy(); }
    function dtInit(id) {
        $('#' + id).DataTable({ iDisplayLength: 10, destroy: true, language: { search: '_INPUT_', searchPlaceholder: 'Search...' } });
    }

    var SPIN = '<i class="mdi mdi-loading mdi-spin"></i>';

    function btnLoad($btn) {
        $btn.prop('disabled', true).data('orig-html', $btn.html()).html(SPIN + ' Loading...');
    }
    function btnReset($btn) {
        $btn.prop('disabled', false).html($btn.data('orig-html'));
    }
    function tableLoading(tableId) {
        var cols = $('#' + tableId + ' thead th').length;
        $('#' + tableId + ' tbody').html(
            '<tr><td colspan="' + cols + '" class="text-center py-4 text-muted">' + SPIN + ' Loading...</td></tr>'
        );
    }
    function modalLoading($modal) {
        $modal.find('.modal-body').css('position', 'relative')
            .prepend('<div id="modal-overlay" style="position:absolute;inset:0;background:rgba(0,0,0,.45);z-index:10;display:flex;align-items:center;justify-content:center;border-radius:4px;"><span class="text-white fs-4">' + SPIN + '</span></div>');
    }
    function modalLoaded($modal) {
        $modal.find('#modal-overlay').remove();
    }

    // =============================================
    //  TAB SWITCHING — lazy load
    // =============================================
    $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr('href');
        if (target === '#tab-master')   get_master_movies();
        if (target === '#tab-linked')   get_linked();
        if (target === '#tab-unlinked') get_unlinked();
    });

    // =============================================
    //  HELPERS — refresh link dropdown
    // =============================================
    function refresh_master_dropdown() {
        $.get(BASE + '/master-movies/list', function (res) {
            var opts = '<option value="">-- Select --</option>';
            $.each(res.masterMovies, function (i, m) {
                opts += '<option value="' + m.id + '">' + m.title + (m.year ? ' (' + m.year + ')' : '') + '</option>';
            });
            $('#lm_master_movie_id').html(opts);
        });
    }

    // =============================================
    //  TAB 1 — MASTER MOVIES (card grid)
    // =============================================
    var _allMasters = [];
    var _masterPage = 1;
    var _masterPerPage = 20;
    var STORAGE_URL = "{{ rtrim(Storage::disk('public')->url(''), '/') }}";

    function renderMasterCard(m) {
        var img = m.image
            ? '<img src="' + STORAGE_URL + '/' + m.image + '" alt="" style="width:60px;height:80px;object-fit:cover;border-radius:8px;flex-shrink:0;">'
            : '<div class="mm-placeholder" style="width:60px;height:80px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:11px;flex-shrink:0;">100 × 150</div>';

        var genres = m.genres.map(function(g){ return g.name; }).join('/') || '';
        var parts = [];
        if (m.year)    parts.push(m.year);
        if (genres)    parts.push(genres);
        if (m.runtime) parts.push(m.runtime + ' min');
        var subtitle = parts.join(' • ');

        var ratingBadge = m.rating
            ? '<span class="mm-badge mm-badge-rating">' + m.rating + ' <i class="mdi mdi-star me-1 text-warning"></i></span>'
            : '';
        var mappingsBadge = '<span class="mm-badge mm-badge-map">'
            + '<i class="mdi mdi-link-variant me-1"></i>' + (m.movies_count || 0) + ' mapping' + (m.movies_count !== 1 ? 's' : '')
            + '</span>';
        var nocBadge = (m.noc_count > 0)
            ? '<span class="mm-badge mm-badge-noc">'
              + '<i class="mdi mdi-television-play me-1"></i>' + m.noc_count + ' NOC' + (m.noc_count !== 1 ? 's' : '')
              + '</span>'
            : '';

        return '<div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 mb-1">'
            + '<div class="mm-card">'
            + '<div class="mm-card-body">'
            + '<div class="d-flex">'
            + img
            + '<div style="min-width:0;padding-left:12px;">'
            + '<div class="mm-title" title="' + m.title + '">' + m.title + '</div>'
            + '<div class="mm-subtitle">' + subtitle + '</div>'
            + '<div>' + ratingBadge + mappingsBadge + nocBadge + '</div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="mm-footer">'
            + '<a href="#" class="mm-footer-link btn-manage-mappings" data-id="' + m.id + '">Click to manage mappings</a>'
            + '<div style="display:flex;gap:4px;">'
            + '<button data-id="' + m.id + '" class="mm-action-btn btn-edit-master" title="Edit"><i class="mdi mdi-pencil text-warning"></i></button>'
            + '<button data-id="' + m.id + '" class="mm-action-btn btn-delete-master" title="Delete"><i class="mdi mdi-delete text-danger"></i></button>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>';
    }

    function renderMasterPage() {
        var search = $('#master-search').val().toLowerCase().trim();
        var filtered = _allMasters.filter(function(m) {
            return !search || m.title.toLowerCase().indexOf(search) !== -1
                || (m.year && m.year.indexOf(search) !== -1)
                || m.genres.some(function(g){ return g.name.toLowerCase().indexOf(search) !== -1; });
        });

        var total = filtered.length;
        var pages = Math.max(1, Math.ceil(total / _masterPerPage));
        if (_masterPage > pages) _masterPage = 1;
        var start = (_masterPage - 1) * _masterPerPage;
        var slice = filtered.slice(start, start + _masterPerPage);

        if (slice.length === 0) {
            $('#master-cards-container').html('<div class="col-12 text-center text-muted py-4">No master movies found.</div>');
        } else {
            $('#master-cards-container').html(slice.map(renderMasterCard).join(''));
        }

        // Pagination
        var pag = '';
        if (pages > 1) {
            pag += '<ul class="pagination pagination-sm mb-0">';
            pag += '<li class="page-item' + (_masterPage === 1 ? ' disabled' : '') + '"><a class="page-link" href="#" data-page="' + (_masterPage - 1) + '">&laquo;</a></li>';
            for (var p = 1; p <= pages; p++) {
                pag += '<li class="page-item' + (p === _masterPage ? ' active' : '') + '"><a class="page-link" href="#" data-page="' + p + '">' + p + '</a></li>';
            }
            pag += '<li class="page-item' + (_masterPage === pages ? ' disabled' : '') + '"><a class="page-link" href="#" data-page="' + (_masterPage + 1) + '">&raquo;</a></li>';
            pag += '</ul>';
        }
        $('#master-pagination').html(pag);
    }

    function get_master_movies() {
        $('#master-cards-container').html('<div class="col-12 text-center text-muted py-4"><i class="mdi mdi-loading mdi-spin fs-3"></i></div>');
        $.get(BASE + '/master-movies/list')
            .done(function (res) {
                _allMasters = res.masterMovies;
                _masterPage = 1;
                renderMasterPage();
            });
    }
    get_master_movies();

    // Pagination click
    $(document).on('click', '#master-pagination .page-link', function (e) {
        e.preventDefault();
        var page = parseInt($(this).data('page'));
        if (!isNaN(page) && page >= 1) { _masterPage = page; renderMasterPage(); }
    });

    // Search
    $('#master-search').on('input', function () { _masterPage = 1; renderMasterPage(); });

    // Manage mappings → switch to Linked tab
    $(document).on('click', '.btn-manage-mappings', function (e) {
        e.preventDefault();
        $('a[href="#tab-linked"]').tab('show');
    });

    // Open create master modal
    $(document).on('click', '#btn-create-master-movie', function () {
        $('#create_master_form')[0].reset();
        $('#cm_genres').val(null).trigger('change');
        $('#cm_preview_img').addClass('d-none').attr('src', '');
        $('#cm_no_image').removeClass('d-none');
        $('#create_master_modal').modal('show');
    });

    // Image preview (create)
    $('#cm_image').on('change', function () {
        previewImage(this, '#cm_preview_img', '#cm_no_image');
    });
    $('#em_image').on('change', function () {
        var reader = new FileReader();
        reader.onload = function (e) { $('#em_preview_img').attr('src', e.target.result); };
        reader.readAsDataURL(this.files[0]);
    });

    function previewImage(input, imgSel, noImgSel) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(imgSel).attr('src', e.target.result).removeClass('d-none');
                $(noImgSel).addClass('d-none');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Submit create master
    $('#create_master_form').on('submit', function (e) {
        e.preventDefault();
        var $btn = $(this).find('[type=submit]');
        btnLoad($btn); modalLoading($('#create_master_modal'));
        var fd = buildMasterFormData('#cm_title', '#cm_year', '#cm_rating', '#cm_runtime', '#cm_plot', '#cm_genres', '#cm_image');
        fd.append('_token', CSRF);
        $.ajax({ url: BASE + '/master-movies', method: 'POST', data: fd, processData: false, contentType: false })
            .done(function () {
                $('#create_master_modal').modal('hide');
                get_master_movies(); get_unlinked(); get_linked(); refresh_master_dropdown();
                Swal.fire('Done!', 'Master movie created.', 'success');
            })
            .fail(function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Operation failed.', 'error');
            })
            .always(function () { btnReset($btn); modalLoaded($('#create_master_modal')); });
    });

    // Edit master movie — load data
    $(document).on('click', '.btn-edit-master', function () {
        var id = $(this).data('id');
        var $modal = $('#edit_master_modal');
        $modal.modal('show');
        modalLoading($modal);
        $.get(BASE + '/master-movies/' + id + '/show')
            .done(function (res) {
                var m = res.masterMovie;
                $('#em_id').val(m.id);
                $('#em_title').val(m.title);
                $('#em_year').val(m.year);
                $('#em_rating').val(m.rating);
                $('#em_runtime').val(m.runtime);
                $('#em_plot').val(m.plot);
                $('#em_genres').val(m.genres.map(g => g.id)).trigger('change');
                $('#em_preview_img').attr('src', m.image ? STORAGE_URL + '/' + m.image : '').toggleClass('d-none', !m.image);
            })
            .always(function () { modalLoaded($modal); });
    });

    // Submit edit master
    $('#edit_master_form').on('submit', function (e) {
        e.preventDefault();
        var $btn = $(this).find('[type=submit]');
        btnLoad($btn); modalLoading($('#edit_master_modal'));
        var id = $('#em_id').val();
        var fd = buildMasterFormData('#em_title', '#em_year', '#em_rating', '#em_runtime', '#em_plot', '#em_genres', '#em_image');
        fd.append('_token', CSRF);
        $.ajax({ url: BASE + '/master-movies/' + id + '/update', method: 'POST', data: fd, processData: false, contentType: false })
            .done(function () {
                $('#edit_master_modal').modal('hide');
                get_master_movies(); get_linked(); refresh_master_dropdown();
                Swal.fire('Done!', 'Master movie updated.', 'success');
            })
            .fail(function (xhr) { Swal.fire('Error', xhr.responseJSON?.message || 'Operation failed.', 'error'); })
            .always(function () { btnReset($btn); modalLoaded($('#edit_master_modal')); });
    });

    // Delete master movie
    $(document).on('click', '.btn-delete-master', function () {
        var id = $(this).data('id');
        Swal.fire({ title: 'Delete master movie?', text: 'All linked movies will be unlinked.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, delete' })
            .then(function (r) {
                if (!r.isConfirmed) return;
                $.ajax({ url: BASE + '/master-movies/' + id, method: 'POST', data: { _method: 'DELETE', _token: CSRF } })
                    .done(function () {
                        get_master_movies();
                        get_unlinked();
                        refresh_master_dropdown();
                        Swal.fire('Deleted!', '', 'success');
                    })
                    .fail(function () { Swal.fire('Error', 'Operation failed.', 'error'); });
            });
    });

    function buildMasterFormData(title, year, rating, runtime, plot, genres, image) {
        var fd = new FormData();
        fd.append('title',   $(title).val());
        fd.append('year',    $(year).val());
        fd.append('rating',  $(rating).val());
        fd.append('runtime', $(runtime).val());
        fd.append('plot',    $(plot).val());
        var genreVals = $(genres).val() || [];
        genreVals.forEach(function (v) { fd.append('movie_genre_ids[]', v); });
        var file = $(image)[0].files[0];
        if (file) fd.append('image', file);
        return fd;
    }

    // =============================================
    //  TAB 2 — LINKED MOVIES
    // =============================================
    function get_linked() {
        dtDestroy('linked-movies-table');
        tableLoading('linked-movies-table');
        $.get(BASE + '/master-movies/linked')
            .done(function (res) {
                var rows = '';
                $.each(res.movies, function (i, m) {
                    var chain = m.cinema_chain ? '<span class="badge bg-secondary">' + m.cinema_chain.name + '</span>' : '—';
                    rows += '<tr class="text-center">'
                        + '<td class="align-middle">' + (i + 1) + '</td>'
                        + '<td class="align-middle text-start">' + m.name + '</td>'
                        + '<td class="align-middle">' + chain + '</td>'
                        + '<td class="align-middle">' + (m.genre?.name || '—') + '</td>'
                        + '<td class="align-middle">' + (m.langue?.name || '—') + '</td>'
                        + '<td class="align-middle">' + (m.runtime ? m.runtime + ' min' : '—') + '</td>'
                        + '<td class="align-middle"><span class="badge bg-success">' + (m.master_movie?.title || '—') + '</span></td>'
                        + '<td class="align-middle">'
                        + '<button data-id="' + m.id + '" class="btn btn-sm btn-danger btn-unlink m-1" title="Unlink"><i class="mdi mdi-link-variant-off"></i> Unlink</button>'
                        + '</td></tr>';
                });
                $('#linked-movies-table tbody').html(rows);
                dtInit('linked-movies-table');
            });
    }

    $(document).on('click', '.btn-unlink', function () {
        var id = $(this).data('id');
        Swal.fire({ title: 'Unlink movie?', icon: 'question', showCancelButton: true, confirmButtonText: 'Yes, unlink' })
            .then(function (r) {
                if (!r.isConfirmed) return;
                $.ajax({ url: BASE + '/master-movies/unlink/' + id, method: 'POST', data: { _method: 'DELETE', _token: CSRF } })
                    .done(function () { get_linked(); get_unlinked(); Swal.fire('Done!', 'Movie unlinked.', 'success'); })
                    .fail(function () { Swal.fire('Error', 'Operation failed.', 'error'); });
            });
    });

    // =============================================
    //  TAB 3 — UNLINKED MOVIES
    // =============================================
    function get_unlinked() {
        dtDestroy('unlinked-movies-table');
        tableLoading('unlinked-movies-table');
        $.get(BASE + '/master-movies/unlinked')
            .done(function (res) {
                var rows = '';
                $.each(res.movies, function (i, m) {
                    var chain = m.cinema_chain ? '<span class="badge bg-secondary">' + m.cinema_chain.name + '</span>' : '—';
                    rows += '<tr class="text-center">'
                        + '<td class="align-middle">' + (i + 1) + '</td>'
                        + '<td class="align-middle text-start">' + m.name + '</td>'
                        + '<td class="align-middle">' + chain + '</td>'
                        + '<td class="align-middle">' + (m.genre?.name || '—') + '</td>'
                        + '<td class="align-middle">' + (m.langue?.name || '—') + '</td>'
                        + '<td class="align-middle">' + (m.runtime ? m.runtime + ' min' : '—') + '</td>'
                        + '<td class="align-middle">'
                        + '<button data-id="' + m.id + '" data-name="' + m.name + '" class="btn btn-sm btn-info text-white btn-link-movie m-1" title="Link"><i class="mdi mdi-link-variant"></i> Link</button>'
                        + '</td></tr>';
                });
                $('#unlinked-movies-table tbody').html(rows);
                dtInit('unlinked-movies-table');
            });
    }

    $(document).on('click', '.btn-link-movie', function () {
        $('#lm_movie_id').val($(this).data('id'));
        $('#lm_movie_name').text($(this).data('name'));
        $('#lm_master_movie_id').val('');
        $('#link_movie_modal').modal('show');
    });

    $('#btn-confirm-link').on('click', function () {
        var movieId = $('#lm_movie_id').val();
        var masterId = $('#lm_master_movie_id').val();
        if (!masterId) { Swal.fire('Warning', 'Please select a master movie.', 'warning'); return; }
        $.ajax({ url: BASE + '/master-movies/link', method: 'POST', data: { _token: CSRF, movie_id: movieId, master_movie_id: masterId } })
            .done(function () {
                $('#link_movie_modal').modal('hide');
                get_unlinked(); get_linked();
                Swal.fire('Done!', 'Movie linked successfully.', 'success');
            })
            .fail(function () { Swal.fire('Error', 'Operation failed.', 'error'); });
    });

    // =============================================
    //  EXISTING MOVIE CRUD (create / edit / delete)
    // =============================================
    $(document).on('click', '#create_movie', function () {
        $('#create_movie_form')[0].reset();
        $('#create_movie_modal').modal('show');
    });

    $('#create_movie_form').on('submit', function (e) {
        e.preventDefault();
        var $btn = $(this).find('[type=submit]');
        btnLoad($btn);
        $.ajax({
            url: BASE + '/movies', method: 'POST',
            data: { name: $('#c_name').val(), movie_genre_id: $('#c_movie_genre').val(), langue_id: $('#c_langue_id').val(), cinema_chain_id: $('#c_cinema_chain_id').val() || null, runtime: $('#c_runtime').val(), status: 1, _token: CSRF }
        })
        .done(function () {
            $('#create_movie_modal').modal('hide');
            get_unlinked(); get_linked();
            Swal.fire('Done!', 'Movie created.', 'success');
        })
        .fail(function (xhr) { Swal.fire('Error', xhr.responseJSON?.message || 'Operation failed.', 'error'); })
        .always(function () { btnReset($btn); });
    });

    $(document).on('click', '.edit-movie', function () {
        var id = $(this).data('id');
        var $modal = $('#edit_movie_modal');
        $modal.modal('show');
        modalLoading($modal);
        $.get(BASE + '/movies/' + id + '/show', function (res) {
            $('#e_id').val(id);
            $('#e_name').val(res.movie.name);
            $('#e_movie_genre').val(res.movie.movie_genre_id);
            $('#e_langue_id').val(res.movie.langue_id);
            $('#e_cinema_chain_id').val(res.movie.cinema_chain_id || '');
            $('#e_runtime').val(res.movie.runtime);
            $('#e_status').val(res.movie.status);
        })
        .always(function () { modalLoaded($modal); });
    });

    $('#edit_movie_form').on('submit', function (e) {
        e.preventDefault();
        var $btn = $(this).find('[type=submit]');
        btnLoad($btn);
        var id = $('#e_id').val();
        $.ajax({
            url: BASE + '/movies/' + id, method: 'PUT',
            data: { name: $('#e_name').val(), movie_genre_id: $('#e_movie_genre').val(), langue_id: $('#e_langue_id').val(), cinema_chain_id: $('#e_cinema_chain_id').val() || null, runtime: $('#e_runtime').val(), status: $('#e_status').val(), _token: CSRF }
        })
        .done(function () {
            $('#edit_movie_modal').modal('hide');
            get_linked(); get_unlinked();
            Swal.fire('Done!', 'Movie updated.', 'success');
        })
        .fail(function () { Swal.fire('Error', 'Operation failed.', 'error'); })
        .always(function () { btnReset($btn); });
    });

    // =============================================
    //  SYNC FROM NOC
    // =============================================
    $('#btn-sync-movies').on('click', function () {
        var $btn = $(this);
        Swal.fire({
            title: 'Sync movies from NOC?',
            text: 'This will fetch movies from all configured cinema chains and update the local database.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, sync',
        }).then(function (r) {
            if (!r.isConfirmed) return;

            btnLoad($btn);

            $.ajax({ url: BASE + '/movies/sync', method: 'POST', data: { _token: CSRF } })
                .done(function (res) {
                    var rows = '';
                    $.each(res.results, function (i, r) {
                        var status = r.success
                            ? '<span class="badge bg-success">OK</span>'
                            : '<span class="badge bg-danger">Failed</span>';
                        var detail = r.success
                            ? '<span class="text-success">+' + (r.created || 0) + ' created &nbsp; ↻ ' + (r.updated || 0) + ' updated</span>'
                            : '<span class="text-danger">' + (r.reason || '') + '</span>';
                        rows += '<tr><td>' + r.cinema_chain + '</td><td>' + status + '</td><td>' + detail + '</td></tr>';
                    });

                    var html = '<div class="text-start">'
                        + '<p class="mb-2"><strong>Total :</strong> '
                        + '<span class="text-success">+' + (res.total_created || 0) + ' created</span>'
                        + ' &nbsp; <span class="text-primary">↻ ' + (res.total_updated || 0) + ' updated</span></p>'
                        + '<div class="table-responsive"><table class="table table-sm table-bordered mb-0">'
                        + '<thead><tr><th>Cinema Chain</th><th>Status</th><th>Detail</th></tr></thead>'
                        + '<tbody>' + rows + '</tbody></table></div></div>';

                    Swal.fire({
                        title: 'Sync Results',
                        html: html,
                        icon: 'info',
                        width: 650,
                    });

                    get_unlinked();
                    get_linked();
                })
                .fail(function (xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Sync failed.', 'error');
                })
                .always(function () { btnReset($btn); });
        });
    });

});
</script>
@endsection
