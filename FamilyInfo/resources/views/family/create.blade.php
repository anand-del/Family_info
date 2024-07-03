@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Create Family</h2>
    <form action="{{ url('family/store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-row mb-3">
            <div class="col-md-6">
                <label for="name">Head of the Family Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="surname">Surname</label>
                <input type="text" name="surname" class="form-control" required>
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="col-md-6">
                <label for="birthdate">Birthdate</label>
                <input type="date" name="birthdate" id="birthdate" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="mobile_no">Mobile No</label>
                <input type="text" name="mobile_no" id="mobile_no" class="form-control" required maxlength="10">
            </div>
        </div>
        <div class="form-group mb-3">
            <label for="address">Address</label>
            <textarea name="address" class="form-control" rows="3" required></textarea>
        </div>
        <div class="form-row mb-3">
            <div class="col-md-6">
                <label for="state_id">State</label>
                <select name="state_id" id="state" class="form-control" required>
                    @foreach($states as $state)
                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="city_id">City</label>
                <select name="city_id" id="city" class="form-control" required>
                    <option value="">Select City</option>
                </select>
            </div>
        </div>
        <div class="form-row mb-3">
            <div class="col-md-6">
                <label for="pincode">Pincode</label>
                <input type="text" name="pincode" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label for="marital_status">Marital Status</label>
                <select name="marital_status" id="marital_status" class="form-control" required>
                    <option value="Married">Married</option>
                    <option value="Unmarried">Unmarried</option>
                </select>
            </div>
        </div>
        <div class="form-group mb-3" id="wedding_date_group">
            <label for="wedding_date">Wedding Date</label>
            <input type="date" name="wedding_date" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="photo">Photo</label>
            <input type="file" name="photo" class="form-control" id="photoInput" onchange="previewImage(this, '#photoPreview')">
            <img src="#" id="photoPreview" style="display: none; max-width: 20%; height: 20%; margin-top: 10px;">
        </div>
        <div class="form-group mb-3">
            <label for="hobbies">Hobbies</label>
            <div id="hobbies-container">
                <div class="input-group mb-3">
                    <input type="text" name="hobbies[]" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary remove-hobby">Remove</button>
                    </div>
                </div>
            </div>
            <button type="button" id="add-hobby" class="btn btn-secondary">Add Hobby</button>
        </div>
        <h4 class="mt-4 mb-3">Family Members</h4>
        <div id="family-members-container">
            <div class="family-member-form border p-3 mb-3">
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="family_members[0][name]">Name</label>
                        <input type="text" name="family_members[0][name]" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="family_members[0][birthdate]">Birthdate</label>
                        <input type="date" name="family_members[0][birthdate]" class="form-control" required>
                    </div>
                </div>
                <div class="form-row mb-3">
                    <div class="col-md-6">
                        <label for="family_members[0][marital_status]">Marital Status</label>
                        <select name="family_members[0][marital_status]" class="form-control" required>
                            <option value="Married">Married</option>
                            <option value="Unmarried">Unmarried</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="family_members[0][wedding_date]">Wedding Date</label>
                        <input type="date" name="family_members[0][wedding_date]" class="form-control">
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="family_members[0][education]">Education</label>
                    <input type="text" name="family_members[0][education]" class="form-control" required>
                </div>
                <div class="form-group mb-3">
                    <label for="family_members[0][photo]">Photo</label>
                    <input type="file" name="family_members[0][photo]" class="form-control" onchange="previewImage(this, '#photoPreview0')">
                    <img src="#" id="photoPreview0" style="display: none; max-width: 20%; height: 20%; margin-top: 10px;">
                </div>
                <hr>
            </div>
        </div>
        <button type="button" id="add-family-member" class="btn btn-secondary">Add Family Member</button>
        <button type="submit" class="btn btn-primary mt-3">Submit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    $(document).ready(function () {
        $('#state').change(function () {
            var stateId = $(this).val();
            $.ajax({
                url: '{{ route('cities.get') }}',
                type: 'GET',
                data: { state_id: stateId },
                success: function (data) {
                    $('#city').empty().append('<option value="">Select City</option>');
                    $.each(data, function (key, value) {
                        $('#city').append('<option value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });
        });

        $('#marital_status').change(function () {
            if ($(this).val() === 'Married') {
                $('#wedding_date_group').show();
            } else {
                $('#wedding_date_group').hide();
            }
        }).trigger('change');

        $('#add-hobby').click(function () {
            $('#hobbies-container').append(`
                <div class="input-group mb-3">
                    <input type="text" name="hobbies[]" class="form-control" required>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary remove-hobby">Remove</button>
                    </div>
                </div>
            `);
        });

        $(document).on('click', '.remove-hobby', function () {
            $(this).closest('.input-group').remove();
        });

        $('#add-family-member').click(function () {
            var index = $('.family-member-form').length;

            var familyMemberHtml = `
                <div class="family-member-form border p-3 mb-3">
                    <div class="form-row mb-3">
                        <div class="col-md-6">
                            <label for="family_members[${index}][name]">Name</label>
                            <input type="text" name="family_members[${index}][name]" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="family_members[${index}][birthdate]">Birthdate</label>
                            <input type="date" name="family_members[${index}][birthdate]" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-row mb-3">
                        <div class="col-md-6">
                            <label for="family_members[${index}][marital_status]">Marital Status</label>
                            <select name="family_members[${index}][marital_status]" class="form-control" required>
                                <option value="Married">Married</option>
                                <option value="Unmarried">Unmarried</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="family_members[${index}][wedding_date]">Wedding Date</label>
                            <input type="date" name="family_members[${index}][wedding_date]" class="form-control">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="family_members[${index}][education]">Education</label>
                        <input type="text" name="family_members[${index}][education]" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="family_members[${index}][photo]">Photo</label>
                        <input type="file" name="family_members[${index}][photo]" class="form-control" onchange="previewImage(this, '#photoPreview${index}')">
                        <img src="#" id="photoPreview${index}" style="display: none; max-width: 20%; height: 20%; margin-top: 10px;">
                    </div>
                    <hr>
                </div>`;
            $('#family-members-container').append(familyMemberHtml);
        });

        $('#birthdate').change(function () {
            var selectedDate = new Date($(this).val());
            var today = new Date();
            var age = today.getFullYear() - selectedDate.getFullYear();
            var m = today.getMonth() - selectedDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < selectedDate.getDate())) {
                age--;
            }
            if (age < 21) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Birthdate must be at least 21 or more than 21 years!',
                });
                $(this).val('');
            }
        });

        $('#mobile_no').keypress(function (event) {
            var keycode = event.which;
            if (!(keycode >= 48 && keycode <= 57)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Only numbers are allowed in mobile number!',
                });
                event.preventDefault();
            }

            var mobileNumber = $(this).val();
            if (mobileNumber.length >= 10) {
                if (event.which !== 8) {
                    event.preventDefault();
                }
            }
        });

        $('form').submit(function (event) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, submit it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    function previewImage(input, imgSelector) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $(imgSelector).attr('src', e.target.result);
            $(imgSelector).show();
        };

        reader.readAsDataURL(input.files[0]);
    }
</script>
@endsection
