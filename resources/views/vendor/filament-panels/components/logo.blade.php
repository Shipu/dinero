@if(Route::current()->getName() === 'filament.hub.auth.login')
    <img src="{{asset('brands/dinero-logo.png')}}"
         alt="{{config('app.name')}}"
         title="{{config('app.name')}}"
         width="290"
         class="mb-2"
    >
@else
    <img src="{{asset('brands/dinero-logo-adm.png')}}"
         alt="{{config('app.name')}}"
         title="{{config('app.name')}}"
         width="140"
    >
@endif

{{-- Precisa somente de uma condição para trocar as logos quando sistema esta dark e light e retirar o style --}}
