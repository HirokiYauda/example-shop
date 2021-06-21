@php
    $sort_options = [
        'update_desc' => "更新日降順",
        'update_asc' => "更新日昇順",
        'price_desc' => "価格の高い順",
        'price_asc' => "価格の安い順"
    ];
@endphp

<div class="col-12 clearfix mb-3 pr-0">
    <div class="float-right">
        <select id="sort" name="sort" class="form-control">
            @foreach ($sort_options as $key => $sort_option)
                <option value="{{$key}}" {{$key === request('sort', "") ? 'selected' : ""}}>{{$sort_option}}</option>
            @endforeach
        </select>
    </div>
</div>