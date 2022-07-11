<script setup>
let user = authService.getUser()
</script>
<script>
import Input from '../../Components/Input.vue'
import Label from '../../Components/Label.vue'
import Button from '../../Components/Button.vue'
import DefaultLayout from '../../Layouts/Default.vue'
import router from '../../router/index.js'
import authService from '../../services/authService.js'

export default {
    name: 'MyProfile',
    components: {
        Input,
        Label,
        Button,
        DefaultLayout
    },
    data () {
        return {
            isEditProfile: false,
            form: {
                name: this.user.name,
                email: this.user.email
            },
            errors: [],
            hasErrors: false,
            isBusy: false
        }
    },
    methods: {
        onSubmit (e) {
            this.isBusy = true
            e.preventDefault()

            axios.put('my-profile', this.form)
                .then(({ data }) => {
                    this.user = data
                    authService.updateUser(data)
                    this.isEditProfile = false
                    this.isBusy = false
                }).catch(e => {
                if (e.response.status === 422) {
                    this.hasErrors = true
                    this.errors = e.response.data.errors
                    return this.isBusy = false
                }
                this.isBusy = false
                return alert('something went wrong, please try refresh page and again')
            })

        }
    }
}
</script>

<template>
    <Head title="My Profile"/>

    <DefaultLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight capitalize">
                profile details
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-16">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 capitalize">
                        <template v-if="isEditProfile">
                            <div class="flex">
                                <Button class="ml-auto bg-gray-600" type="button" @click="isEditProfile = false"
                                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Cancel
                                </Button>
                            </div>

                            <div v-if="hasErrors">

                                <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                    <li v-for="(error, key) in errors" :key="key">{{ error[0] }}</li>
                                </ul>
                            </div>

                            <form @submit.prevent="onSubmit">
                                <div>
                                    <Label for="name" value="Name"/>
                                    <Input id="name" type="text" class="mt-1 block w-full" v-model="form.name"
                                           required autofocus/>
                                </div>

                                <div class="mt-4">
                                    <Label for="email" value="Email"/>
                                    <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email"
                                           required/>
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <Button class="ml-4 bg-gray-900" :class="{ 'opacity-25': form.processing }"
                                            :disabled="form.processing">
                                        Save Changes
                                    </Button>

                                </div>
                            </form>
                        </template>

                        <template v-else>
                            <div class="flex">
                                <Button type="button" class="ml-auto" @click="isEditProfile = true">
                                    Edit Profile
                                </Button>
                            </div>
                            <table class="mb-2 border-l-[2.5px] border-gray-400">
                                <tr>
                                    <td class="pl-3.5 text-[#9094A2]">full name:</td>
                                    <td class="pl-4">{{ user.name }}</td>
                                </tr>
                                <tr>
                                    <td class="pl-3.5 text-[#9094A2]">email:</td>
                                    <td class="pl-4 lowercase">{{ user.email }}</td>
                                </tr>
                            </table>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
