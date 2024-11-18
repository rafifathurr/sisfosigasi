<!DOCTYPE html>
<html lang="en"> <!--begin::Head-->

@include('layout.head')

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary"> <!--begin::App Wrapper-->
    <div class="app-wrapper"> <!--begin::Header-->
        @include('layout.navbar')
        @include('layout.sidebar')
        <main class="app-main"> <!--begin::App Content Header-->
            <!--end::App Content Header--> <!--begin::App Content-->
            @yield('content')
        </main> <!--end::App Main--> <!--begin::Footer-->
        @include('layout.footer')
    </div> <!--end::App Wrapper--> <!--begin::Script--> <!--begin::Third Party Plugin(OverlayScrollbars)-->
    @include('layout.script')
</body><!--end::Body-->

</html>
