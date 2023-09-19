    <div class="search-bar pt-3">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    <form action="{{ route('adverts.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-11">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="text"
                                        value="{{ request('text') }}"
                                        placeholder="Город, адрес, метро, район, ж/д, шоссе или ЖК...">
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <button class="btn btn-light border" type="submit"><span
                                            class="fa fa-search"></span></button>
                                </div>
                            </div>
                        </div>
                        @if ($category)
                            @foreach ($category->allAttributes() as $attribute)
                                <div class="form-group">
                                    <input type="text" class="form-control"
                                        placeholder="Город, адрес, метро, район, ж/д, шоссе или ЖК..."
                                        name="attrs[{{ $attribute->id }}][equals]"
                                        value="{{ request()->input('attrs.' . $attribute->id . '.equals') }}">

                                </div>
                            @endforeach
                        @endif
                    </form>
                </div>
                <div class="col-md-3" style="text-align: right">
                    <p><a href="{{ route('cabinet.adverts.create') }}" class="btn btn-primary"><span
                                class="fa fa-plus"></span> Разместить объявление</a></p>
                </div>
            </div>
        </div>
    </div>
