<template>
  <div class="flex flex-col">
    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 min-w-full sm:px-6 lg:px-8">
        <!--        SEARCH BAR      -->
        <form action="#" @submit.prevent="getRecords">
          <div class="grid grid-cols-4 content-end gap-4 py-4">
            <div class="col-span-1">
              <select v-model="search.column"
                      class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option :value="column" v-for="column in response.displayables">{{  response.custom_columns[column] || column }}</option>
              </select>
            </div>
            <div class="col-span-1">
              <select v-model="search.operator"
                      class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="equals">=</option>
                <option value="contains">contient</option>
                <option value="starts_with">commence par</option>
                <option value="ends_with">fini par</option>
                <option value="greater_than">plus grand que</option>
                <option value="less_than">plus petit que</option>
              </select>
            </div>
            <div class="col-span-2">
              <div>
                <!--              <label for="email" class="block text-sm font-medium text-gray-700">Search candidates</label>-->
                <div class="flex rounded-md shadow-sm">
                  <div class="relative flex items-stretch flex-grow focus-within:z-10">
                    <input v-model="search.value"
                           class="focus:ring-indigo-500 focus:border-indigo-500 block w-full rounded-none rounded-l-md pl-4 sm:text-sm border-gray-300"
                           placeholder="Rechercher"/>
                  </div>
                  <button type="submit"
                          class="-ml-px relative inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    <!--                -->
                    <!--    ADD A RESET OPTION   -->
                    <!--                -->
                    <!--                  <SortAscendingIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />-->
                    <span>{{ search.active !== null ? 'Rechercher' : 'Annuler'  }}</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </form>

        <!--        SEARCH BAR      -->
        <div class="grid grid-cols-4 content-end gap-4 py-4">
          <div class="col-span-3">
            <div class="flex md:max-w-3xl md:mx-auto lg:max-w-none lg:mx-0 xl:px-0">
              <div class="w-full">
                <label for="search" class="sr-only">Rechercher</label>
                <div class="relative">
                  <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                    <SearchIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
                  </div>
                  <input id="filter" v-model="quickSearchQuery" name="search"
                         class="block w-full bg-white border border-gray-300 rounded-md pl-10 pr-3 text-sm placeholder-gray-500 focus:outline-none focus:text-gray-900 focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                         placeholder="Recherche dans la sélection" type="search"/>
                </div>
              </div>
            </div>
          </div>
          <div class="flex justify-self-end col-span-1">
            <div class="">
              <!--                -->
              <!--    ADD TO PROPS AS OPTIONS   -->
              <!--                -->
              <label class="sr-only block text-xs font-medium text-gray-700">Nombre d'enregistrements</label>
              <select id="limit" v-model="limit" @change="getRecords"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
          </div>
        </div>
        <!--       END SEARCH BAR      -->
        <!--       TABLE     -->
        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <!--       TABLE HEADER    -->
            <tr>
              <th v-for="column in response.displayables" scope="col"
                  class="px-6 py-3 text-left text-xs font-medium font-semibold text-gray-700 uppercase tracking-wider">
                <span class="sortable" @click="sortBy(column)">{{ response.custom_columns[column] || column }}</span>
                <div class="arrow"
                     v-if="sort.key === column"
                     :class="{'arrow--asc' : sort.order === 'asc', 'arrow--desc': sort.order === 'desc'}"
                ></div>
              </th>
              <!--                -->
              <!--    ADD TO PROPS AS OPTIONS EDITABLE INPUT IN TABLE EDIT LINK TO EDIT PAGE OR NOT EDITABLE   -->
              <!--                -->
              <th scope="col" class="relative px-6 py-3">
                <span class="sr-only">Edit</span>
              </th>
            </tr>
            <!--       END TABLE HEADER    -->
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="record in filteredRecords">
              <td v-for="(columnValue, column) in record" class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                <!--    Show only if currently editing    -->
                <template v-if="editing.id === record.id && isUpdatable(column)">
                  <div class="mt-1 relative rounded-md shadow-sm">
                    <input v-model="editing.form[column]" type="text"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           :class="{'border-2 border-red-300 text-red-900 mb-2': editing.errors[column]}"
                    />
                    <div v-if="editing.errors[column]"
                         class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                      <ExclamationCircleIcon v-if="editing.errors[column]" class="h-5 w-5 text-red-500"/>
                    </div>
                  </div>
                  <p v-if="editing.errors[column]" class="text-sm text-red-600">
                    {{ editing.errors[column][0] }}</p>
                </template>
                <!--    Show only if not currently editing    -->
                <template v-else>
                  {{ columnValue }}
                </template>
              </td>
              <!--       EDIT BUTTON      -->
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <!--    Show only if not currently editing    -->
                <a href="#" @click.prevent="edit(record)" class="text-indigo-500 hover:text-indigo-300"
                   v-if="editing.id !== record.id">Modifier</a>
                <!--    Get back the editing.id value to null if we want to cancel the edit action    -->
                <!--    Show only if currently editing    -->
                <template v-if="editing.id === record.id">
                  <a href="#" @click.prevent="update()" class="text-green-600 hover:text-green-300">Enregistrer</a>
                  <br>
                  <a href="#" @click.prevent="editing.id = null" class="text-red-600 hover:text-red-300">Annuler</a>
                </template>
              </td>
              <!--       END EDIT BUTTON      -->
            </tr>
            </tbody>
          </table>
        </div>
        <!--       END TABLE      -->
      </div>
    </div>
  </div>
</template>

<script>
import {SearchIcon} from "@heroicons/vue/solid";
import queryString from "query-string"
import {ExclamationCircleIcon, SortAscendingIcon} from '@heroicons/vue/solid'

export default {
  components: {
    SearchIcon, ExclamationCircleIcon, SortAscendingIcon
  },
  props: ['endpoint'],
  data() {
    return {
      response: {
        displayables: [],
        updatables: [],
        records: []
      },
      sort: {
        key: 'id',
        order: 'asc',
      },
      limit: 10,
      quickSearchQuery: '',
      editing: {
        id: null,
        form: {},
        errors: [],
      },
      search: {
        value: '',
        operator: 'equals',
        column: 'id'
      }
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
      return axios.get(`${this.endpoint}?${this.getQueryParameters()}`)
          .then((response) => {
            this.response = response.data.data
          })
    },
    getQueryParameters() {
      return queryString.stringify({
        limit: this.limit,
        ...this.search
      })
    },
    sortBy(column) {
      this.sort.key = column
      this.sort.order = this.sort.order === 'asc' ? 'desc' : 'asc'
    },
    edit(record) {
      // Get back the errors to an empty array
      this.editing.errors = []
      this.editing.id = record.id
      // Method with lodash to pick up the value we wnat to update from a specific record
      this.editing.form = _.pick(record, this.response.updatables)
    },
    isUpdatable(column) {
      return this.response.updatables.includes(column)
    },
    update() {
      axios.patch(`${this.endpoint}/${this.editing.id}`, this.editing.form)
          .then(() => {
            this.getRecords().then(() => {
              this.editing.id = null
              this.editing.form = {}
            })
          }).catch((error) => {
        this.editing.errors = error.response.data.errors
      })
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