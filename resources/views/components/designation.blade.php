<div class="form-group row required">
    <label for="designation" class="col-form-label">Designation</label>
    <input type="text" id="designation" name="designation" placeholder="Designation ..."
        class="form-control @error('designation') is-invalid @enderror" value="{{old('designation')}}" required>
    @error('designation')
    <span class="invalid-feedback" role="alert">
        <strong>{{ $message }}</strong>
    </span>
    @enderror
</div>