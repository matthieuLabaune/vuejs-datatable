<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">

        <div class="min-w-0 flex-1 md:px-8 lg:px-0 xl:col-span-6">
          <div class="flex items-center px-6 py-4 md:max-w-3xl md:mx-auto lg:max-w-none lg:mx-0 xl:px-0">
            <div class="w-full">
              <label for="search" class="sr-only">Rechercher</label>
              <div class="relative">
                <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                  <SearchIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                </div>
                <input id="filter" v-model="quickSearchQuery" name="search" class="block w-full bg-white border border-gray-300 rounded-md py-2 pl-10 pr-3 text-sm placeholder-gray-500 focus:outline-none focus:text-gray-900 focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search" type="search" />
              </div>
            </div>
          </div>
        </div>

        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th v-for="column in response.displayables" scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                <span class="sortable" @click="sortBy(column)">{{ column }}</span>
                <div class="arrow"
                     v-if="sort.key === column"
                     :class="{'arrow--asc' : sort.order === 'asc', 'arrow--desc': sort.order === 'desc'}"
                ></div>
              </th>
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Edit</span>
              </th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="records in filteredRecords">
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
import {SearchIcon} from "@heroicons/vue/solid";

export default {
  components:{
    SearchIcon
  },
  props: ['endpoint'],
  data() {
    return {
      response: {
        displayables: [],
        records: []
      },
      sort: {
        key: 'id',
        order: 'asc',
      },
      quickSearchQuery: ''
    }
  },
  computed: {
    filteredRecords() {
      let data = this.response.records

      data = data.filter((row) => {
        return Object.keys(row).some((key) => {
          return String(row[key]).toLowerCase().indexOf(this.quickSearchQuery.toLowerCase()) > -1
        })
      })

      if (this.sort.key) {
        // With Lodash
        data = _.orderBy(data, (i) => {
          let value = i[this.sort.key]
          // isNaN is Not A Number
          if (!isNaN(parseFloat(value))) {
            return parseFloat(value)
          }
          return String(i[this.sort.key]).toLowerCase()
        }, this.sort.order)
      }

      return data
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
    },
    sortBy(column) {
      this.sort.key = column
      this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
    }
  }
}
</script>

<style lang="css">
.sortable {
  cursor: pointer
}

.arrow {
  display: inline-block;
  vertical-align: middle;
  width: 0;
  height: 0;
  margin-left: 5px;
  opacity: .6;
}

.arrow--asc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-bottom: 4px solid #222;
}

.arrow--desc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-top: 4px solid #222;
}
</style>