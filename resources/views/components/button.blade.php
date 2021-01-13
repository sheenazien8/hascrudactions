<button
    type="{{ $to ? 'button' : 'submit' }}"
    class="btn btn-{{ $color }} btn-{{ $size }} {{ $block ? 'btn-block' : '' }}"
    id="button-create"
    onclick="to('{{ $to }}')">
    @if ($submitIcon)
      <span>
        <svg style="width:15px; margin-right:2px; margin-bottom:4px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
        </svg>
      </span>
    @endif
    @if ($icon)
        <span class="{{ $icon }}" id="icon">{{ $icon }}</span>
    @endif
    <div class="spinner-grow spinner-grow-sm mb-1 d-none" role="status" id="spinner"></div>
    <span id="title" class="my-1">{{ $title }}</span></button>

<script charset="utf-8" defer>
    function to(to) {
        let buttonCreate = document.getElementById('button-create').setAttribute("disabled", true);
        let icon = document.getElementById('icon').classList.add("d-none");;
        let title = document.getElementById('title').innerHTML = 'Loading...';
        let spinner = document.getElementById('spinner').classList.remove("d-none");;
        if(to) {
            window.location = to
        }
    }
</script>
