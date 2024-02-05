<div class="appBottomMenu">
    <a href="/home" class="item {{ request()->is('home') ? 'active' : '' }}">
        <div class="col">

            <ion-icon name="bar-chart-outline" role="img" class="md hydrated" aria-label="home-outline"></ion-icon>
            <strong>Dashboard</strong>
        </div>
    </a>
    <a href="/report" class="item {{ request()->is('report') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-attach-outline"></ion-icon>
            <strong>Report</strong>
        </div>
    </a>
    {{-- <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="home-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </div>
        </div>
    </a> --}}
    <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="home-outline">Home</ion-icon>
            </div>
        </div>
    </a>
    <a href="/labakas" class="item {{ request()->is('labakas') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="cash-outline"></ion-icon>
            <strong>Laba</strong>
        </div>
    </a>
    <a href="/editprofile" class="item {{ request()->is('editprofile') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="people-outline" role="img" class="md hydrated" aria-label="people outline"></ion-icon>
            <strong>Profile</strong>
        </div>
    </a>
</div>
