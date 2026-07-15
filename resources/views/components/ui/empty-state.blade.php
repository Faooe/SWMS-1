@props([

'title',

'description'=>null

])

<div class="py-16 text-center">

<div
class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-slate-100">

<i
data-lucide="inbox"
class="h-10 w-10 text-slate-400">
</i>

</div>

<h3
class="text-xl font-bold">

{{ $title }}

</h3>

@if($description)

<p
class="mt-2 text-slate-500">

{{ $description }}

</p>

@endif

@if(trim($slot))

<div class="mt-8">

{{ $slot }}

</div>

@endif

</div>