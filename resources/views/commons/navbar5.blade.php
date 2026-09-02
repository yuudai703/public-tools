<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container" style="font-family: 'ヒラギノ明朝 Pro W3', 'Hiragino Mincho Pro', '游明朝','Yu Mincho', '游明朝体', 'YuMincho','ＭＳ Ｐ明朝', 'MS PMincho', serif;">
            <a class="navbar-brand" href="#">情報共有システム - {{ config('global.company') }}</a>
                        &nbsp;&nbsp;&nbsp;&nbsp;<lo style="color:white;">{!! link_to_route('scheduleIndex', 'スケジュール', [], ['class' => 'dropdown-item']) !!}</lo>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<lo style="color:white;">{!! link_to_route('calendar', '休日登録', [], ['class' => 'dropdown-item']) !!}</lo>
                    
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon">

                    
                </span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <lo>{!! link_to_route('scheduleIndex', 'スケジュール', [], ['class' => 'dropdown-item']) !!}</lo>
                        <lo>{!! link_to_route('calendar', '休日登録', [], ['class' => 'dropdown-item']) !!}</lo>
                    </ul>
                    @if (Auth::check())
                        <li class="nav-item">
                            {{-- <a class="nav-link" href="/">トップに戻る</a> --}}
                        </li>
                        <!-- <li class="navbar-brand">{{ Auth::user()->username }}</li> -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{-- {{ Auth::user()->name }} --}}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li>{!! link_to_route('scheduleIndex', 'スケジュール', [], ['class' => 'dropdown-item']) !!}</li>
                                <li>{!! link_to_route('calendar', '休日登録', ["id"=>1], ['class' => 'dropdown-item']) !!}</li>
                            </ul>
                        </li>
                    @else

                        <li class="nav-item">
                            {{-- {!! link_to_route('login.get', 'Login', [], ['class' => 'nav-link']) !!} --}}
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</header>
