<nav style="" class="bg-gray-300 h-10 border-gray-200 dark:border-gray-600 dark:bg-gray-900">
    <div class="flex flex-wrap items-center mx-auto max-w-screen-xl p-[2px]">
        <button class="drawer-navigation text-center text-black hover:bg-blue-400 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-xs px-3 py-[3px] dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" type="button" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation" aria-controls="drawer-navigation">
            <svg class="ml-[11px] w-[15px] h-[15px] text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
            </svg>
            MENU  
        </button>
        <span class="text-left systemTitle text-2xl font-semibold whitespace-nowrap dark:text-white ml-4">積算-System</span>


        @if(fnmatch("*/mitumoriSetubi*", $_SERVER['REQUEST_URI']))
            <span class="text-left setubiTitle text-2xl font-semibold whitespace-nowrap dark:text-white ml-4">設備工事</span>
        @elseif(fnmatch("*/mitumori*", $_SERVER['REQUEST_URI']))
            <span class="text-left denkiTitle text-2xl font-semibold whitespace-nowrap dark:text-white ml-4">電気工事</span>
        @endif
    </div>
</nav>

<style>
    /*https://nextage-tech.com/blog/2025/12/08/post-4349/#outline__6_1*/
    .denkiTitle{ 
        font-size: 24px;
        font-weight: 900;
        color: #9d9d01;
        text-shadow: 
            1px 1px 0 #5e5e5e,
            4px 4px 5px #7e7e7e;
    }
    .setubiTitle{ 
        font-size: 24px;
        font-weight: 900;
        color: #207a6c;
        text-shadow: 
            1px 1px 0 #5e5e5e,
            4px 4px 5px #7e7e7e;
    }
    .systemTitle{ 
        font-size: 24px;
        font-weight: 900;
        color: #1a1a1a00;
        text-shadow: 
            1px 1px 0 #5e5e5e,
            4px 4px 5px #7e7e7e;
    }
</style>