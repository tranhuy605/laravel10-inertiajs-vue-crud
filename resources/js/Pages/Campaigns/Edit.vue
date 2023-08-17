<template>
  <div>
    <Head/>
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/campaigns">Campaigns</Link>
    </h1>
    <trashed-message v-if="campaign.deleted_at" class="mb-6" @restore="restore"> This campaign has been deleted. </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
        <div class="flex items-center px-8 py-4 bg-gray-50 border-t border-gray-100">
          <button v-if="!campaign.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete campaign</button>
          <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Update campaign</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import Datepicker from 'vue3-datepicker/src/datepicker/Datepicker.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    LoadingButton,
    TextInput,
    TrashedMessage,
    Datepicker
  },
  layout: Layout,
  props: {
    campaign: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        title: this.campaign.title,
        description: this.campaign.description,
        start_date: new Date(this.campaign.start_date),
        end_date: new Date(this.campaign.end_date),
        amount: this.campaign.amount,
      }),
    }
  },
  methods: {
    update() {
      this.form.transform((data) => ({
            ...data,
            start_date: data.start_date.toISOString().split('T')[0],
            end_date: data.end_date.toISOString().split('T')[0],
        })).put(`/campaigns/${this.campaign.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this campaign?')) {
        this.$inertia.delete(`/campaigns/${this.campaign.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this campaign?')) {
        this.$inertia.put(`/campaigns/${this.campaign.id}/restore`)
      }
    },
  },
}
</script>
