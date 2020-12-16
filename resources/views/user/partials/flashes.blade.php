@if(session('flash_message'))
    <div id="flash_message" class="flash_message bg-{{ session('success') ? 'success' : 'danger' }}">
        <span>
            {{ session()->get('flash_message') }}
        </span>
    </div>
@endif
<?php //echo "<pre>"; print_r($errors); exit; ?>
@if($errors->any() && 1==2)
    <div id="flash_message" class="flash_message bg-danger">
        <span>
            {!! implode('', $errors->all('<div>:message</div>')) !!}
        </span>
    </div>
@endif

@if(session('success'))
    <div id="flash_message" class="flash_message bg-success">
        <span>
            {{ session()->get('success') }}
        </span>
    </div>
@endif