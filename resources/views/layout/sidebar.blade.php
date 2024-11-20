<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark"> <!--begin::Sidebar Brand-->
    <div class="sidebar-brand"> <!--begin::Brand Link-->
        <a href="./index.html" class="brand-link">
            <!--begin::Brand Image-->
            {{-- <img src="../../dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"> --}}
            <!--end::Brand Image--> <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span> <!--end::Brand Text-->
        </a>
        <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand--> <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="#" class="nav-link"> <i class="nav-icon bi bi-speedometer"></i>
                        <p>Dash Board</p>
                    </a>
                </li>
                <li class="nav-header">Barang</li>
                <li class="nav-item">
                    <a href="{{ route('jenis-barang.index') }}" class="nav-link"> <i
                            class="nav-icon bi bi-inboxes-fill"></i>
                        <p>Jenis Barang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('barang.index') }}" class="nav-link"> <i class="nav-icon bi bi-inbox-fill"></i>
                        <p>Barang</p>
                    </a>
                </li>
                <li class="nav-header">Penduduk</li>
                <li class="nav-item">
                    <a href="{{ route('kelompok.index') }}" class="nav-link"> <i class="nav-icon bi bi-people-fill"></i>
                        <p>Kelompok</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('penduduk.index') }}" class="nav-link"> <i class="nav-icon bi bi-person-fill"></i>
                        <p>Penduduk</p>
                    </a>
                </li>
                <li class="nav-header">Bantuan</li>
                <li class="nav-item">
                    <a href="{{ route('bantuan.index') }}" class="nav-link"> <i
                            class="nav-icon bi bi-box2-heart-fill"></i>
                        <p>Bantuan</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div> <!--end::Sidebar Wrapper-->
</aside> <!--end::Sidebar--> <!--begin::App Main-->
