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
                <span class="sortable" @click="sortBy(column)">{{ column }}</span>
                <div
                    class="arrow"
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

export default {

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
      }
    }
  },
  computed: {
    filteredRecords() {
      let data = this.response.records

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