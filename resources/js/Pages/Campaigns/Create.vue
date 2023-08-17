<template>
  <div>
    <Head title="Create Organization" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/campaigns">Campaign</Link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="flex flex-wrap -mb-8 -mr-6 p-8">
          <text-input v-model="form.title" :error="form.errors.title" class="pb-8 pr-6 w-full lg:w-1/2" label="Title" />
          <text-input v-model="form.description" :error="form.errors.description" class="pb-8 pr-6 w-full lg:w-1/2" label="Description" />
          <div class="pb-8 pr-6 w-full lg:w-1/2">
            <label class="form-label" for="start_date">Start Date:</label>
            <datepicker id="start_date" v-model="form.start_date"  class="form-input" inputFormat="yyyy-MM-dd"/>
            <div v-if="form.errors.start_date" class="form-error">{{ form.errors.start_date }}</div>
          </div>
          <div class="pb-8 pr-6 w-full lg:w-1/2">
            <label class="form-label" for="end_date">End Date:</label>
            <datepicker id="end_date" v-model="form.end_date" class="form-input" inputFormat="yyyy-MM-dd" />
            <div v-if="form.errors.end_date" class="form-error">{{ form.errors.end_date }}</div>
          </div>
          <text-input v-model="form.amount" :error="form.errors.amount" class="pb-8 pr-6 w-full lg:w-1/2" label="Amount" />
        </div>
        <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
          <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create campaign</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import Datepicker from 'vue3-datepicker/src/datepicker/Datepicker.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    Datepicker
  },
  layout: Layout,
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
          title: null,
          description: null,
          start_date: new Date(),
          end_date:  new Date(),
          amount:0
      }),
    }
  },
  methods: {
    store() {
      this.form.transform((data) => ({
          ...data,
          start_date: data.start_date.toISOString().split('T')[0],
          end_date: data.end_date.toISOString().split('T')[0],
      })).post('/campaigns')
    },
  },
}
</script>
