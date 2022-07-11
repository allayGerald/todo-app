<script>
import router from '../../router'
import AuthLayout from '../../Layouts/Auth.vue'
import Input from '../../Components/Input.vue'
import Label from '../../Components/Label.vue'
import Button from '../../Components/Button.vue'
import authService from '../../services/authService'

export default {
    name: 'Register',
    components: {
        Input,
        Label,
        Button,
        AuthLayout
    },
    data () {
        return {
            form: {
                name: '',
                email: '',
                password: '',
                password_confirmation: ''
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
            if (this.form.password !== this.form.password_confirmation) {
                return alert('Password confirmation does not match')
            }

            axios.post('register', this.form)
                .then(({ data }) => {
                    console.log(data, router)
                    authService.setSession(data)
                    this.isBusy = false
                    router.push({path: 'items'})
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
    <AuthLayout>
        <div v-if="hasErrors">
            <div class="font-medium text-red-600">Whoops! Something went wrong.</div>

            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                <li v-for="(error, key) in errors" :key="key">{{ error[0] }}</li>
            </ul>
        </div>

        <form @submit.prevent="onSubmit">
            <div>
                <Label for="name" value="Name"/>
                <Input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                       autocomplete="name"/>
            </div>

            <div class="mt-4">
                <Label for="email" value="Email"/>
                <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                       autocomplete="username"/>
            </div>

            <div class="mt-4">
                <Label for="password" value="Password"/>
                <Input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                       autocomplete="new-password"/>
            </div>

            <div class="mt-4">
                <Label for="password_confirmation" value="Confirm Password"/>
                <Input id="password_confirmation" type="password" class="mt-1 block w-full"
                       v-model="form.password_confirmation" required autocomplete="new-password"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Button class="ml-4" :class="{ 'opacity-25': isBusy }" :disabled="isBusy">
                    Register
                </Button>

                <router-link to="/login" class="ml-4 button-outline" :class="{ 'opacity-25': isBusy }"
                   :disabled="isBusy">
                    login
                </router-link>
            </div>
        </form>
    </AuthLayout>
</template>

