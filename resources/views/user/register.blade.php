@extends('admin.partials._layout')

@section('title')
    <title>Register User | {{ config('app.name') }}</title>
@endsection

@section('content')
    <section class="px-3 my-5">
        <div class="container mx-auto">
            <img src="{{ asset('/images/laratter-logo.svg') }}" alt="{{ config('app.name') }} - Logo" title="{{ config('app.name') }} - Logo" class="block mx-auto" />

            <div class="mx-auto max-w-3xl bg-white rounded shadow-md overflow-hidden">
                <div class="bg-blue-200 px-3 py-2">
                    <h1 class="text-gray-900 uppercase font-bold text-2xl tracking-wider">Register User</h1>
                </div>
                <div class="bg white px-3 py-2">
                    <p class="text-sm text-gray-800">We will send an E-Mail containing the details you will give below.</p>

                    <form action="{{ route('user.register') }}" method="POST" id="formRegisterUser">
                        @csrf

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="first_name" class="formLabel">First Name:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="text" name="first_name" id="first_name" class="formInput" maxlength="255" required />
                                <span data-name="first_name"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="last_name" class="formLabel">Last Name:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="text" name="last_name" id="last_name" class="formInput" maxlength="255" required />
                                <span data-name="last_name"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="username" class="formLabel">Username:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="text" name="username" id="username" class="formInput" required maxlength="50">
                                <span data-name="username"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="email" class="formLabel">E-Mail:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="email" name="email" id="email" class="formInput" required>
                                <span data-name="email"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="repeat_email" class="formLabel">Repeat E-Mail:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="email" name="repeat_email" id="repeat_email" class="formInput" required>
                                <span data-name="repeat_email"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/5">
                                <label for="password" class="formLabel">Password:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="password" name="password" id="password" class="formInput" required>
                                <span data-name="password"></span>
                            </div>
                        </div>

                        <div class="flex justify-center items-center my-8">
                            <button type="submit" class="formSubmitBtn btnRegisterUser">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('pageScripts')
    <script>
        $('.btnRegisterUser').click(function (e) {
            e.preventDefault();

            var self = $(this),
                form = $('#formRegisterUser');

            form.find('span').removeClass('text-red-500 text-sm').html('');
            form.find('input').removeClass('border-red-500');

            self.addClass('opacity-50 cursor-not-allowed')
                .html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.removeClass('opacity-50 cursor-not-allowed').html('Register');

                    displayGrowlNotification(res);

                    if (res.status == 'success') {
                        form[0].reset();

                        setTimeout(function () {
                            window.location = res.redirectTo;
                        }, 4000);
                    }
                },
                error: function (err) {
                    self.removeClass('opacity-50 cursor-not-allowed').html('Register');

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
