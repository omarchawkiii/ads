@extends('admin.layouts.app')
@section('title')
    compaign
@endsection
@section('content')
    <div class="container py-4">

        <div class="card card-body py-3">
            <div class="row align-items-center">
                <div class="col-12">
                    <div class="d-sm-flex align-items-center justify-space-between">
                        <h4 class="mb-4 mb-sm-0 card-title"> compaign </h4>
                        <nav aria-label="breadcrumb" class="ms-auto">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page">
                                    <button class="btn bg-success  text-white " id="create_compaign">
                                        + New compaign
                                    </button>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-body table-responsive">
                <table id="compaigns-table" class="table table-striped table-bordered display text-nowrap dataTable">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center" style="width:160px;">ID</th>
                            <th class="text-center">Name</th>
                            <th class="text-center">Date</th>
                            <th class="text-center" style="width:160px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal  " id="create_compaign_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg" aria-modal="true"
        role="dialog">
        <div class="modal-dialog modal-lg  modal-dialog-centered">
            <div class="modal-content ">

                <div class="modal-header d-flex align-items-center  bg-primary ">
                    <h4 class="modal-title text-white " id="myLargeModalLabel ">
                        Create compaign
                    </h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body wizard-content">
                    <form action="#" class="validation-wizard wizard-circle mt-5" id="create_compaign_form">
                        <!-- Step 1 -->
                        <h6>Basics</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="name"> Campaign Name : <span
                                                class="danger">*</span>
                                        </label>
                                        <input type="text" class="form-control required" id="name" name="name"
                                            placeholder="Campaign Name" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="compaign_objective"> Campaign Objective : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="compaign_objective"
                                            name="compaign_objective">
                                            <option value="">Select...</option>
                                            @foreach ($compaign_objectives as $compaign_objective)
                                                <option value="{{ $compaign_objective->id }}">
                                                    {{ $compaign_objective->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="compaign_category"> Ad Category : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="compaign_category"
                                            name="compaign_category">
                                            <option value="">Select...</option>
                                            @foreach ($compaign_categories as $compaign_category)
                                                <option value="{{ $compaign_category->id }}">{{ $compaign_category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="brand">Ad Brand</label>
                                        <select class="form-select required select2" multiple="" id="brand"
                                            name="brand[]">
                                            <option value="">Select...</option>
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="start_date">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="end_date">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" />
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="budget">Desired Budget</label> <span
                                            class="danger">(RM)</span>
                                        <input type="number" class="form-control" id="budget" name="budget" />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="langue"> Preferred Language : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="langue" name="langue">
                                            <option value="">Select...</option>
                                            @foreach ($langues as $langue)
                                                <option value="{{ $langue->id }}">{{ $langue->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="note">Internal Notes :</label>
                                        <textarea name="note" id="note" rows="4" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>

                        </section>
                        <!-- Step 2 -->
                        <h6> Targeting </h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="location"> Select Cinemas : <span
                                                class="danger">*</span>
                                        </label>

                                        <select class="form-select required select2" multiple="" id="location"
                                            name="location[]">
                                            <option value="">Select...</option>
                                            @foreach ($locations as $location)
                                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="hall_type"> Hall Types : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required select2" id="hall_type" multiple=""
                                            name="hall_type[]">
                                            <option value="">Select...</option>
                                            @foreach ($hall_types as $hall_type)
                                                <option value="{{ $hall_type->id }}">{{ $hall_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="movie"> Select Movies : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="movie" name="movie">
                                            <option value="">Select...</option>
                                            @foreach ($movies as $movie)
                                                <option value="{{ $movie->id }}">{{ $movie->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="movie_genre"> Genres : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required select2" multiple="" id="movie_genre"
                                            name="movie_genre[]">

                                            <option value="">Select...</option>
                                            @foreach ($movie_genres as $movie_genre)
                                                <option value="{{ $movie_genre->id }}">{{ $movie_genre->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="gender"> Gender : <span class="danger">*</span>
                                        </label>
                                        <select class="form-select required" id="gender" name="gender">
                                            <option value="">Select...</option>
                                            @foreach ($genders as $gender)
                                                <option value="{{ $gender->id }}">{{ $gender->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="target_type"> Target Type : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select select2 required" id="target_type"
                                            name="target_type[]">
                                            <option value="">Select...</option>
                                            @foreach ($target_types as $target_type)
                                                <option value="{{ $target_type->id }}">{{ $target_type->name }}
                                                    ({{ $target_type->detail }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="interest"> Interests : <span
                                                class="danger">*</span>
                                        </label>
                                        <select class="form-select required select2" multiple="" id="interest"
                                            name="interest[]">
                                            <option value="">Select...</option>
                                            @foreach ($interests as $interest)
                                                <option value="{{ $interest->id }}">{{ $interest->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </section>
                        <!-- Step 3 -->
                        <h6>Upload & Slot</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="slot"> Select Ad Slot Tier : <span
                                                class="danger">*</span>
                                        </label>

                                        <select class="form-select required" id="slot" name="slot">
                                            <option value="">Select...</option>
                                            @foreach ($slots as $slot)
                                                <option value="{{ $slot->id }}">{{ $slot->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="duration"> Select Ad Duration : <span
                                                class="danger">*</span>
                                        </label>

                                        <select class="form-select required" id="duration" name="duration">
                                            <option value="">Select...</option>
                                            <option value="15">15 seconds</option>
                                            <option value="30">30 seconds</option>
                                            <option value="45">45 seconds</option>
                                            <option value="60">60 seconds</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="dcp_file"> Upload DCP Creative : <span
                                                class="danger">*</span>
                                        </label>
                                        <input class="form-control" type="file" id="dcp_file">
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Step 4 -->
                        <h6>Review & Submit</h6>
                        <section>
                            <div class="row" id="review-summary">
                                <div class="col-md-6">
                                    <h6 class="mb-3">Basics</h6>
                                    <dl class="row">
                                        <dt class="col-5">Campaign Name</dt>
                                        <dd class="col-7" id="rv_name">–</dd>
                                        <dt class="col-5">Objective</dt>
                                        <dd class="col-7" id="rv_objective">–</dd>
                                        <dt class="col-5">Ad Category</dt>
                                        <dd class="col-7" id="rv_category">–</dd>
                                        <dt class="col-5">Brand</dt>
                                        <dd class="col-7" id="rv_brand">–</dd>
                                        <dt class="col-5">Start</dt>
                                        <dd class="col-7" id="rv_start">–</dd>
                                        <dt class="col-5">End</dt>
                                        <dd class="col-7" id="rv_end">–</dd>
                                        <dt class="col-5">Budget (RM)</dt>
                                        <dd class="col-7" id="rv_budget">–</dd>
                                        <dt class="col-5">Language</dt>
                                        <dd class="col-7" id="rv_langue">–</dd>
                                        <dt class="col-5">Notes</dt>
                                        <dd class="col-7" id="rv_note">–</dd>
                                    </dl>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">Targeting & Slot</h6>
                                    <dl class="row">
                                        <dt class="col-5">Cinemas</dt>
                                        <dd class="col-7" id="rv_cinema">–</dd>
                                        <dt class="col-5">Hall Type</dt>
                                        <dd class="col-7" id="rv_hall">–</dd>
                                        <dt class="col-5">Movie</dt>
                                        <dd class="col-7" id="rv_movie">–</dd>
                                        <dt class="col-5">Genre</dt>
                                        <dd class="col-7" id="rv_genre">–</dd>
                                        <dt class="col-5">Gender</dt>
                                        <dd class="col-7" id="rv_gender">–</dd>
                                        <dt class="col-5">Target Type</dt>
                                        <dd class="col-7" id="rv_target_type">–</dd>
                                        <dt class="col-5">Interests</dt>
                                        <dd class="col-7" id="rv_interest">–</dd>
                                        <dt class="col-5">Slot Tier</dt>
                                        <dd class="col-7" id="rv_slot_tier">–</dd>
                                        <dt class="col-5">Ad Duration</dt>
                                        <dd class="col-7" id="rv_ad_duration">–</dd>
                                    </dl>
                                </div>
                            </div>
                        </section>
                    </form>
                </div>

            </div>

        </div>
        <!-- /.modal-content -->
    </div>

    <div class="modal fade" id="edit_compaign_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Edit compaign</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body wizard-content">
                    <form id="edit_compaign_form" class="validation-wizard wizard-circle mt-3">
                        <input type="hidden" id="e_id" name="id">

                        <!-- STEP 1 -->
                        <h6>Basics</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Campaign Name <span class="danger">*</span></label>
                                    <input type="text" class="form-control required" id="e_name" name="name" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Campaign Objective <span class="danger">*</span></label>
                                    <select class="form-select required" id="e_compaign_objective"
                                        name="compaign_objective">
                                        <option value="">Select...</option>
                                        @foreach ($compaign_objectives as $co)
                                            <option value="{{ $co->id }}">{{ $co->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ad Category <span class="danger">*</span></label>
                                    <select class="form-select required" id="e_compaign_category"
                                        name="compaign_category">
                                        <option value="">Select...</option>
                                        @foreach ($compaign_categories as $cc)
                                            <option value="{{ $cc->id }}">{{ $cc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Brands</label>
                                    <select class="form-select select2" id="e_brand" name="brand[]" multiple>
                                        @foreach ($brands as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="e_start_date" name="start_date" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="e_end_date" name="end_date" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Budget (RM)</label>
                                    <input type="number" class="form-control" id="e_budget" name="budget" />
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Language <span class="danger">*</span></label>
                                    <select class="form-select required" id="e_langue" name="langue">
                                        <option value="">Select...</option>
                                        @foreach ($langues as $l)
                                            <option value="{{ $l->id }}">{{ $l->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" id="e_note" name="note" rows="3"></textarea>
                                </div>
                            </div>
                        </section>

                        <!-- STEP 2 -->
                        <h6>Targeting</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Cinemas <span class="danger">*</span></label>
                                    <select class="form-select select2 required" id="e_location" name="location[]"
                                        multiple>
                                        @foreach ($locations as $loc)
                                            <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Hall Types <span class="danger">*</span></label>
                                    <select class="form-select select2 required" id="e_hall_type" name="hall_type[]"
                                        multiple>
                                        @foreach ($hall_types as $ht)
                                            <option value="{{ $ht->id }}">{{ $ht->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Movie <span class="danger">*</span></label>
                                    <select class="form-select required" id="e_movie" name="movie">
                                        @foreach ($movies as $m)
                                            <option value="{{ $m->id }}">{{ $m->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Genres <span class="danger">*</span></label>
                                    <select class="form-select select2 required" id="e_movie_genre" name="movie_genre[]"
                                        multiple>
                                        @foreach ($movie_genres as $mg)
                                            <option value="{{ $mg->id }}">{{ $mg->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Gender <span class="danger">*</span></label>
                                    <select class="form-select required" id="e_gender" name="gender">
                                        @foreach ($genders as $g)
                                            <option value="{{ $g->id }}">{{ $g->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Target Types <span class="danger">*</span></label>
                                    <select class="form-select select2 required" id="e_target_type" name="target_type[]"
                                        multiple>
                                        @foreach ($target_types as $tt)
                                            <option value="{{ $tt->id }}">{{ $tt->name }}
                                                ({{ $tt->detail }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Interests <span class="danger">*</span></label>
                                    <select class="form-select select2 required" id="e_interest" name="interest[]"
                                        multiple>
                                        @foreach ($interests as $it)
                                            <option value="{{ $it->id }}">{{ $it->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </section>

                        <!-- STEP 3 -->
                        <h6>Upload & Slot</h6>
                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Ad Slot Tier <span class="danger">*</span></label>
                                    <select class="form-select required" id="e_slot" name="slot">
                                        @foreach ($slots as $s)
                                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Ad Duration <span class="danger">*</span></label>
                                    <select class="form-select required" id="e_duration" name="duration">
                                        <option value="15">15 seconds</option>
                                        <option value="30">30 seconds</option>
                                        <option value="45">45 seconds</option>
                                        <option value="60">60 seconds</option>
                                    </select>
                                </div>

                                <!-- (optionnel) fichier -->
                                <div class="col-md-6 d-none">
                                    <label class="form-label">DCP Creative</label>
                                    <input type="file" class="form-control" id="e_dcp_file" name="dcp_file">
                                </div>
                            </div>
                        </section>


                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="view_compaign_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Compaign details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div id="cmpg_loader" class="py-4 text-center" style="display:none;">
                        <div class="spinner-border" role="status"></div>
                        <div class="mt-2">Chargement…</div>
                    </div>

                    <div id="cmpg_content">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-3">Basics</h6>
                                <dl class="row">
                                    <dt class="col-5">Name</dt>
                                    <dd class="col-7" id="v_name">–</dd>
                                    <dt class="col-5">Objective</dt>
                                    <dd class="col-7" id="v_objective">–</dd>
                                    <dt class="col-5">Category</dt>
                                    <dd class="col-7" id="v_category">–</dd>
                                    <dt class="col-5">Brands</dt>
                                    <dd class="col-7" id="v_brands">–</dd>
                                    <dt class="col-5">Start</dt>
                                    <dd class="col-7" id="v_start">–</dd>
                                    <dt class="col-5">End</dt>
                                    <dd class="col-7" id="v_end">–</dd>
                                    <dt class="col-5">Budget (RM)</dt>
                                    <dd class="col-7" id="v_budget">–</dd>
                                    <dt class="col-5">Language</dt>
                                    <dd class="col-7" id="v_langue">–</dd>
                                    <dt class="col-5">Notes</dt>
                                    <dd class="col-7" id="v_note">–</dd>
                                </dl>
                            </div>

                            <div class="col-md-6">
                                <h6 class="mb-3">Targeting & Slot</h6>
                                <dl class="row">
                                    <dt class="col-5">Cinemas</dt>
                                    <dd class="col-7" id="v_locations">–</dd>
                                    <dt class="col-5">Hall Types</dt>
                                    <dd class="col-7" id="v_hall_types">–</dd>
                                    <dt class="col-5">Movie</dt>
                                    <dd class="col-7" id="v_movie">–</dd>
                                    <dt class="col-5">Genres</dt>
                                    <dd class="col-7" id="v_movie_genres">–</dd>
                                    <dt class="col-5">Gender</dt>
                                    <dd class="col-7" id="v_gender">–</dd>
                                    <dt class="col-5">Target Type</dt>
                                    <dd class="col-7" id="v_target_types">–</dd>
                                    <dt class="col-5">Slot</dt>
                                    <dd class="col-7" id="v_slot">–</dd>
                                    <dt class="col-5">Ad Duration</dt>
                                    <dd class="col-7" id="v_duration">–</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div><!-- /body -->
            </div>
        </div>
    </div>
@endsection


@section('custom_script')
    <script src="{{ asset('assets/libs/jquery-steps/build/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/libs/jquery-validation/dist/jquery.validate.min.js') }}"></script>>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
    <script src="{{ asset('assets/js/helper.js') }}"></script>
    <script>
        $(function() {

            $(document).on('click', '#create_compaign', function() {
                $('#create_compaign_modal').modal('show');
            });

            $(document).on('shown.bs.modal', '#create_compaign_modal', function() {
                const $modal = $(this);

                $modal.find('.select2').each(function() {
                    const $sel = $(this);

                    // Si déjà initialisé (réouverture), détruire proprement
                    if ($sel.hasClass('select2-hidden-accessible')) {
                        $sel.select2('destroy');
                    }

                    $sel.select2({
                        placeholder: "Select...",
                        dropdownParent: $modal, // évite le menu derrière le modal
                        width: '100%',
                        // active "tags" seulement pour #brand
                        tags: $sel.is('#brand')
                    });
                });
            });

            $(document).on('hidden.bs.modal', '#create_compaign_modal', function() {
                $(this).find('.select2.select2-hidden-accessible').select2('destroy');
            });
            var form = $("#create_compaign_form").show();
            $(".validation-wizard").steps({
                    headerTag: "h6",
                    bodyTag: "section",
                    transitionEffect: "fade",
                    titleTemplate: '<span class="step">#index#</span> #title#',
                    labels: {
                        finish: "Submit",
                    },
                    onStepChanging: function(event, currentIndex, newIndex) {
                        return (
                            currentIndex > newIndex ||
                            (!(3 === newIndex && Number($("#age-2").val()) < 18) &&
                                (currentIndex < newIndex &&
                                    (form.find(".body:eq(" + newIndex + ") label.error").remove(),
                                        form.find(".body:eq(" + newIndex + ") .error").removeClass(
                                            "error")),
                                    (form.validate().settings.ignore = ":disabled,:hidden"),
                                    form.valid()))
                        );
                    },
                    onFinishing: function(event, currentIndex) {
                        return (form.validate().settings.ignore = ":disabled"), form.valid();
                    },
                    onFinished: function(event, currentIndex) {
                        event.preventDefault();


                        const url = "{{ url('') }}/compaigns";
                        const data = $(this).serialize();
                        console.log(data)
                        $.ajax({
                                url: url,
                                method: "POST",
                                data: data,
                                "_token": "{{ csrf_token() }}",
                                headers: {
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                }
                            })
                            .done(function(response) {
                                get_compaigns();
                                Swal.fire({
                                    title: 'Done!',
                                    text: 'Compaign created successfully.',
                                    icon: 'success',
                                    confirmButtonText: 'Continue'
                                });
                                reset_form('#create_compaign_modal form'); // ton helper si tu l’as
                            })
                            .fail(function(xhr) {
                                let msg = 'Operation failed.';
                                if (xhr.status === 422 && xhr.responseJSON?.errors) {
                                    msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                                }
                                Swal.fire({
                                    title: 'Error',
                                    text: msg,
                                    icon: 'error'
                                });
                            })
                            .always(function() {
                                $('#wait-modal').modal('hide');
                                $('#create_compaign_modal').modal('hide');
                            });
                    },
                }),
            $(".validation-wizard").validate({
                ignore: "input[type=hidden]",
                errorClass: "text-danger",
                successClass: "text-success",
                highlight: function(element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                unhighlight: function(element, errorClass) {
                    $(element).removeClass(errorClass);
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                rules: {
                    email: {
                        email: !0,
                    },
                },
            });

            function getSelectText(selector) {
                const $el = $(selector);
                if ($el.length === 0) return '–';
                const txt = $el.find('option:selected').text().trim();
                return txt || '–';
            }

            function getVal(selector) {
                const $el = $(selector);
                if ($el.length === 0) return '–';
                const v = $el.val();
                return (v === null || v === '') ? '–' : v;
            }

            function fmtBudget(v) {
                const n = parseFloat(v);
                return isNaN(n) ? '–' : n.toLocaleString(undefined, {
                    maximumFractionDigits: 2
                });
            }


            function updateReview() {
                // Basics
                $('#rv_name').text(getVal('#name'));
                $('#rv_objective').text(getSelectText('#compaign_objective'));
                $('#rv_category').text(getSelectText('#compaign_category'));
                $('#rv_brand').text(getSelectText('#brand'));
                $('#rv_start').text(getVal('#start_date'));
                $('#rv_end').text(getVal('#end_date'));
                $('#rv_budget').text(fmtBudget(getVal('#budget')));
                $('#rv_langue').text(getSelectText('#langue'));
                $('#rv_note').text(getVal('#note'));

                // Targeting
                $('#rv_cinema').text(getSelectText('#location'));
                $('#rv_hall').text(getSelectText('#hall_type'));
                $('#rv_movie').text(getSelectText('#movie'));
                $('#rv_genre').text(getSelectText('#movie_genre'));
                $('#rv_gender').text(getSelectText('#gender'));
                $('#rv_target_type').text(getSelectText('#target_type'));
                $('#rv_interest').text(getSelectText('#interest'));

                // Slot
                $('#rv_slot_tier').text(getSelectText('#slot'));
                $('#rv_ad_duration').text(getSelectText('#duration'));
            }

            $('#create_compaign_form').on('stepChanged', function(e, currentIndex) {
                if (currentIndex === 3) updateReview();
            });

            $('#create_compaign_form').on('input change', 'input, select, textarea', function() {
                // Si la step 4 est visible, actualiser en direct
                const step4Visible = $('#review-summary').is(':visible');
                if (step4Visible) updateReview();
            });

            $(document).on("submit", "#create_compaign_form", function(event) {

            });

            function get_compaigns() {
                $('#wait-modal').modal('show');

                $("#compaigns-table").dataTable().fnDestroy();
                var url = "{{ url('') }}" + '/compaigns/list';
                var result = " ";
                $.ajax({
                        url: url,
                        method: 'GET',
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $.each(response.compaigns, function(index, value) {
                                index++;
                                result = result +
                                    '<tr class="odd text-center">' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    index + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    value.name + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                        formatDateEN(value.start_date, { locale: 'en-US', variant: 'short' }) + ' </td>' +
                                    '<td class="text-body align-middle fw-medium text-decoration-none">' +
                                    '<button id="' + value.id +
                                    '" type="button" class="view  ustify-content-center btn mb-1 btn-rounded btn-success  m-1" >' +
                                    '<i class="mdi mdi-magnify "></i>' +
                                    '</button>' +

                                    '<button id="' + value.id +
                                    '" type="button" class="delete justify-content-center btn mb-1 btn-rounded btn-danger m-1">' +
                                    '<i class="mdi mdi-delete"></i>' +
                                    '</button>' +

                                    '</td>' +
                                    '</tr>';
                            });
                            $('#compaigns-table tbody').html(result)
                            $('#wait-modal').modal('hide');
                            // $('#loader-modal').css('display','none')
                            /***** refresh datatable **** **/

                            var compaigns = $('#compaigns-table').DataTable({
                                "iDisplayLength": 10,
                                destroy: true,
                                "bDestroy": true,
                                "language": {
                                    search: "_INPUT_",
                                    searchPlaceholder: "Search..."
                                }
                            });
                        },
                        error: function(response) {}
                    })
                    .always(function() {
                        $('#wait-modal').modal('hide');
                    });

            }
            get_compaigns();

            $(document).on('click', '.view', function() {
                const id = $(this).attr('id');
                const url = "{{ url('') }}/compaigns/" + encodeURIComponent(id) + '/show';

                // reset + loader
                $('#view_compaign_modal').modal('show');
                $('#cmpg_content').hide();
                $.ajax({
                        url: url,
                        method: 'GET',
                        beforeSend: function() {
                            $("#wait-modal").modal('show');
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    .done(function(data) {
                        console.log(data.name)
                        // data est l’objet compaign avec ses relations
                        $('#v_name').text(jv(data.name));
                        $('#v_objective').text(jn(data.compaign_objective));
                        $('#v_category').text(jn(data.compaign_category));
                        $('#v_brands').text(jn(data.brands));
                        $('#v_start').text(jv(formatDateEN(data.start_date, { locale: 'en-US', variant: 'short' }) ));
                        $('#v_end').text(jv(formatDateEN(data.end_date, { locale: 'en-US', variant: 'short' }) ));
                        $('#v_budget').text(data.budget ? Number(data.budget).toLocaleString() : '–');
                        $('#v_langue').text(jn(data.langue));
                        $('#v_note').text(jv(data.note));

                        $('#v_locations').text(jn(data.locations));
                        $('#v_hall_types').text(jn(data.hall_types));
                        $('#v_movie').text(jn(data.movie));
                        $('#v_movie_genres').text(jn(data.movie_genres));
                        $('#v_gender').text(jn(data.gender));
                        $('#v_target_types').text(jn(data.target_types));
                        $('#v_slot').text(jn(data.slot));
                        $('#v_duration').text(data.ad_duration ? data.ad_duration + ' seconds' : '–');
                        $("#wait-modal").modal('hide');
                    })
                    .fail(function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Impossible de charger la compaign.'
                        });
                        $('#view_compaign_modal').modal('hide');
                        $("#wait-modal").modal('hide');
                    })
                    .always(function() {
                        $("#wait-modal").modal('hide');
                        $('#cmpg_content').show();
                    });
            });
            $(document).on("submit", "#create_compaign_modal", function(event) {

                event.preventDefault();
                var name = $('#create_compaign_modal #name ').val();

                var url = '{{ url('') }}' + '/compaigns';

                $.ajax({
                        url: url,
                        type: 'POST',
                        method: 'POST',
                        data: {
                            name: name,
                            "_token": "{{ csrf_token() }}",
                        },
                    })
                    .done(function(response) {

                        get_compaigns();
                        Swal.fire({
                            title: 'Done!',
                            text: 'compaign Created successfully.',
                            icon: 'success',
                            confirmButtonText: 'Continue'
                        });
                        reset_form('#create_compaign_modal form')
                    })
                    .fail(function(xhr) {
                        Swal.fire({
                            title: 'Error',
                            text: 'Operation failed.',
                            icon: 'error'
                        });
                    })
                    .always(function() {
                        $('#wait-modal').modal('hide');
                        $('#create_compaign_modal').modal('hide');
                    });

            })

            $(document).on('click', '.delete', function() {
                var id = $(this).attr('id');
                console.log(id)

                const url = '{{ url('') }}' + '/compaigns/' + encodeURIComponent(id);
                const csrf = '{{ csrf_token() }}';
                console.log(url)
                // SweetAlert2 confirm
                Swal.fire({
                    title: 'Delete compaign?',
                    text: 'Are you sure you want to delete this compaign?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel'
                }).then(function(result) {
                    if (!result.isConfirmed) return;
                    $('#wait-modal').modal('show');
                    // Bootstrap 5 Modal: passer un ELEMENT, pas une string
                    const waitEl = document.getElementById('wait-modal');
                    const wait = bootstrap.Modal.getOrCreateInstance(waitEl);
                    wait.show();

                    $.ajax({
                            url: url,
                            method: 'POST', // compat Laravel
                            data: {
                                _method: 'DELETE',
                                _token: csrf
                            },
                            headers: {
                                'X-CSRF-TOKEN': csrf
                            }
                        })
                        .done(function(response) {

                            get_compaigns();
                            Swal.fire({
                                title: 'Done!',
                                text: 'compaign deleted successfully.',
                                icon: 'success',
                                confirmButtonText: 'Continue'
                            });

                        })
                        .fail(function(xhr) {
                            Swal.fire({
                                title: 'Error',
                                text: 'Deletion failed.',
                                icon: 'error'
                            });
                        })
                        .always(function() {
                            $('#wait-modal').modal('hide'); //
                        });
                });
            });

            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

            function txtSelect(selector){ const $el=$(selector); return $el.length ? ($el.find('option:selected').map((_,o)=>$(o).text().trim()).get().join(', ') || '–') : '–'; }
            function txtInput(selector){ const v=$(selector).val(); return (v===null||v==='')?'–':v; }
            function fmtNum(v){ const n=parseFloat(v); return isNaN(n)?'–':n.toLocaleString(undefined,{maximumFractionDigits:2}); }

            function updateEditReview(){
            // basics
            $('#ev_name').text(txtInput('#e_name'));
            $('#ev_objective').text(txtSelect('#e_compaign_objective'));
            $('#ev_category').text(txtSelect('#e_compaign_category'));
            $('#ev_brands').text(txtSelect('#e_brand'));
            $('#ev_start').text(txtInput('#e_start_date'));
            $('#ev_end').text(txtInput('#e_end_date'));
            $('#ev_budget').text(fmtNum($('#e_budget').val()));
            $('#ev_langue').text(txtSelect('#e_langue'));
            $('#ev_note').text(txtInput('#e_note'));
            // targeting & slot
            $('#ev_locations').text(txtSelect('#e_location'));
            $('#ev_hall_types').text(txtSelect('#e_hall_type'));
            $('#ev_movie').text(txtSelect('#e_movie'));
            $('#ev_movie_genres').text(txtSelect('#e_movie_genre'));
            $('#ev_gender').text(txtSelect('#e_gender'));
            $('#ev_target_types').text(txtSelect('#e_target_type'));
            $('#ev_slot_tier').text(txtSelect('#e_slot'));
            $('#ev_ad_duration').text(txtSelect('#e_duration'));
            }


            $(document).on('click', '.edit', function () {
                const id = $(this).attr('id');
                const url = "{{ url('') }}/compaigns/" + encodeURIComponent(id)+'/show';

                // reset rapide
                const f = document.getElementById('edit_compaign_form');
                if (f) f.reset();
                $('#edit_compaign_form .select2').val(null).trigger('change');
                $('#e_id').val(id);

                $('#edit_compaign_modal').modal('show');

                // GET compaign
                $.get(url).done(function (data) {
                    // simples
                    $('#e_name').val(data.name || '');
                    $('#e_compaign_objective').val(data.compaign_objective_id ?? data.compaign_objective?.id ?? '').trigger('change');
                    $('#e_compaign_category').val(data.compaign_category_id ?? data.compaign_category?.id ?? '').trigger('change');
                    $('#e_start_date').val(data.start_date || '');
                    $('#e_end_date').val(data.end_date || '');
                    $('#e_budget').val(data.budget ?? '');
                    $('#e_langue').val(data.langue_id ?? data.langue?.id ?? '').trigger('change');
                    $('#e_note').val(data.note || '');
                    $('#e_movie').val(data.movie_id ?? data.movie?.id ?? '').trigger('change');
                    $('#e_gender').val(data.gender_id ?? data.gender?.id ?? '').trigger('change');
                    $('#e_slot').val(data.slot_id ?? data.slot?.id ?? '').trigger('change');
                    $('#e_duration').val(data.ad_duration ?? '').trigger('change');

                    // helper ids
                    const ids = (arrOrObj) => Array.isArray(arrOrObj) ? arrOrObj.map(x=>x?.id).filter(Boolean) : (arrOrObj?.id?[arrOrObj.id]:[]);
                    $('#e_brand').val(ids(data.brands)).trigger('change');
                    $('#e_location').val(ids(data.locations)).trigger('change');
                    $('#e_hall_type').val(ids(data.hall_types || data.hallTypes)).trigger('change');
                    $('#e_movie_genre').val(ids(data.movie_genres || data.movieGenres)).trigger('change');
                    $('#e_interest').val(ids(data.interests)).trigger('change');
                    $('#e_target_type').val(ids(data.target_types || data.targetTypes)).trigger('change');

                    updateEditReview();
                }).fail(function(){
                    Swal.fire({icon:'error',title:'Error',text:'Unable to load compaign.'});
                    $('#edit_compaign_modal').modal('hide');
                });
            });


            $(document).on('shown.bs.modal', '#edit_compaign_modal', function () {
                const $m = $(this);
                $m.find('.select2').each(function(){
                    const $s=$(this);
                    if ($s.hasClass('select2-hidden-accessible')) $s.select2('destroy');
                    $s.select2({ dropdownParent:$m, width:'100%' });
                });

                // Init wizard (éviter double init)
                const $form = $('#edit_compaign_form');
                if (!$form.data('wizard-initialized')) {
                    initEditWizard();
                    $form.data('wizard-initialized', true);
                }
            });


            function initEditWizard(){
            const $form = $("#edit_compaign_form").show();

            $form.steps({
                headerTag: "h6",
                bodyTag: "section",
                transitionEffect: "fade",
                titleTemplate: '<span class="step">#index#</span> #title#',
                labels: { finish: "Save" },
                onStepChanging: function (e, cur, next) {
                if (cur < next) {
                    $form.find(".body:eq(" + next + ") label.error").remove();
                    $form.find(".body:eq(" + next + ") .error").removeClass("error");
                }
                $form.validate().settings.ignore=":disabled,:hidden";
                return $form.valid();
                },
                onFinishing: function () {
                $form.validate().settings.ignore=":disabled";
                return $form.valid();
                },
                onFinished: function (e) {
                e.preventDefault();
                const id = $('#e_id').val();
                const url = "{{ url('') }}/compaigns/" + encodeURIComponent(id);
                const data = $form.serialize() + '&_method=PATCH';

                $.ajax({
                    url: url,
                    method: 'POST', // PATCH via _method
                    data: data
                })
                .done(function(){
                    Swal.fire({icon:'success',title:'Saved',text:'Compaign updated successfully.'});
                    $('#edit_compaign_modal').modal('hide');
                    if (typeof get_compaigns === 'function') get_compaigns();
                })
                .fail(function(xhr){
                    let msg='Update failed.';
                    if (xhr.status===422 && xhr.responseJSON?.errors){
                    msg = Object.values(xhr.responseJSON.errors).flat().join('\n');
                    }
                    Swal.fire({icon:'error',title:'Error',text:msg});
                });
                }
            });


            $form.validate({
                ignore: "input[type=hidden]",
                errorClass: "text-danger",
                successClass: "text-success",
                highlight: (el, ec)=>$(el).removeClass(ec),
                unhighlight: (el, ec)=>$(el).removeClass(ec),
                errorPlacement: (err, el)=>err.insertAfter(el)
            });

            $form.on('stepChanged', function(e, idx){
                if (idx === 3) updateEditReview();
            });

            $form.on('input change', 'input, select, textarea', function(){
                if ($('#edit-review-summary').is(':visible')) updateEditReview();
            });
            }

            $(document).on('hidden.bs.modal', '#edit_compaign_modal', function(){
                $(this).find('.select2.select2-hidden-accessible').select2('destroy');
            });



        });
    </script>
@endsection

@section('custom_css')
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">

    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />
@endsection
