<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @yield('title')
    <meta name="robots" content="NOINDEX, NOFOLLOW" />

    <link href="https://fonts.googleapis.com/css2?family=Inconsolata&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('/css/vendor.css') }}" />
    <link href="https://use.fontawesome.com/releases/v5.13.0/css/all.css" rel="stylesheet" />

    @yield('pageStyles')
</head>
<body class="bg-gray-300">
    @if (auth()->id() == 1)
        <header class="bg-gray-900 px-3 py-1">
            <div class="container mx-auto">
                <div class="flex flex-wrap justify-between">
                    <div>
                        <div class="w-16">
                            <a href="{{ route('admin.dashboard') }}"><img src="{{ asset('/images/laratter-logo.svg') }}" alt="{{ config('app.name') }}" title="{{ config('app.name') }}" loading="lazy" /></a>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <x-admin-nav-link route="admin.dashboard">Dashboard</x-admin-nav-link>
                        <x-admin-nav-link route="admin.accountSettings">Account Settings</x-admin-nav-link>
                        <x-admin-nav-link route="admin.logout">Logout</x-admin-nav-link>
                    </div>
                </div>
            </div>
        </header>
    @endif

    @yield('content')

    <script src="{{ asset('/js/app.js') }}"></script>
    <script>
        $('body').on('blur', 'input', function (e) {
            var self = $(e.target);

            if (self.hasClass('border-red-500')) {
                self.removeClass('border-red-500');
            }
            if ($('body').find('span[data-name='+self.attr('id')+']').hasClass('text-xs text-red-500')) {
                $('body').find('span[data-name='+self.attr('id')+']').removeClass('text-xs text-red-500').html('');
            }
        });

        $('.linkImportantPoints').click(function (e) {
            $('.importantPoints').removeClass('hidden');
            $(this).css({'text-decoration': 'none'});
            return false;
        });

        $('.closeImportantPoints').click(function (e) {
            $('.importantPoints').addClass('hidden');
            $(this).css({'text-decoration': 'none'});
            return false;
        });

        function displayGrowlNotification(res)
        {
            $.iGrowl({
                type: res.status == 'success' ? 'success' : 'error',
                title: res.title,
                message: res.message,
                icon: res.status == 'success' ? 'feather-check' : 'feather-cross',
                delay: res.delay || 0,
                placement: {
                    x: 'left',
                    y: 'bottom'
                },
                animShow: 'flash',
                animHide: 'zoomOut'
            });
        }
    </script>
    @yield('pageScripts')
</body>
</html>
