<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" @close="closeModal" class="relative z-10">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4 text-center">
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all" >
                            <DialogTitle
                                as="h3"
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                Event Details
                            </DialogTitle>
                            <div class="mt-2">
                                <div class="w-full px-4 pt-2">
                                    <div class="mx-auto w-full max-w-md rounded-2xl bg-white p-2">
                                        <Disclosure v-for="detailedEvent in detailedEvents" v-slot="{ open }" as="div" class="mt-2">
                                            <DisclosureButton
                                            class="flex w-full justify-between rounded-lg bg-purple-100 px-4 py-2 text-left text-sm font-medium text-purple-900 hover:bg-purple-200 focus:outline-none focus-visible:ring focus-visible:ring-purple-500 focus-visible:ring-opacity-75"
                                            >
                                                <span>{{ detailedEvent.user.name }} ({{ detailedEvent.request_type }})</span>
                                                <ChevronUpIcon
                                                    :class="open ? 'rotate-180 transform' : ''"
                                                    class="h-5 w-5 text-purple-500"
                                                />
                                            </DisclosureButton>
                                            <DisclosurePanel class="px-4 pt-4 pb-2 text-sm text-gray-500">
                                                <span class="mt-4 text-1xl font-bold text-yellow-700">{{detailedEvent.start_date}} {{detailedEvent.start_time}} ~ {{ detailedEvent.end_date }} {{detailedEvent.end_time}}</span><br/>
                                                {{ detailedEvent.reason }}
                                            </DisclosurePanel>
                                        </Disclosure>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 flex justify-center">
                                <button
                                type="button"
                                class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                @click="closeModal"
                                >
                                Close
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>

    <div class="lg:flex lg:h-full lg:flex-col">
        <header class="flex items-center justify-between border-b border-gray-200 px-6 py-4 lg:flex-none">
            <h1 class="text-base font-semibold leading-6 text-gray-900">
                <time datetime="2022-01">{{strMonth[tmonth-1]}} {{ tyear }}</time>
            </h1>
            <div class="flex items-center">
                <div class="relative flex items-center rounded-md bg-white shadow-sm md:items-stretch">
                    <button @click="clickedPreviousBtn()" type="button" class="flex h-9 w-12 items-center justify-center rounded-l-md border-y border-l border-gray-300 pr-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pr-0 md:hover:bg-gray-50">
                        <span class="sr-only">Previous month</span>
                        <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                    </button>
                    <button @click="clickTodayBtn()" type="button" class="hidden border-y border-gray-300 px-3.5 text-sm font-semibold text-gray-900 hover:bg-gray-50 focus:relative md:block">Today</button>
                    <span class="relative -mx-px h-5 w-px bg-gray-300 md:hidden" />
                    <button @click="clickedNextBtn()" type="button" class="flex h-9 w-12 items-center justify-center rounded-r-md border-y border-r border-gray-300 pl-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pl-0 md:hover:bg-gray-50">
                        <span class="sr-only">Next month</span>
                        <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                    </button>
                </div>
                <!-- <div class="hidden md:ml-4 md:flex md:items-center">
                <button type="button" class="ml-6 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">Add event</button>
                </div> -->
            </div>
        </header>
        <div class="shadow ring-1 ring-black ring-opacity-5 lg:flex lg:flex-auto lg:flex-col">
            <div class="grid grid-cols-7 gap-px border-b border-gray-300 bg-gray-200 text-center text-xs font-semibold leading-6 text-gray-700 lg:flex-none">
                <div class="bg-white py-2">M<span class="sr-only sm:not-sr-only">on</span></div>
                <div class="bg-white py-2">T<span class="sr-only sm:not-sr-only">ue</span></div>
                <div class="bg-white py-2">W<span class="sr-only sm:not-sr-only">ed</span></div>
                <div class="bg-white py-2">T<span class="sr-only sm:not-sr-only">hu</span></div>
                <div class="bg-white py-2">F<span class="sr-only sm:not-sr-only">ri</span></div>
                <div class="bg-white py-2">S<span class="sr-only sm:not-sr-only">at</span></div>
                <div class="bg-white py-2">S<span class="sr-only sm:not-sr-only">un</span></div>
            </div>
            <div class="flex bg-gray-200 text-xs leading-6 text-gray-700 lg:flex-auto">
                <div class="hidden w-full lg:grid lg:grid-cols-7 lg:grid-rows-6 lg:gap-px">
                    <div v-for="day in currentMonthDays" :key="day.date" :class="[day.isCurrentMonth ? 'bg-white' : 'bg-gray-50 text-gray-500', 'relative px-3 py-2', day.events.length > 0?'cursor-pointer' : '']" @click="day.events.length > 0?dayClicked(day):undefined">
                        <time :datetime="day.date" :class="day.isToday ? 'flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white' : undefined">{{ day.date.split('-').pop().replace(/^0/, '') }}</time>
                        <ol v-if="day.events.length > 0" class="mt-2">
                            <li v-for="event in day.events.slice(0, 2)" :key="event.id">
                                <p class="group flex">
                                    <p class="flex-auto truncate font-medium text-gray-900 group-hover:text-indigo-600">
                                        {{ event.name }}
                                    </p>
                                    <time :datetime="event.datetime" class="ml-3 hidden flex-none text-gray-500 group-hover:text-indigo-600 xl:block">{{ event.time }}</time>
                                </p>
                            </li>
                            <li v-if="day.events.length > 2" class="text-gray-500">+ {{ day.events.length - 2 }} more</li>
                        </ol>
                    </div>
                </div>
                <div class="isolate grid w-full grid-cols-7 grid-rows-6 gap-px lg:hidden">
                    <button v-for="day in currentMonthDays" :key="day.date" type="button" :class="[day.isCurrentMonth ? 'bg-white' : 'bg-gray-50', (day.isSelected || day.isToday) && 'font-semibold', day.isSelected && 'text-white', !day.isSelected && day.isToday && 'text-indigo-600', !day.isSelected && day.isCurrentMonth && !day.isToday && 'text-gray-900', !day.isSelected && !day.isCurrentMonth && !day.isToday && 'text-gray-500', 'flex h-14 flex-col px-3 py-2 hover:bg-gray-100 focus:z-10']" @click="day.events.length > 0?dayClicked(day):undefined">
                        <time :datetime="day.date" :class="[day.isSelected && 'flex h-6 w-6 items-center justify-center rounded-full', day.isSelected && day.isToday && 'bg-indigo-600', day.isSelected && !day.isToday && 'bg-gray-900', 'ml-auto']">{{ day.date.split('-').pop().replace(/^0/, '') }}</time>
                        <span class="sr-only">{{ day.events.length }} events</span>
                        <span v-if="day.events.length > 0" class="-mx-0.5 mt-auto flex flex-wrap-reverse">
                            <span v-for="event in day.events" :key="event.id" class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400" />
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <div v-if="selectedDay?.events.length > 0" class="px-4 py-10 sm:px-6 lg:hidden">
            <ol class="divide-y divide-gray-100 overflow-hidden rounded-lg bg-white text-sm shadow ring-1 ring-black ring-opacity-5">
                <li v-for="event in selectedDay.events" :key="event.id" class="group flex p-4 pr-6 focus-within:bg-gray-50 hover:bg-gray-50">
                    <div class="flex-auto">
                        <p class="font-semibold text-gray-900">{{ event.name }}</p>
                        <time :datetime="event.datetime" class="mt-2 flex items-center text-gray-700">
                        <ClockIcon class="mr-2 h-5 w-5 text-gray-400" aria-hidden="true" />
                        {{ event.time }}
                        </time>
                    </div>
                    <a :href="event.href" class="ml-6 flex-none self-center rounded-md bg-white px-3 py-2 font-semibold text-gray-900 opacity-0 shadow-sm ring-1 ring-inset ring-gray-300 hover:ring-gray-400 focus:opacity-100 group-hover:opacity-100"
                        >Edit<span class="sr-only">, {{ event.name }}</span></a
                    >
                </li>
            </ol>
        </div>
    </div>
