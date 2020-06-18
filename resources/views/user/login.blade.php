@extends('partials._layout')

@section('title')
    <title>Login User | {{ config('app.name') }}</title>
@endsection

@section('content')
    <section class="px-3 my-5">
        <div class="container mx-auto">
            <img src="{{ asset('/images/laratter-logo.svg') }}" alt="{{ config('app.name') }} - Logo" title="{{ config('app.name') }} - Logo" class="block mx-auto" />

            <div class="mx-auto max-w-3xl bg-white rounded shadow-md overflow-hidden">
                <div class="bg-blue-200 px-3 py-2">
                    <h1 class="text-gray-900 uppercase font-bold text-2xl tracking-wider">Login User</h1>
                </div>
                <div class="bg white px-3 py-2">
                    <form action="{{ route('user.login.check') }}" method="POST" id="formLoginUser">
                        @csrf

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/4">
                                <label for="usernameOrEmail" class="formLabel">Username / E-Mail:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="text" name="usernameOrEmail" id="usernameOrEmail" class="formInput" required />
                                <span data-name="usernameOrEmail"></span>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center my-5">
                            <div class="w-full sm:w-1/4">
                                <label for="password" class="formLabel">Password:</label>
                            </div>
                            <div class="w-full sm:w-3/4">
                                <input type="password" name="password" id="password" class="formInput" required />
                                <span data-name="password"></span>
                            </div>
                        </div>

                        <div class="flex justify-center items-center my-8">
                            <button type="submit" class="formSubmitBtn btnLoginUser">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('pageScripts')
    <script>
        $('.btnLoginUser').click(function (e) {
            e.preventDefault();

            var self = $(this),
                form = $('#formLoginUser');

            form.find('span').removeClass('text-red-500 text-sm').html('');
            form.find('input').removeClass('border-red-500');

            self.addClass('opacity-50 cursor-not-allowed')
                .html('<i class="fa fa-spinner fa-spin"></i> Logging in...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function (res) {
                    self.removeClass('opacity-50 cursor-not-allowed').html('Login');

                    displayGrowlNotification(res);

                    if (res.status == 'success') {
                        form[0].reset();

                        setTimeout(function () {
                            window.location = res.redirectTo;
                        }, 4000);
                    }
                },
                error: function (err) {
                    self.removeClass('opacity-50 cursor-not-allowed').html('Login');

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
