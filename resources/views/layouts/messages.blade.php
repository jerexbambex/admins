{{-- <script>
    Swal.fire('Success!', "Hello", 'success');
</script> --}}
<script>
    let rendered = false;
</script>
@if(Session::has('success_alert'))
<script>
    if(!rendered)
    Swal.fire('Success!', @json(session()->get('success_alert')), 'success');
</script>
<div class="alert alert-success"><?=session()->get('success_alert')?></div>
@endif

@if(Session::has('error_alert'))
<script>
    if(!rendered)
    Swal.fire('Error!', @json(session()->get('error_alert')), 'error');
</script>
<div class="alert alert-danger"><?=session()->get('error_alert')?></div>
@endif

@if(Session::has('success_toast'))
<script>
    if(!rendered)
    toastr.success(@json(session()->get('success_toast')), 'Success!');
</script>
<div class="alert alert-success"><?=session()->get('success_toast')?></div>
@endif

@if(Session::has('error_toast'))
<script>
    if(!rendered)
    toastr.error(@json(session()->get('error_toast')), 'Error!');
</script>
<div class="alert alert-danger"><?=session()->get('error_toast')?></div>
@endif
