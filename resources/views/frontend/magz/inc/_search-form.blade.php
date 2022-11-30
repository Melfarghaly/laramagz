<form action="{{ route('search') }}" method="GET" class="search" autocomplete="off">
    <div class="form-group">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Type something here">
            <div class="input-group-btn">
                <button type="submit" class="btn btn-primary"><i class="ion-search"></i></button>
            </div>
        </div>
    </div>
</form>
