{{--
  Grouped department <select> partial.

  Parameters (passed via @include):
    $name        - input name attribute (default: 'department')
    $selected    - currently selected dept id (default: null)
    $allLabel    - text for the "all" option (default: 'All Departments')
    $class       - extra CSS classes for the <select>
    $style       - inline style string (default: '')
    $multiple    - if true, renders a multi-select (name becomes $name[])
    $departments - array of grouped depts from getGroupedDepartments()

  When $multiple is false (the normal filter case), the dropdown renders:
    -- All Departments --
    ▸ Lagos State          (selectable — filters all centers in Lagos)
      ↳ Ojodu Center       (selectable — filters that center only)
      ↳ Gbagada Center
    ▸ Kano State
      ↳ Sharada Center
      ...

  When $multiple is true (department report), it renders optgroups with
  only the center options selectable (states are just group headers).
--}}
@php
    $name      = $name     ?? 'department';
    $allLabel  = $allLabel ?? 'All Departments';
    $selected  = $selected ?? null;
    $multiple  = $multiple ?? false;
    $class     = $class    ?? '';
    $style     = $style    ?? '';
    $nameAttr  = $multiple ? $name . '[]' : $name;
@endphp

<select name="{{ $nameAttr }}"
        {{ $multiple ? 'multiple' : '' }}
        class="ts-select {{ $class }}"
        @if($style) style="{{ $style }}" @endif>

    @if(!$multiple)
        {{-- "All" option --}}
        <option value="">{{ $allLabel }}</option>

        @foreach($departments as $state)
            {{-- State row — selectable, filters ALL centers in this state --}}
            @php $stateSelected = (string)$selected === (string)$state['id']; @endphp
            <option value="{{ $state['id'] }}"
                    {{ $stateSelected ? 'selected' : '' }}
                    data-dept-type="state">
                {{ $state['title'] }}
            </option>

            {{-- Center rows — indented --}}
            @foreach($state['centers'] as $center)
                @php $cSelected = (string)$selected === (string)$center->id; @endphp
                <option value="{{ $center->id }}" {{ $cSelected ? 'selected' : '' }}
                        data-dept-type="center">
                    {{ $center->title }}
                </option>
            @endforeach
        @endforeach

    @else
        {{-- Multi-select mode (department report): states as optgroup headers only --}}
        @foreach($departments as $state)
            <optgroup label="▸ {{ $state['title'] }}">
                @foreach($state['centers'] as $center)
                    @php
                        $isSelected = in_array((string)$center->id, array_map('strval', (array) $selected));
                    @endphp
                    <option value="{{ $center->id }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $center->title }}
                    </option>
                @endforeach
            </optgroup>
        @endforeach
    @endif

</select>
