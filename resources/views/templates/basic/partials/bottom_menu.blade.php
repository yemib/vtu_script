
@if(!request()->routeIs('user.home') && !request()->routeIs('user.deposit.history') && !request()->routeIs('user.withdraw.history'))
<nav class="navbar navbar-expand-lg section--bg2 bottom-menu p-0">
    <div class="container-lg">
        <button class="navbar-toggler text-white align-items-center py-2 ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#bottomMenu" aria-controls="bottomMenu" aria-expanded="false" aria-label="Toggle navigation">
            <p class="d-flex align-items-center"><span class="fs--14px me-2"></span><i class="las la-bars"></i></p>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="bottomMenu">
            <ul class="navbar-nav text-center">
                @stack('bottom-menu')
            </ul>
        </div>
    </div>
</nav>
@endif
