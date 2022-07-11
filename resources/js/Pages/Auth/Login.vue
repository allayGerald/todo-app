<script>
import AuthLayout from '../../Layouts/Auth.vue'
import Input from '../../Components/Input.vue'
import Label from '../../Components/Label.vue'
import Checkbox from '../../Components/Checkbox.vue'
import Button from '../../Components/Button.vue'
import ButtonOutline from '../../Components/ButtonOutline.vue'
import router from '../../router/index.js'
import authService from '../../services/authService.js'

export default {
    name: 'Login',
    components: {
        Input,
        Label,
        Checkbox,
        Button,
        ButtonOutline,
        AuthLayout
    },
    data () {
        return {
            form: {
                email: '',
                password: '',
                remember: false
            },
            isBusy: false,
            hasErrors: false,
            errors: []
        }
    },
    methods: {
        onSubmit (e) {
            this.isBusy = true
            e.preventDefault()

            axios.post('login', this.form)
                .then(({ data }) => {
                    authService.setSession(data)
                    this.isBusy = false
                    router.push({path: 'items'})
                }).catch(e => {
                this.isBusy = false
                if (e.response.status === 422) {
                    this.hasErrors = true
                    return this.errors = e.response.data.errors
                }
                if (e.response.status === 401) {
                    console.log(e.response.data)
                    this.hasErrors = true
                    return this.errors = [[e.response.data.message]]
                }

                return alert('something went wrong, please try refresh page and again')
            })

        }
    }
}
</script>

<template>
    <AuthLayout>
        <div v-if="hasErrors">
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                <li v-for="(error, key) in errors" :key="key">{{ error[0] }}</li>
            </ul>
        </div>
        <form @submit.prevent="onSubmit">
            <div>
                <Label for="email" value="Email"/>
                <Input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                       autocomplete="username"/>
            </div>

            <div class="mt-4">
                <Label for="password" value="Password"/>
                <Input id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                       autocomplete="current-password"/>
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember"/>
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Button class="ml-4" :class="{ 'opacity-25': isBusy }" :disabled="isBusy">
                    Log in
                </Button>
                <router-link to="/register" class="ml-4 button-outline" :class="{ 'opacity-25': isBusy }" :disabled="isBusy">
                    register
                </router-link>
            </div>
        </form>
    </AuthLayout>
</template>
