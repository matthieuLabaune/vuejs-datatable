<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th v-for="column in response.displayables" scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ column }}
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Edit</span>
              </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="records in response.records">
              <td v-for="columnValue in records" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ columnValue }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <a href="#" class="text-indigo-600 hover:text-indigo-900">Edit</a>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const people = [
  {name: 'Jane Cooper', title: 'Regional Paradigm Technician', role: 'Admin', email: 'jane.cooper@example.com'},
  // More people...
]

export default {
  setup() {
    return {
      people,
    }
  },
  props: ['endpoint'],
  data() {
    return {
      response: {
        displayables: [],
        records: []
      }
    }
  },
  mounted() {
    this.getRecords()
  },
  methods: {
    getRecords() {
      return axios.get(`${this.endpoint}`)
          .then((response) => {
            this.response = response.data.data
          })
    }
  }
}
</script>