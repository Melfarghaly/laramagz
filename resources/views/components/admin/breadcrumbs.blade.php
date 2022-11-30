<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">{{ $title }}</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                    @foreach( $addLink as $key => $value )
                        <li class="breadcrumb-item">
                            <a href="{{ $key }}">{{ $value }}</a>
                        </li>
                    @endforeach
                    @isset($currentActive)
                        <li class="breadcrumb-item active">{{ $currentActive }}</li>
                    @endisset
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
