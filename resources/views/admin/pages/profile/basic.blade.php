<div class="col-md-12 pl-0">
  <div class="col-md-5 pl-0">
    <h3 class = "mt-0">Basic Details</h3>
  </div>
</div>
<form action="{{ route('admin-basic-profile') }}" method="post">
  {{ csrf_field() }}
  <input type="hidden" name = "id" value = "{{ $user->id }}">
  <div class="form-group">
    <label>Name</label>
    <input type="text" class="form-control" placeholder="Name" value="{{  $user->name }}" name = "name" required>
    {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
  </div>
  
  <div class="form-group">
    <label>Email</label>
    <input type="email" class="form-control" placeholder="Email" value="{{  $user->email }}" disabled="true" name = "email" required>
  </div>
  <div class="form-group">
    <label>Contact</label>
    <input type="tel" class="form-control validate_number" maxlength="10" placeholder="Contact" value="{{  $user->contact }}" name = "contact" required>
    {!! $errors->first('contact', '<p class="text-danger">:message</p>') !!}
  </div>
  
  <div class="form-group">
    <button type="submit" class="btn btn-danger">Update</button>
  </div>
</form>