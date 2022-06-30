<template>
  <section aria-labelledby="announcements-title">
    <div class="rounded-lg bg-white overflow-hidden shadow">
      <div class="p-6">
        <h2 class="text-base font-medium text-gray-900" id="announcements-title">Mes ressources</h2>
        <div class="flow-root mt-6">
          <ul role="list" class="-my-5 divide-y divide-gray-200">
            <li v-for="announcement in announcements" :key="announcement.id" class="py-5">
              <div class="flex">
                <div class="mr-4 flex-shrink-0">
                  <svg class="h-full w-16 border border-gray-300 bg-white text-gray-300" preserveAspectRatio="none" stroke="currentColor" fill="none" viewBox="0 0 200 200" aria-hidden="true">
                    <path vector-effect="non-scaling-stroke" stroke-width="1" d="M0 0l200 200M0 200L200 0" />
                  </svg>
                </div>
                <div class="relative focus-within:ring-2 focus-within:ring-cyan-500">
                  <h3 class="text-sm font-semibold text-gray-800">
                    <a :href="announcement.href" class="hover:underline focus:outline-none">
                      <!-- Extend touch target to entire panel -->
                      <span class="absolute inset-0" aria-hidden="true" />
                      {{ announcement.title }}
                    </a>
                  </h3>
                  <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                    {{ announcement.preview }}
                  </p>
                </div>
              </div>
            </li>
          </ul>
        </div>
        <div class="mt-6">
          <a href="#" class="w-full flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"> Voir tous </a>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "DocumentCard",
  setup() {
    return {
      announcements
    }
  }
}
const announcements = [
  {
    id: 1,
    title: 'DVT - Feuille de route - Clubs - 2022',
    href: '#',
    preview: 'Le dispositif de valorisation territoriale - DVT'
  },
  {
    id: 2,
    title: 'DVT - Feuille de route - Comités Départementaux - 2022',
    href: '#',
    preview:'Le dispositif de valorisation territoriale - DVT'
  },
  {
    id: 3,
    title: 'DVT - Feuille de route - Comités Régionaux - 2022',
    href: '#',
    preview:'Le dispositif de valorisation territoriale - DVT'
  },
]
</script>

<style scoped>

</style>