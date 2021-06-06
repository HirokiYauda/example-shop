<div class="mb-3">
    <span class="p-country-name" style="display:none;">Japan</span>
    <label for="zip" class="form-label">郵便番号</label>
    <input type="text" name="zip" value="{{old('zip', $user->zip ?? "")}}" class="p-postal-code form-control" id="zip" size="8" maxlength="8">
</div>
<div class="mb-3">
    <label for="pref_id" class="form-label">都道府県</label>
    <select name="pref_id" class="form-control p-region-id" id="pref_id">
        @foreach ($prefs as $pref)
            <option value="{{$pref->id}}" @if(old('pref_id', $user->pref_id ?? 0) == $pref->id) selected @endif>{{$pref->name}}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label for="address1" class="form-label">住所1</label>
    <input type="text" value="{{old('address1', $user->address1 ?? "")}}" name="address1"
        class="form-control p-locality p-street-address p-extended-address" id="address1" placeholder="市、区、町、丁目、番地、号" />
</div>
<div class="mb-3">
    <label for="address2" class="form-label">住所2</label>
    <input type="text" value="{{old('address2', $user->address2 ?? "")}}" name="address2" class="form-control" id="address2" placeholder="マンション、アパート、部屋番号、等" />
</div>