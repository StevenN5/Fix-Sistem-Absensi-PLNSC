<!-- Edit -->
<div class="modal fade" id="edit{{ $employee->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>

            </div>
            <h4 class="modal-title"><b><span class="employee_id">{{ __('global.edit') }} {{ __('global.employees') }}</span></b></h4>
            <div class="modal-body text-left">
                <form class="form-horizontal" method="POST" action="{{ route('employees.update', $employee->name) }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">{{ __('global.name') }}</label>


                        <input type="text" class="form-control" id="name" name="name" value="{{ $employee->name }}"
                            required>

                    </div>
                    <div class="form-group">
                        <label for="phone_number" class="col-sm-3 control-label">{{ __('global.phone_number') }}</label>


                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ $employee->phone_number }}"
                            required>

                    </div>
                    <div class="form-group">
                        <label for="address" class="col-sm-3 control-label">{{ __('global.address') }}</label>


                        <input type="text" class="form-control" id="address" name="address" value="{{ $employee->address }}"
                            required>

                    </div>
                    <div class="form-group">
                        <label for="birth_date" class="col-sm-3 control-label">{{ __('global.birth_date') }}</label>


                        <input type="date" class="form-control" id="birth_date" name="birth_date" value="{{ $employee->birth_date }}"
                            required>

                    </div>
                    <div class="form-group">
                        <label for="institution" class="col-sm-3 control-label">{{ __('global.institution') }}</label>


                        <input type="text" class="form-control" id="institution" name="institution" value="{{ $employee->institution }}"
                            required>

                    </div>
                    <div class="form-group">
                        <label for="internship_start_date" class="col-sm-3 control-label">Periode Magang Mulai</label>
                        <input type="date" class="form-control" id="internship_start_date" name="internship_start_date" value="{{ $employee->internship_start_date }}">
                    </div>
                    <div class="form-group">
                        <label for="internship_end_date" class="col-sm-3 control-label">Periode Magang Selesai</label>
                        <input type="date" class="form-control" id="internship_end_date" name="internship_end_date" value="{{ $employee->internship_end_date }}">
                    </div>
                    <div class="form-group">
                        <label for="profile_photo" class="col-sm-3 control-label">Foto Profil</label>
                        @if($employee->profile_photo_path)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $employee->profile_photo_path) }}" alt="Foto Profil"
                                    style="width: 70px; height: 70px; object-fit: cover; border-radius: 50%; border: 1px solid #e6e3ef;">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png,.webp">
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">{{ __('global.position') }}</label>


                        <input type="text" class="form-control" id="position" name="position" value="{{ $employee->position }}"
                            required>

                    </div>

                    <div class="form-group">
                        <label for="major" class="col-sm-3 control-label">{{ __('global.major') }}</label>


                        <input type="text" class="form-control" id="major" name="major" value="{{ $employee->major }}">

                    </div>
                 
                  
                    <div class="form-group">
                        <label for="email" class="col-sm-3 control-label">{{ __('global.email') }}</label>


                        <input type="email" class="form-control" id="email" name="email"
                            value="{{ $employee->email }}" >

                    </div>
                    <div class="form-group">
                        <label for="schedule" class="col-sm-3 control-label">{{ __('global.schedule_label') }}</label>


                        <select class="form-control" id="schedule" name="schedule" required>
                            <option value="" selected>- {{ __('global.pleaseSelect') }} -</option>
                            @foreach ($schedules as $schedule)
                                <option value="{{ $schedule->slug }}">{{ $schedule->slug }} -> dari
                                    {{ $schedule->time_in }} sampai {{ $schedule->time_out }} </option>
                            @endforeach

                        </select>

                    </div>
                    <div class="form-group">
                        <label for="emergency_contact_name" class="col-sm-3 control-label">Nama Kontak Darurat</label>
                        <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name"
                            value="{{ $employee->emergency_contact_name }}">
                    </div>
                    <div class="form-group">
                        <label for="emergency_contact_phone" class="col-sm-3 control-label">Nomor Kontak Darurat</label>
                        <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone"
                            value="{{ $employee->emergency_contact_phone }}">
                    </div>
                    <div class="form-group">
                        <label for="emergency_contact_relation" class="col-sm-3 control-label">Hubungan Kontak Darurat</label>
                        <input type="text" class="form-control" id="emergency_contact_relation" name="emergency_contact_relation"
                            value="{{ $employee->emergency_contact_relation }}">
                    </div>
                    <div class="form-group">
                        <label for="bank_name" class="col-sm-3 control-label">Nama Bank</label>
                        <input type="text" class="form-control" id="bank_name" name="bank_name"
                            value="{{ $employee->bank_name }}">
                    </div>
                    <div class="form-group">
                        <label for="bank_account_number" class="col-sm-3 control-label">Nomor Rekening</label>
                        <input type="text" class="form-control" id="bank_account_number" name="bank_account_number"
                            value="{{ $employee->bank_account_number }}">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> {{ __('global.close') }}</button>
                <button type="submit" class="btn btn-success btn-flat" name="edit"><i class="fa fa-check-square-o"></i>
                    {{ __('global.update') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete -->
<div class="modal fade" id="delete{{ $employee->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header " style="align-items: center">
               
              <h4 class="modal-title "><span class="employee_id">{{ __('global.delete') }} {{ __('global.employees') }}</span></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" method="POST" action="{{ route('employees.destroy', $employee->name) }}">
                    @csrf
                    {{ method_field('DELETE') }}
                    <div class="text-center">
                        <h6>{{ __('global.areYouSure') }}</h6>
                        <h2 class="bold del_employee_name">{{$employee->name}}</h2>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal"><i
                        class="fa fa-close"></i> {{ __('global.close') }}</button>
                <button type="submit" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i> {{ __('global.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
