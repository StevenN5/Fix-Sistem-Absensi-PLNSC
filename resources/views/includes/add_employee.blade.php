<!-- Add -->
<div class="modal fade" id="addnew">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>

            <h4 class="modal-title"><b>{{ __('global.add') }} {{ __('global.employees') }}</b></h4>
            <div class="modal-body">

                <div class="card-body text-left">

                    <form method="POST" action="{{ route('employees.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">{{ __('global.name') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.name') }}" id="name" name="name"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="phone_number">{{ __('global.phone_number') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_phone') }}" id="phone_number" name="phone_number"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="address">{{ __('global.address') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_address') }}" id="address" name="address"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="birth_date">{{ __('global.birth_date') }}</label>
                            <input type="date" class="form-control" id="birth_date" name="birth_date"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="institution">{{ __('global.institution') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_institution') }}" id="institution" name="institution"
                                required />
                        </div>
                        <div class="form-group">
                            <label for="internship_start_date">Periode Magang Mulai</label>
                            <input type="date" class="form-control" id="internship_start_date" name="internship_start_date" />
                        </div>
                        <div class="form-group">
                            <label for="internship_end_date">Periode Magang Selesai</label>
                            <input type="date" class="form-control" id="internship_end_date" name="internship_end_date" />
                        </div>
                        <div class="form-group">
                            <label for="profile_photo">Foto Profil</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png,.webp" />
                        </div>
                        <div class="form-group">
                            <label for="position">{{ __('global.position') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_position') }}" id="position" name="position"
                                required />
                        </div>

                        <div class="form-group">
                            <label for="major">{{ __('global.major') }}</label>
                            <input type="text" class="form-control" placeholder="{{ __('global.placeholder_major') }}" id="major" name="major" />
                        </div>

                        
                        <div class="form-group">
                            <label for="email" class="col-sm-3 control-label">{{ __('global.email') }}</label>


                            <input type="email" class="form-control" id="email" name="email">

                        </div>
                        <div class="form-group">
                            <label for="schedule" class="col-sm-3 control-label">{{ __('global.schedule_label') }}</label>


                            <select class="form-control" id="schedule" name="schedule" required>
                                <option value="" selected>- {{ __('global.pleaseSelect') }} -</option>
                                @foreach($schedules as $schedule)
                                <option value="{{$schedule->slug}}">{{$schedule->slug}} -> dari {{$schedule->time_in}}
                                    sampai {{$schedule->time_out}} </option>
                                @endforeach

                            </select>

                        </div>
                        <div class="form-group">
                            <label for="emergency_contact_name">Nama Kontak Darurat</label>
                            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name">
                        </div>
                        <div class="form-group">
                            <label for="emergency_contact_phone">Nomor Kontak Darurat</label>
                            <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone">
                        </div>
                        <div class="form-group">
                            <label for="emergency_contact_relation">Hubungan Kontak Darurat</label>
                            <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation">
                        </div>
                        <div class="form-group">
                            <label for="bank_name">Nama Bank</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name">
                        </div>
                        <div class="form-group">
                            <label for="bank_account_number">Nomor Rekening</label>
                            <input type="text" class="form-control" id="bank_account_number" name="bank_account_number">
                        </div>

                        <div class="form-group">
                            <div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light">
                                    {{ __('global.submit') }}
                                </button>
                                <button type="reset" class="btn btn-secondary waves-effect m-l-5" data-dismiss="modal">
                                    {{ __('global.cancel') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>


        </div>

    </div>
</div>
</div>
