<template>
  <div>
    <dl class="mt-5">
      <div class="relative bg-white pt-5 px-4 pb-12 sm:pt-6 sm:px-6 shadow rounded-lg overflow-hidden">
        <dt>
          <div class="absolute bg-indigo-500 rounded-md p-3">
            <component :is="icon" class="h-6 w-6 text-white" aria-hidden="true" />
          </div>
          <p class="ml-16 text-sm font-medium text-gray-500 truncate">{{ name }}</p>
        </dt>
        <dd class="ml-16 pb-6 flex items-baseline sm:pb-7">
          <p class="text-2xl font-semibold text-gray-900">
            {{ stat }}
          </p>
          <p :class="[type === 'increase' ? 'text-green-600' : 'text-red-600', 'ml-2 flex items-baseline text-sm font-semibold']">
            <ArrowSmUpIcon v-if="type === 'increase'" class="self-center flex-shrink-0 h-5 w-5 text-green-500" aria-hidden="true" />
            <ArrowSmDownIcon v-else class="self-center flex-shrink-0 h-5 w-5 text-red-500" aria-hidden="true" />
            <span class="sr-only"> {{ type === 'increase' ? 'Increased' : 'Decreased' }} by </span>
            {{ change }}
          </p>
          <div class="absolute bottom-0 inset-x-0 bg-gray-50 px-4 py-4 sm:px-6">
            <div class="text-sm">
              <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                View all<span class="sr-only"> {{ name }} stats</span></a
              >
            </div>
          </div>
        </dd>
      </div>
    </dl>
  </div>
</template>

<script>
import {ArrowSmDownIcon, ArrowSmUpIcon, UserGroupIcon, IdentificationIcon} from '@heroicons/vue/solid'
import { CursorClickIcon, MailOpenIcon, UsersIcon } from '@heroicons/vue/outline'


export default {
  components: {
    ArrowSmDownIcon,
    ArrowSmUpIcon,
  },
  props:[
    'name',
    'stat',
    'icon',
    'change',
    'type'
  ],
}
</script>