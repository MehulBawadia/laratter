@extends('admin.partials._layout')

@section('title')
    <title>Account Settings | {{ config('app.name') }}</title>
@endsection

@section('content')
    <section class="px-3 py-5 bg-white shadow">
        <div class="container mx-auto">
            <h1 class="text-2xl font-bold">Account Settings</h1>
        </div>
    </section>

    <section class="px-3 py-5">
        <div class="container mx-auto">
            <div class="mx-auto max-w-3xl bg-white rounded shadow-md overflow-hidden">
                <div class="bg-blue-200 px-3 py-2">
                    <h1 class="text-gray-900 uppercase font-bold text-2xl tracking-wider">General Settings</h1>
                </div>
                <div class="bg white px-3 py-2">
                    <form action="{{ route('admin.accountSettings.updateGeneral') }}" method="POST" id="formUpdateGeneral">
                        @csrf
                        @method('PATCH')

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="first_name" class="formLabel">First Name:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="text" name="first_name" id="first_name" class="formInput" value="{{ $admin->first_name }}" maxlength="255" required />
                                <span data-name="first_name"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="last_name" class="formLabel">Last Name:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="text" name="last_name" id="last_name" class="formInput" value="{{ $admin->last_name }}" maxlength="255" required />
                                <span data-name="last_name"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="username" class="formLabel">Username:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="text" name="username" id="username" class="formInput" value="{{ $admin->username }}" required maxlength="50">
                                <span data-name="username"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="email" class="formLabel">E-Mail:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="email" name="email" id="email" class="formInput" value="{{ $admin->email }}" required>
                                <span data-name="email"></span>
                            </div>
                        </div>

                        <div class="flex justify-center items-center my-8">
                            <button type="submit" class="formSubmitBtn btnUpdateGeneral">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('pageScripts')
    <script>
        $('.btnUpdateGeneral').click(function (e) {
            e.preventDefault();

            var self = $(this),
                form = $('#formUpdateGeneral');

            form.find('span').removeClass('text-red-500 text-sm').html('');
            form.find('input').removeClass('border-red-500');

            self.addClass('opacity-50 cursor-not-allowed')
                .html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.removeClass('opacity-50 cursor-not-allowed').html('Update');

                    displayGrowlNotification(res);
                },
                error: function (err) {
                    self.removeClass('opacity-50 cursor-not-allowed').html('Update');

                    var errors = null;

                    if (err.status == 422) {
                        errors = err.responseJSON.errors;
                    }

                    if (errors != null) {
                        $.each(errors, function (index, value) {
                            $('input[id="'+index+'"]').first().addClass('border-red-500');
                            $('span[data-name="'+index+'"]').first().addClass('text-xs text-red-500').html('<i class="fas fa-times-circle"></i> ' + value);
                        });
                    } else {
                        alert('Something went wrong!');
                    }
                }
            });

            return false;
        });
    </script>
@endsection
