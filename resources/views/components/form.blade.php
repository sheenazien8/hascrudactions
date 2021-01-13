<div class="d-flex justify-content-center">
  <div class="{{ $card ? 'card' : ''}} col-md-{{ $size }} p-0">
    <form action="{{ $route }}" method="POST" enctype="multipart/form-data">
      @if ($card)
        <div class="card-header">
          <h4>{{ $title }}</h4>
        </div>
      @endif
      <div class="{{ $card ? 'card-body' : '' }}">
        @csrf
        @method($method)
        {{ $slot }}
      </div>
      <div class="{{ $card ? 'card-footer' : '' }}">
        <div class="{{ $card ? '' : 'col-12' }}">
          <x-components-button :title="__('hascrudactions::app.global.submit')" :submit-icon="true"/>
        </div>
      </div>
    </form>
  </div>
</div>

