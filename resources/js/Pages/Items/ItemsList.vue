<script>
import AppLayout from '../../Layouts/Default.vue'
import Input from '../../Components/Input.vue'
import Label from '../../Components/Label.vue'
import Button from '../../Components/Button.vue'
import Checkbox from '../../Components/Checkbox.vue'

export default {
    name: 'ItemsList',
    components: {
        Input,
        Label,
        Button,
        Checkbox,
        AppLayout
    },
    data () {
        return {
            showModal: false,
            createForm: {
                name: null
            },
            editForm: {
                name: null,
                itemId: null
            },
            items: [],
            isBusy: false
        }
    },
    mounted () {
       this.onFetchItems()
    },
    methods: {
        onCreateNewItem (e) {
            this.isBusy = true

            e.preventDefault()

            axios.post('items', this.createForm)
                .then(({ data }) => {
                    this.items.unshift(data)
                    this.createForm.name = null
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
        },
        resetForm () {
            this.editForm = {
                name: null,
                itemId: null
            }
        },
        onChangeCompleteStatus (item) {
            const newStatus = !(item.completed_at !== null) // new status is the inverse of current status
            this.isBusy = true
            axios.put(`items/${item.id}`, { is_complete: newStatus })
                .then(() => {
                    this.changeItemCompleteStatus(item)
                    this.isBusy = false
                }).catch(e => {
                console.log(e)
                this.isBusy = false
                return alert('something went wrong, please try refresh page and again')
            })
        },
        changeItemCompleteStatus(item) {
            if (item.completed_at === null) {
               return  item.completed_at = new Date()
            }

            item.completed_at = null
        },
        onDeleteItem (itemId, index) {
            if (confirm('Are you sure you want to delete this item?')) {
                axios.delete(`items/${itemId}`)
                    .then(() => {
                      this.items.splice(index, 1)
                        this.isBusy = false
                    }).catch(e => {
                    console.log(e)
                    this.isBusy = false
                    return alert('something went wrong, please try refresh page and again')
                })
            }
        },
        onEdit (item, index) {
            this.editForm = Object.assign({}, { name: item.name, itemId: item.id, index: index })
            this.showModal = true
        },
        updateItem () {
            this.isBusy = true
            axios.put(`items/${this.editForm.itemId}`, {name: this.editForm.name})
                .then(() => {
                    this.$router.go(0) // reload page, for larger app we could use vuex instead
                }).catch(e => {
                console.log(e)
                this.isBusy = false
                return alert('something went wrong, please try refresh page and again')
            })
        },
        onFetchItems() {
            this.isBusy = true

            axios.get('items')
                .then(({ data }) => {
                    this.items = data
                    this.isBusy = false
                }).catch(e => {
                this.isBusy = false
                return alert('something went wrong, please try refresh page and again')
            })
        }
    }
}
</script>

<template>
    <AppLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight capitalize">
                Todo List
            </h2>
        </template>

        <div class="py-12" :class="isBusy ? 'opacity-25 pointer-events-none' :''">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-16">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200 capitalize">

                        <div class="mb-4 px-4 py-8 bg-gray-100">
                            <form class="grid grid-cols-5 max-w-4xl" @submit.prevent="onCreateNewItem">
                                <div class="col-span-3">
                                    <Input id="name" type="text" class="block w-full" v-model="createForm.name"
                                           required autofocus/>
                                </div>

                                <div class="mx-4 ml-auto col-span-2 justify-items-end">
                                    <Button class="ml-4 py-3 bg-gray-900"
                                            :class="{ 'opacity-25': createForm.processing }"
                                            :disabled="createForm.processing">
                                        Add
                                    </Button>

                                </div>
                            </form>
                        </div>

                        <ul class="flex flex-col space-y-2 max-w-4xl" v-if="items.length">
                            <li v-for="(item, index) in items" class="py-4 even:bg-gray-50" v-on:listUpdated="alert()">
                                <div class="mx-1.5 flex space-x-3">
                                    <div class="flex-none">
                                        <Checkbox :checked="item.completed_at !== null"
                                                  @change="onChangeCompleteStatus(item)"
                                                  :id="`item-${item.id}`" :name="`item-${item.id}`"/>
                                    </div>
                                    <div class="grow" :class="item.completed_at !== null ? 'line-through' : ''">
                                        {{ item.name }}
                                    </div>
                                    <div class="flex-none flex items-center space-x-1.5">
                                        <svg class="w-4 h-4 cursor-pointer fill-gray-500 hover:fill-gray-900"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                             @click="onEdit(item, index)">
                                            <g>
                                                <path fill="none" d="M0 0h24v24H0z"></path>
                                                <path
                                                    d="M7.243 18H3v-4.243L14.435 2.322a1 1 0 0 1 1.414 0l2.829 2.829a1 1 0 0 1 0 1.414L7.243 18zM3 20h18v2H3v-2z"></path>
                                            </g>
                                        </svg>
                                        <svg class="w-4 h-4 cursor-pointer fill-gray-500 hover:fill-gray-800"
                                             xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                             viewBox="0 0 24 24" @click="onDeleteItem(item.id, index)">
                                            <path fill-rule="evenodd"
                                                  d="M16 1.75V3h5.25a.75.75 0 010 1.5H2.75a.75.75 0 010-1.5H8V1.75C8 .784 8.784 0 9.75 0h4.5C15.216 0 16 .784 16 1.75zm-6.5 0a.25.25 0 01.25-.25h4.5a.25.25 0 01.25.25V3h-5V1.75z"></path>
                                            <path
                                                d="M4.997 6.178a.75.75 0 10-1.493.144L4.916 20.92a1.75 1.75 0 001.742 1.58h10.684a1.75 1.75 0 001.742-1.581l1.413-14.597a.75.75 0 00-1.494-.144l-1.412 14.596a.25.25 0 01-.249.226H6.658a.25.25 0 01-.249-.226L4.997 6.178z"></path>
                                            <path
                                                d="M9.206 7.501a.75.75 0 01.793.705l.5 8.5A.75.75 0 119 16.794l-.5-8.5a.75.75 0 01.705-.793zm6.293.793A.75.75 0 1014 8.206l-.5 8.5a.75.75 0 001.498.088l.5-8.5z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <p v-else class="text-center italic">No items yet...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true" v-if="showModal">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed z-10 inset-0 overflow-y-auto">
                <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                    <div
                        class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Edit Todo
                                    Item</h3>
                                <form class="mt-3 flex flex-col space-y-2" @submit.prevent="updateItem">
                                    <input type="hidden" name="itemId" v-model="editForm.itemId">
                                    <Input id="edit-name" type="text" class="block w-full"
                                           v-model="editForm.name"
                                           required autofocus/>

                                    <div class=" px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <Button class="ml-4 py-3 bg-gray-900"
                                                :class="{ 'opacity-25': isBusy }"
                                                :disabled="isBusy">
                                            Save Changes
                                        </Button>

                                        <Button id="cancelButton"
                                                class="ml-4 py-3 bg-gray-400 text-gray-700 hover:bg-gray-900 hover:text-white"
                                                type="button"
                                                @click="showModal = false"
                                                :disabled="isBusy">
                                            Cancel
                                        </Button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

</template>
