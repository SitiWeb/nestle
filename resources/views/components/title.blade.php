@isset($class)
  
@else
    @php $class= ''; @endphp
@endisset
<div>
    <h2 class="text-left text-4xl font-bold mb-4 text-center {{$class}}">{{$text}}</h2>
</div> 