</template>

<script setup>
import {
  ChevronDownIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  ChevronUpIcon,
  ClockIcon,
  EllipsisHorizontalIcon,
} from '@heroicons/vue/20/solid'
import {  Disclosure, 
          DisclosureButton, 
          DisclosurePanel, 
          Menu, MenuButton, 
          MenuItem, 
          MenuItems, 
          TransitionRoot,
          TransitionChild,
          Dialog,
          DialogPanel,
          DialogTitle, } from '@headlessui/vue'
import Modal from '@/Components/Modal.vue';
import {inject, ref} from 'vue'
import { onMounted } from 'vue';

const vacas = inject('acceptedVacations')
const strMonth = ['January', 'Febrary', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const tyear = ref(2023);
const tmonth = ref(1);
const detailedEvents = ref();
const isOpen = ref(false);
const currentDate = new Date();
const currentYear = currentDate.getFullYear();
const currentMonth = currentDate.getMonth() + 1;
const currentDay = currentDate.getDate();
const _userId = inject('userId');
const _userType = inject('userType');
var currentMonthDays = getDaysInMonth(currentYear, currentMonth);

function closeModal() {
  isOpen.value = false
}

function openModal() {
  isOpen.value = true
}

function clickedPreviousBtn(){
    if( tmonth.value == 1 )
    {
        tyear.value = tyear.value - 1;
        tmonth.value = 12;
    }
    else
        tmonth.value = tmonth.value-1;

    currentMonthDays = getDaysInMonth(tyear.value, tmonth.value);
}

function clickedNextBtn(){
    if( tmonth.value == 12 )
    {
        tyear.value = tyear.value + 1;
        tmonth.value = 1;
    }
    else
        tmonth.value = tmonth.value+1;
    
    currentMonthDays = getDaysInMonth(tyear.value, tmonth.value);
}

function clickTodayBtn(){
    tyear.value = currentYear;
    tmonth.value = currentMonth;
    currentMonthDays = getDaysInMonth(tyear.value, tmonth.value);
}

function dayClicked( day ){
    detailedEvents.value = getVacatonsInRange( tyear.value, tmonth.value, parseInt(day.date.split('-')[2]) );
    openModal();
}

function createEvents(day, mon, yr){
    let vacasArray=[];
    if (_userType == "manager"){
        vacasArray = vacas
    }else{
        vacasArray = vacas.filter(vaca=>_userId == vaca.user_id);
    }
    var events = [];
    for( const vaca of vacasArray ){
      //  var sDate = parseInt(vaca.start_date.split("-")[0])
        var sDate = parseInt(vaca.start_date.split("-")[2])
        var month = parseInt(vaca.start_date.split("-")[1])
      //  var year = parseInt(vaca.start_date.split("-")[2])
        var year = parseInt(vaca.start_date.split("-")[0])
       // var eDate = parseInt(vaca.end_date.split("-")[0])
        var eDate = parseInt(vaca.end_date.split("-")[2])
        var sTime = parseInt(vaca.start_time.split(":")[0])
        var eTime = parseInt(vaca.end_time.split(":")[0])
        var event_time = '';

      //  if( sTime > 12 )
       //     event_time += (sTime - 12)+'PM~'
      //  else
      //      event_time += sTime+'AM~'

       // if( eTime > 12 )
      //      event_time += (eTime - 12)+'PM'
      //  else
      //      event_time += eTime+'AM'

        event_time += sTime+'~'
        event_time += eTime
        if( sTime==0 && eTime==0 )
            event_time = 'All day'
        if( day >= sDate && day <= eDate && mon == parseInt(month) && yr == parseInt(year) )
        {
            events.push({
                id: vaca.id, // Start from 4 and increment for each event
                name: vaca.request_type, // Name of the event
                time: event_time, // Time of the event
                datetime: vaca.start_date, // Datetime of the event
                href: '#' // Link or href of the event
            });
        }
    }
    
    return events;
}

function getVacatonsInRange(yr, mon, day){
    let vacasArray=[];
    if (_userType == "manager"){
        vacasArray = vacas
    }else{
        vacasArray = vacas.filter(vaca=>_userId == vaca.user_id);
    }
    var vacass = [];
    for( const vaca of vacasArray ){
       // var sDate = parseInt(vaca.start_date.split("-")[0])
        var sDate = parseInt(vaca.start_date.split("-")[2])
        var month = parseInt(vaca.start_date.split("-")[1])
      //  var year = parseInt(vaca.start_date.split("-")[2])
        var year = parseInt(vaca.start_date.split("-")[0])
        //var eDate = parseInt(vaca.end_date.split("-")[0])
        var eDate = parseInt(vaca.end_date.split("-")[2])
        var sTime = parseInt(vaca.start_time.split(":")[0])
        var eTime = parseInt(vaca.end_time.split(":")[0])

        if( day >= sDate && day <= eDate && mon == parseInt(month) && yr == parseInt(year) )
        {
            vacass.push(vaca);
        }
    }
    return vacass;
}

function getDaysInMonth(year, month) {
    
    tyear.value = year;
    tmonth.value = month;

    const firstDay = new Date(year, month - 1, 1);
    const lastDay = new Date(year, month, 0);
    const daysInMonth = lastDay.getDate();

    // Find the day of the week for the first day of the month (0 = Sunday, 1 = Monday, etc.)
    const startDayOfWeek = firstDay.getDay();

    // Calculate the number of days to pad at the beginning and end of the array
    const daysToPadBefore = startDayOfWeek;
    const totalDaysInGrid = 42;
    const daysToPadAfter = totalDaysInGrid - (daysInMonth + daysToPadBefore);

    // Create an array of day objects
    const days = [];

    // Calculate the number of days from the previous month
    const prevMonth = month === 1 ? 12 : month - 1;
    const prevYear = month === 1 ? year - 1 : year;
    const daysInPrevMonth = new Date(prevYear, prevMonth, 0).getDate();

    // Add padding days from the previous month
    for (let i = daysToPadBefore; i > 0; i--) {
        const prevMonthDay = daysInPrevMonth - i + 1;
        days.push({
        date: `${prevYear}-${prevMonth.toString().padStart(2, '0')}-${prevMonthDay.toString().padStart(2, '0')}`,
        events: [],
        });
    }

    // Add days for the current month
    for (let i = 1; i <= daysInMonth; i++) {
        if( i == currentDay && month == currentMonth)
        {
            days.push({
            date: `${year}-${month.toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`,
            isToday: true,
            events: createEvents(i, month, year),
            isCurrentMonth: true,
            });
        }
        else
        {
            days.push({
            date: `${year}-${month.toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`,
            events: createEvents(i, month, year),
            isCurrentMonth: true,
            });
        }
    }

    // Calculate the number of days from the next month
    const nextMonth = month === 12 ? 1 : month + 1;
    const nextYear = month === 12 ? year + 1 : year;

    // Add padding days from the next month
    for (let i = 1; i <= daysToPadAfter; i++) {
        days.push({
        date: `${nextYear}-${nextMonth.toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`,
        events: [],
        isCurrentMonth: false,
        });
    }

    return days;
}

</script>