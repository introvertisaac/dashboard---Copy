@if (session()->has('message'))
    <div class="block-warning type-custom w-full fs-4 my-3">
        {!! session('message')  !!}
    </div>
        <?php session()->forget('message'); ?>
@endif

@if (session()->has('error'))
    <div class="block-warning type-danger w-full fs-4 my-3">
        {!!  session('error') !!}
    </div>
        <?php session()->forget('error'); ?>
@endif
