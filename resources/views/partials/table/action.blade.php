<span>
  @if (isset($delete))
  <a class="btn btn-primary delete-row" id="{{ $model->id }}"
    data-confirm=" {{ __('hascrudactions::app.global.suredelete') }}" data-method="DELETE" href="{{ $delete }}"> {{ __('hascrudactions::app.global.delete') }}</a>
  @endif
</span>
