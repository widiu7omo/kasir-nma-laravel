<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">
        <a href="http://www.creative-tim.com" class="simple-text logo-mini">
            <div class="logo-image-small">
                <img src="{{ asset('paper') }}/img/logo-small.png">
            </div>
        </a>
        <a href="http://www.creative-tim.com" class="simple-text logo-normal">
            {{ __('KASIR PMA') }}
        </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="{{ $elementActive == 'dashboard' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'home') }}">
                    <i class="nc-icon nc-bank"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'kwitansi' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'kwitansi') }}">
                    <i class="nc-icon nc-diamond"></i>
                    <p>{{ __('Kwitansi') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'timbangan' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'timbangan') }}">
                    <i class="nc-icon nc-caps-small"></i>
                    <p>{{ __('Data Timbangan') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'laporan' ? 'active' : '' }}">
                <a data-toggle="collapse" aria-expanded="false" href="#laravelExamples">
                    <i class="nc-icon"><img src="{{ asset('paper/img/laravel.svg') }}"></i>
                    <p>
                        {{ __('Laporan') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse" id="laravelExamples">
                    <ul class="nav">
                        <li class="{{ $elementActive == 'harian' ? 'active' : '' }}">
                            <a href="{{ route('laporan.index','harian') }}">
                                <span class="sidebar-mini-icon">{{ __('UP') }}</span>
                                <span class="sidebar-normal">{{ __(' Harian ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'mingguan' ? 'active' : '' }}">
                            <a href="{{ route('laporan.index', 'mingguan') }}">
                                <span class="sidebar-mini-icon">{{ __('U') }}</span>
                                <span class="sidebar-normal">{{ __(' Mingguan ') }}</span>
                            </a>
                        </li>
                        <li class="{{ $elementActive == 'bulanan' ? 'active' : '' }}">
                            <a href="{{ route('laporan.index', 'bulanan') }}">
                                <span class="sidebar-mini-icon">{{ __('U') }}</span>
                                <span class="sidebar-normal">{{ __(' Bulanan ') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="{{ $elementActive == 'harga' ? 'active' : '' }}">
                <a href="{{ route('harga.index') }}">
                    <i class="nc-icon nc-pin-3"></i>
                    <p>{{ __('Harga Sawit') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'spb' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'spb') }}">
                    <i class="nc-icon nc-tile-56"></i>
                    <p>{{ __('Pemilik SPB') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'petani' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'petani') }}">
                    <i class="nc-icon nc-book-bookmark"></i>
                    <p>{{ __('Petani') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'korlap' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'korlap') }}">
                    <i class="nc-icon nc-bell-55"></i>
                    <p>{{ __('Korlap') }}</p>
                </a>
            </li>
            <li class="{{ $elementActive == 'user' ? 'active' : '' }}">
                <a href="{{ route('page.index', 'user') }}">
                    <i class="nc-icon nc-spaceship"></i>
                    <p>{{ __('Data Akun') }}</p>
                </a>
            </li>
        </ul>
    </div>
</div>
