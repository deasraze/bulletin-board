<div class="search-bar pt-3">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <form action="{{ $route }}" method="GET">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="text"
                                       value="{{ request('text') }}" placeholder="Search for...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="number" class="form-control" name="price_from"
                                       value="{{ request('price_from') }}" placeholder="Price from">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="number" class="form-control" name="price_to"
                                       value="{{ request('price_to') }}" placeholder="Price to">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <button class="btn btn-light border" type="submit"><span class="fa fa-search"></span> Search</button>
                            </div>
                        </div>
                    </div>

                    @if ($category)
                        <div class="row">
                            @foreach ($category->allAttributes() as $attribute)
                                @if ($attribute->isSelect() || $attribute->isNumber())
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-form-label">{{ $attribute->name }}</label>

                                            @if ($attribute->isSelect())
                                                <select class="form-control" name="attrs[{{ $attribute->id }}][equals]">
                                                    <option value=""></option>
                                                    @foreach ($attribute->variants as $variant)
                                                        <option value="{{ $variant }}"
                                                            {{ $variant === request()->input('attrs.' . $attribute->id . '.equals') ? ' selected' : '' }}>
                                                            {{ $variant }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            @elseif ($attribute->isNumber())
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control"
                                                               name="attrs[{{ $attribute->id }}][from]"
                                                               value="{{ request()->input('attrs.' . $attribute->id . '.from') }}" placeholder="From">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <input type="number" class="form-control"
                                                               name="attrs[{{ $attribute->id }}][to]"
                                                               value="{{ request()->input('attrs.' . $attribute->id . '.to') }}" placeholder="To">
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </form>
            </div>
            <div class="col-md-3 text-right">
                <p>
                    <a href="{{ route('cabinet.adverts.create') }}" class="btn btn-success">
                        <span class="fa fa-plus"></span> Add New Advertisement
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
