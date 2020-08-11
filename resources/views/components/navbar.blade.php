<nav class="bg-purple-900" x-data="{ open: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <x-logo class="w-auto h-8 mx-auto text-white" />
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <div class="ml-3 relative">
                        <a href="https://caycehouse.com"
                            class="font-medium text-white hover:text-yellow-300 focus:outline-none focus:underline transition ease-in-out duration-150"
                            target="_blank">
                            Cayce House</a>
                    </div>
                </div>
            </div>
            <div class="-mr-2 flex md:hidden">
                <!-- Mobile menu button -->
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
                    <!-- Menu open: "hidden", Menu closed: "block" -->
                    <svg :class="{ 'hidden': open, 'block': !open}" class="block h-6 w-6" stroke="currentColor"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <!-- Menu open: "block", Menu closed: "hidden" -->
                    <svg :class="{ 'hidden': !open, 'block': open}" class="hidden h-6 w-6" stroke="currentColor"
                        fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!--
      Mobile menu, toggle classes based on menu state.

      Open: "block", closed: "hidden"
    -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden md:hidden ">
        <div class="px-2 pt-2 pb-3 sm:px-3">
            <a href="https://caycehouse.com"
                class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-white hover:text-yellow-300 hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                target="_blank">
                Cayce House
            </a>
        </div>
    </div>
</nav>
