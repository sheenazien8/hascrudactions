<div>
  @if ($withoutcard)
    <div class="table-responsive">
      <table class="table table-hover" id="{{ $resources }}-table">
        <thead>
          <tr>
            @if (!$withoutcheckbox)
              @include('partials.table.select-all')
            @endif
            {{ $thead }}
            @if (!$withoutTime)
              <th> {{ __('app.global.created_at') }}</th>
            @endif
            @if (!$withoutbulk)
              <th></th>
            @endif
          </tr>
        </thead>
        @if (isset($tbody))
          <tbody>
            <form method="POST" accept-charset="utf-8">
              {{ $tbody }}
            </form>
          </tbody>
        @endif
      </table>
    </div>
  @else
    <div class="card">
      <div class="card-header">
          <h4 class="pt-3 pb-3">{{ $title }}</h4>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover" id="{{ $resources }}-table">
            <thead>
              <tr>
                <form method="POST" accept-charset="utf-8">
                  @if (!$withoutcheckbox)
                    @include('hascrudactions::partials.table.select-all')
                  @endif
                </form>
                {{ $thead }}
                @if (!$withoutTime)
                  <th> {{ __('hascrudactions::app.global.created_at') }}</th>
                @endif
                @if (!$withoutaction)
                  <th></th>
                @endif
              </tr>
            </thead>
          </table>
        </div>
      </div>
      <div class="card-footer">
      </div>
    </div>
  @endif
</div>

@push('js')
  {{-- @if (!$withoutaction) --}}
  {{--   <script> --}}
  {{--     $(() => { --}}
  {{--       $("input.select-all").click(function(){ --}}
  {{--         $('input:checkbox').not(this).prop('checked', this.checked); --}}
  {{--       }); --}}
  {{--       $('#{{ $resources }}-table').on('change', 'input:checkbox', function(event) { --}}
  {{--         console.log(event.target) --}}
  {{--       }) --}}
  {{--       $('.bulk-action').on('click', function(ev) { --}}
  {{--         let checkbox = $('#{{ $resources }}-table input:checkbox'); --}}
  {{--         let array = [] --}}
  {{--         for(let a = 0; a < checkbox.length; a++) { --}}
  {{--           if($(checkbox[a]).attr('class') != 'select-all') { --}}
  {{--             let checked = $(checkbox[a]).prop('checked'); --}}
  {{--             if(checked) { --}}
  {{--               array.push($(checkbox[a]).val()) --}}
  {{--             } --}}
  {{--           } --}}
  {{--         } --}}
  {{--         let url = '{{ route($resources.'.bulk-destroy') }}'; --}}
  {{--         let token = $('meta[name="csrf-token"]'); --}}
  {{--         $.ajax({ --}}
  {{--           url: url, --}}
  {{--           method: 'DELETE', --}}
  {{--           data: { --}}
  {{--             _token: $(token[0]).attr('content') --}}
  {{--           } --}}
  {{--         }) --}}
  {{--       }) --}}
  {{--     }) --}}
  {{--   </script> --}}
  {{-- @endif --}}
@endpush

