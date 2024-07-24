@if (session()->has('message'))
    <div class="block-warning type-custom w-full fs-4 my-3">
        {!! session('message')  !!}
    </div>
@endif

@if (session()->has('error'))
    <div class="block-warning type-danger w-full fs-4 my-3">
        {!!  session('error') !!}
    </div>
@endif
