<div class="col-md-12 pl-0">
    <div class="col-md-5 pl-0">
        <h3 class = "mt-0">Security</h3>
    </div>
</div>
<form action="{{ route('admin-security') }}" method="post" id = "update-security">
                  {{ csrf_field() }}
                <input type="hidden" name = "id" value = "{{ $user->id }}"> 
                <div class="form-group">
                  <label>Old Password</label>
                  <input type="password" class="form-control" placeholder="Old Password" name = "old_password" required>
                  {!! $errors->first('old_password', '<p class="text-danger">:message</p>') !!}
                </div>
                <div class="form-group">
                  <label>New Password</label>
                  <input type="password" class="form-control" placeholder="New Password" name = "password" required>
                  {!! $errors->first('password', '<p class="text-danger">:message</p>') !!}
                </div>
                <div class="form-group">
                  <label>Confirm Password</label>
                  <input type="password" class="form-control" placeholder="Confirm Password" name = "password_confirmation" required>
                  {!! $errors->first('password_confirmation', '<p class="text-danger">:message</p>') !!}
                </div>
                <div class="form-group">
                      
                        <button type="submit" class="btn btn-danger">Update</button>
                        <span id = "success"></span>
                </div>
</form>