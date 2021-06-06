<x-app-layout>
    <x-slot name="header">
        <div class="bg-white">
            <h3 class="text-center"><a href="/year/{{$year-1}}">{{$year-1}}</a> <span class="text-4xl">{{$year}}</span> <a href="/year/{{$year+1}}">{{$year+1}}</a></h3>
        </div>
    </x-slot>

    <br>

    @if(count($categories) == 0)
        <div class="p-6 bg-white text-center">
            You must create at least one <a href="/categories">category</a> to continue.
        </div>
    @else
        <div class="p-6 bg-white">
            <h3 class="text-center pb-6 text-2xl">Projected</h3>

            <livewire:projected :year="$year" />
        </div>

        <br>

       
        <div class="p-6 bg-white mb-8">
            <h3 class="text-center pb-6 text-2xl">Actual</h3>
                    
            <livewire:actual :year="$year" />
        </div>
    @endif
    
    <script>
        function clearHover() {
            if($('table:hover').length == 0) {
                $(".hovered").each(function(i) {
                    $(this).removeClass("hovered");
                });
            }
            setTimeout(clearHover, 1000);
        }

        window.addEventListener("load", function() {
            $("td").hover(function() {
                $(".hovered").each(function(i) {
                    $(this).removeClass("hovered");
                });
                $(this).parent("tr").find("td").each(function(i) {
                    $(this).addClass("hovered");
                });
                var idx = $(this).index();

                $(this).closest("table").find("tr").each(function(i) {
                    $(this).children("td").filter(":nth-child(" + (idx+1) + ")").addClass("hovered");
                });
            });

            clearHover();
        }); 
    </script>
</x-app-layout>
