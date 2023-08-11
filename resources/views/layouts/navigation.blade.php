<!-- Sidebar component, swap this element with another sidebar if you like -->
<div class="h-full min-h-screen flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4 border">




    <nav x-data="{ open: false }" class="bg-white border-b border-gray-100 flex flex-1 flex-col ">
        <div class="text-center">
            <h1 class="mt-6 text-2xl font-bold">Welcome back</h1>
            <p class="">{{ auth()->user()->email }}</p>

            <ul role="list" class="-mx-2 space-y-1 inline-block mt-12">
                @foreach($menuItems as $menuItem)
                <li>
                    <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                    <a href="{{ $menuItem->url }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold
                {{ request()->getRequestUri() == $menuItem->url ? 'bg-neutral-200' : 'text-gray-700 hover:bg-neutral-200' }}">
                        <x-image-component src="{{$menuItem->icon}}" class="custom-class" style="object-fit: none;" />
                        {{ $menuItem->title }}
                    </a>
                </li>
                @endforeach

                @role('editor')
                <hr>
                <li>
                    <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                    <a href="{{ route('units.create') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold
            {{ request()->getRequestUri() == '/units' ? 'bg-neutral-200' : 'text-gray-700 hover:bg-neutral-200' }}">
                        Create unit
                    </a>
                </li>
                <li>
                    <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                    <a href="{{ route('locations.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold
            {{ request()->getRequestUri() == '/locations' ? 'bg-neutral-200' : 'text-gray-700 hover:bg-neutral-200' }}">
                        Manage Locations
                    </a>
                </li>
                <li>
                    <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                    <a href="{{ route('brands.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold
            {{ request()->getRequestUri() == '/brands' ? 'bg-neutral-200' : 'text-gray-700 hover:bg-neutral-200' }}">
                        Manage Brands
                    </a>
                </li>
                <li>
                    <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                    <a href="{{ route('units.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold
            {{ request()->getRequestUri() == '/units' ? 'bg-neutral-200' : 'text-gray-700 hover:bg-neutral-200' }}">
                        Manage Units
                    </a>
                </li>
                <li>
                    <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                    <a href="{{ route('import') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold
            {{ request()->getRequestUri() == '/import' ? 'bg-neutral-200' : 'text-gray-700 hover:bg-neutral-200' }}">
                        Import
                    </a>
                </li>
                @endrole

                @role('admin')
                <li>
                    <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                    <a href="{{ route('users.index') }}" class="group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold
            {{ request()->getRequestUri() == '/users' ? 'bg-neutral-200' : 'text-gray-700 hover:bg-neutral-200' }}">
                        Manage Users
                    </a>
                </li>
               
                @endrole

            </ul>



    </nav>


    <div class="mb-12 text-center flex justify-center">
       
                <!-- Current: "bg-gray-50 text-indigo-600", Default: "text-gray-700 hover:text-indigo-600 hover:bg-gray-50" -->
                <a href="{{ route('logout') }}" class="text-center group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold
            {{ request()->getRequestUri() == '/logout' ? 'bg-neutral-200' : 'text-gray-700 hover:bg-neutral-200' }}">
                    Sign out
                </a>
         
    </div>
</div>